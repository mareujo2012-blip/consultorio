<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Prescription extends BaseModel
{
    protected string $table = 'prescriptions';

    public function getByPatient(int $patientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT pr.*, a.appointment_date, p.name AS patient_name
             FROM prescriptions pr
             JOIN appointments a ON a.id = pr.appointment_id
             JOIN patients p ON p.id = pr.patient_id
             WHERE pr.patient_id = ?
             ORDER BY pr.created_at DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function getByAppointment(int $appointmentId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT pr.*, p.name AS patient_name, p.cpf AS patient_cpf, p.birth_date
             FROM prescriptions pr
             JOIN patients p ON p.id = pr.patient_id
             WHERE pr.appointment_id = ? LIMIT 1"
        );
        $stmt->execute([$appointmentId]);
        return $stmt->fetch();
    }

    public function withDetails(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT pr.*, p.name AS patient_name, p.cpf AS patient_cpf, p.birth_date,
                    a.appointment_date, u.name AS doctor_name
             FROM prescriptions pr
             JOIN patients p ON p.id = pr.patient_id
             JOIN appointments a ON a.id = pr.appointment_id
             JOIN users u ON u.id = pr.user_id
             WHERE pr.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
