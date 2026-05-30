<?php
declare(strict_types=1);

class Donation extends Model
{
    protected string $table = 'donations';

    public function allWithRelations(): array
    {
        $stmt = $this->db->query('
            SELECT d.*, dn.name AS donor_name, u.name AS staff_name
            FROM donations d
            JOIN donors dn ON dn.id = d.donor_id
            JOIN users u ON u.id = d.collection_staff_id
            ORDER BY d.id DESC
        ');
        return $stmt->fetchAll();
    }

    public function paginatedWithRelations(int $page = 1, int $perPage = 10): array
    {
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));
        $offset = ($page - 1) * $perPage;

        $total = (int) $this->db->query('SELECT COUNT(*) FROM donations')->fetchColumn();
        $stmt = $this->db->prepare('
            SELECT d.*, dn.name AS donor_name, u.name AS staff_name
            FROM donations d
            JOIN donors dn ON dn.id = d.donor_id
            JOIN users u ON u.id = d.collection_staff_id
            ORDER BY d.id DESC
            LIMIT :limit OFFSET :offset
        ');
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
