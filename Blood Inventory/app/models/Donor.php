<?php
declare(strict_types=1);

class Donor extends Model
{
    protected string $table = 'donors';

    public function search(string $query = '', string $bloodGroup = ''): array
    {
        $sql = 'SELECT * FROM donors WHERE 1=1';
        $params = [];
        if ($query !== '') {
            $sql .= ' AND (name LIKE :query OR contact LIKE :query)';
            $params['query'] = '%' . $query . '%';
        }
        if ($bloodGroup !== '') {
            $sql .= ' AND blood_group = :blood_group';
            $params['blood_group'] = $bloodGroup;
        }
        $sql .= ' ORDER BY id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function searchPaginated(string $query = '', string $bloodGroup = '', int $page = 1, int $perPage = 10): array
    {
        $from = 'FROM donors WHERE 1=1';
        $params = [];
        if ($query !== '') {
            $from .= ' AND (name LIKE :query OR contact LIKE :query)';
            $params['query'] = '%' . $query . '%';
        }
        if ($bloodGroup !== '') {
            $from .= ' AND blood_group = :blood_group';
            $params['blood_group'] = $bloodGroup;
        }
        $from .= ' ORDER BY id DESC';
        return $this->paginateQuery($from, $params, $page, $perPage);
    }
}
