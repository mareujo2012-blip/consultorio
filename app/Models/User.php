<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([strtolower(trim($email))]);
        return $stmt->fetch();
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID);
    }

    public function createUser(array $data): int|false
    {
        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        $data['email'] = strtolower(trim($data['email']));
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_ARGON2ID),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
