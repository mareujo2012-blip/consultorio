<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class AuditLog extends BaseModel
{
    protected string $table = 'audit_logs';

    public function log(string $action, string $description, ?int $userId = null): void
    {
        $userId = $userId ?? ($_SESSION['user_id'] ?? null);
        $this->insert([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
