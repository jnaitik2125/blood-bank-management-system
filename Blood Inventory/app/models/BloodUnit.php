<?php
declare(strict_types=1);

class BloodUnit extends Model
{
    protected string $table = 'blood_units';

    public function markExpired(): void
    {
        $stmt = $this->db->prepare("UPDATE blood_units SET status = 'expired' WHERE expiry_date < CURDATE() AND status = 'available'");
        $stmt->execute();
    }

    public function stockByGroup(): array
    {
        $stmt = $this->db->query("SELECT blood_group, SUM(quantity) AS total_quantity FROM blood_units WHERE status = 'available' GROUP BY blood_group ORDER BY blood_group");
        return $stmt->fetchAll();
    }

    public function consume(string $bloodGroup, float $quantity): bool
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("SELECT id, quantity FROM blood_units WHERE blood_group = :bg AND status = 'available' AND expiry_date >= CURDATE() ORDER BY expiry_date ASC");
            $stmt->execute(['bg' => $bloodGroup]);
            $units = $stmt->fetchAll();
            $remaining = $quantity;
            foreach ($units as $unit) {
                if ($remaining <= 0) {
                    break;
                }
                $take = min($remaining, (float) $unit['quantity']);
                $newQty = (float) $unit['quantity'] - $take;
                $status = $newQty <= 0 ? 'used' : 'available';
                $update = $this->db->prepare('UPDATE blood_units SET quantity = :qty, status = :status WHERE id = :id');
                $update->execute([
                    'qty' => max(0, $newQty),
                    'status' => $status,
                    'id' => $unit['id'],
                ]);
                $remaining -= $take;
            }

            if ($remaining > 0) {
                $this->db->rollBack();
                return false;
            }
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function searchable(string $bloodGroup = '', string $status = ''): array
    {
        $sql = 'SELECT * FROM blood_units WHERE 1=1';
        $params = [];
        if ($bloodGroup !== '') {
            $sql .= ' AND blood_group = :blood_group';
            $params['blood_group'] = $bloodGroup;
        }
        if ($status !== '') {
            $sql .= ' AND status = :status';
            $params['status'] = $status;
        }
        $sql .= ' ORDER BY expiry_date ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function searchablePaginated(string $bloodGroup = '', string $status = '', int $page = 1, int $perPage = 10): array
    {
        $from = 'FROM blood_units WHERE 1=1';
        $params = [];
        if ($bloodGroup !== '') {
            $from .= ' AND blood_group = :blood_group';
            $params['blood_group'] = $bloodGroup;
        }
        if ($status !== '') {
            $from .= ' AND status = :status';
            $params['status'] = $status;
        }
        $from .= ' ORDER BY expiry_date ASC';
        return $this->paginateQuery($from, $params, $page, $perPage);
    }
}
