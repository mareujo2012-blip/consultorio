<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;

class Appointment extends BaseModel
{
    protected string $table = 'appointments';

    public function listByPatient(int $patientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.name AS doctor_name
             FROM appointments a
             JOIN users u ON u.id = a.user_id
             WHERE a.patient_id = ?
             ORDER BY a.appointment_date DESC"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public function withPatient(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.name AS patient_name, p.cpf AS patient_cpf, p.photo AS patient_photo
             FROM appointments a
             JOIN patients p ON p.id = a.patient_id
             WHERE a.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listRecent(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.name AS patient_name
             FROM appointments a
             JOIN patients p ON p.id = a.patient_id
             ORDER BY a.appointment_date DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function countThisMonth(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM appointments
             WHERE YEAR(appointment_date) = YEAR(CURDATE())
               AND MONTH(appointment_date) = MONTH(CURDATE())"
        );
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function revenueThisMonth(): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(value), 0)
             FROM appointments
             WHERE YEAR(appointment_date) = YEAR(CURDATE())
               AND MONTH(appointment_date) = MONTH(CURDATE())"
        );
        $stmt->execute();
        return (float) $stmt->fetchColumn();
    }

    public function chartData(string $from, string $to, string $groupBy = 'day'): array
    {
        $format = match ($groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(appointment_date, '{$format}') AS period,
                    COUNT(*) AS total_appointments,
                    COALESCE(SUM(value), 0) AS total_revenue
             FROM appointments
             WHERE DATE(appointment_date) BETWEEN ? AND ?
             GROUP BY period
             ORDER BY period ASC"
        );
        $stmt->execute([$from, $to]);
        return $stmt->fetchAll();
    }

    public function financialReport(string $from, string $to): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.name AS patient_name, p.cpf AS patient_cpf
             FROM appointments a
             JOIN patients p ON p.id = a.patient_id
             WHERE DATE(a.appointment_date) BETWEEN ? AND ?
             ORDER BY a.appointment_date DESC"
        );
        $stmt->execute([$from, $to]);
        return $stmt->fetchAll();
    }
}
