<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Patient extends BaseModel
{
    protected string $table = 'patients';

    public function search(string $term, int $page = 1, int $perPage = 15): array
    {
        $like = "%{$term}%";
        $where = "deleted_at IS NULL AND (name LIKE ? OR cpf LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params = [$like, $like, $like, $like];
        return $this->paginate($page, $perPage, $where, $params, 'name ASC');
    }

    public function listActive(int $page = 1, int $perPage = 15): array
    {
        return $this->paginate($page, $perPage, 'deleted_at IS NULL', [], 'name ASC');
    }

    public function findByCpf(string $cpf): array|false
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE cpf = ? AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([$cpf]);
        return $stmt->fetch();
    }

    public function softDelete(int $id): bool
    {
        return $this->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function totalActive(): int
    {
        return $this->count('deleted_at IS NULL');
    }
}
