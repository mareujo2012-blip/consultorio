<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class MedicalRecord extends BaseModel
{
    protected string $table = 'medical_record_entries';

    /**
     * Create an immutable entry. Once inserted, it cannot be edited.
     */
    public function createEntry(array $data): int|false
    {
        $content = $data['content'] ?? '';
        $data['content_hash'] = hash('sha256', $content . ($data['appointment_id'] ?? '') . ($data['patient_id'] ?? '') . date('Y-m-d H:i:s'));
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    public function getByPatient(int $patientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT mre.*, a.appointment_date, a.value AS appointment_value,
                    a.payment_method, u.name AS created_by_name
             FROM medical_record_entries mre
             JOIN appointments a ON a.id = mre.appointment_id
             JOIN users u ON u.id = mre.user_id
             WHERE mre.patient_id = ?
             ORDER BY mre.created_at DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getByAppointment(int $appointmentId): array
    {
        $stmt = $this->db->prepare(
            "SELECT mre.*, u.name AS created_by_name
             FROM medical_record_entries mre
             JOIN users u ON u.id = mre.user_id
             WHERE mre.appointment_id = ?
             ORDER BY mre.created_at ASC"
        );
        $stmt->execute([$appointmentId]);
        return $stmt->fetchAll();
    }

    /**
     * Entries are immutable – no update allowed.
     * Corrections must be done via new addendum entries.
     */
    public function update(int $id, array $data): bool
    {
        throw new \LogicException('Medical record entries are immutable and cannot be updated.');
    }

    public function delete(int $id): bool
    {
        throw new \LogicException('Medical record entries cannot be deleted.');
    }
}
