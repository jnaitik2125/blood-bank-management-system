<?php
declare(strict_types=1);

class User extends Model
{
    protected string $table = 'users';

    public function paginated(int $page = 1, int $perPage = 10): array
    {
        return $this->paginateQuery('FROM users ORDER BY id DESC', [], $page, $perPage);
    }
}
