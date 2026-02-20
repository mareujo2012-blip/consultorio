<?php

namespace App\Models;

use App\Core\BaseModel;

class ClinicSettings extends BaseModel
{
    protected string $table = 'clinic_settings';

    public function getSettings(): array
    {
        $stmt = $this->db->query("SELECT * FROM clinic_settings LIMIT 1");
        return $stmt->fetch() ?: [];
    }

    public function saveSettings(array $data): bool
    {
        $existing = $this->getSettings();
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($existing) {
            return $this->update((int) $existing['id'], $data);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return (bool) $this->insert($data);
    }
}
