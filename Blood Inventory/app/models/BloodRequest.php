<?php
declare(strict_types=1);

class BloodRequest extends Model
{
    protected string $table = 'requests';

    public function allWithUsers(string $status = '', string $bloodGroup = ''): array
    {
        $sql = '
            SELECT r.*, u.name AS requested_by_name
            FROM requests r
            LEFT JOIN users u ON u.id = r.requested_by
            WHERE 1=1
        ';
        $params = [];
        if ($status !== '') {
            $sql .= ' AND r.status = :status';
            $params['status'] = $status;
        }
        if ($bloodGroup !== '') {
            $sql .= ' AND r.blood_group = :blood_group';
            $params['blood_group'] = $bloodGroup;
        }
        $sql .= ' ORDER BY r.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function allWithUsersPaginated(string $status = '', string $bloodGroup = '', int $page = 1, int $perPage = 10): array
    {
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));
        $offset = ($page - 1) * $perPage;

        $base = ' FROM requests r LEFT JOIN users u ON u.id = r.requested_by WHERE 1=1 ';
        $params = [];
        if ($status !== '') {
            $base .= ' AND r.status = :status ';
            $params['status'] = $status;
        }
        if ($bloodGroup !== '') {
            $base .= ' AND r.blood_group = :blood_group ';
            $params['blood_group'] = $bloodGroup;
        }

        $count = $this->db->prepare('SELECT COUNT(*)' . $base);
        $count->execute($params);
        $total = (int) $count->fetchColumn();

        $sql = 'SELECT r.*, u.name AS requested_by_name ' . $base . ' ORDER BY r.id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) max(1, ceil($total / $perPage)),
        ];
    }
}
