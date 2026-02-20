<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\AuditLog;

class AppointmentController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $appointmentModel = new Appointment();
        $result = $appointmentModel->paginate($page, $perPage, '', [], 'appointment_date DESC');

        // Enrich with patient names
        $db = \App\Config\Database::getInstance();
        $appointments = array_map(function ($a) use ($db) {
            $stmt = $db->prepare("SELECT name FROM patients WHERE id = ?");
            $stmt->execute([$a['patient_id']]);
            $a['patient_name'] = $stmt->fetchColumn() ?: '—';
            return $a;
        }, $result['data']);

        $pagination = $result;
        $csrfToken = $this->csrfToken();

        $this->view('appointments.index', compact('appointments', 'pagination', 'csrfToken'));
    }

    public function create(): void
    {
        $this->requireAuth();

        $patientId = (int) ($_GET['patient_id'] ?? 0);
        $patientModel = new Patient();
        $patient = $patientId ? $patientModel->find($patientId) : null;
        $csrfToken = $this->csrfToken();

        $this->view('appointments.create', compact('patient', 'patientId', 'csrfToken'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $patientId = (int) ($_POST['patient_id'] ?? 0);
        if (!$patientId) {
            $this->flashError('Paciente inválido.');
            $this->redirect('appointments/create');
        }

        $value = (float) str_replace(['.', ','], ['', '.'], $_POST['value'] ?? '0');

        $data = [
            'patient_id' => $patientId,
            'user_id' => $_SESSION['user_id'],
            'appointment_date' => ($_POST['appointment_date'] ?? date('Y-m-d')) . ' ' . ($_POST['appointment_time'] ?? date('H:i:s')),
            'value' => $value,
            'payment_method' => $this->sanitize($_POST['payment_method'] ?? ''),
            'admin_notes' => $this->sanitize($_POST['admin_notes'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $appointmentModel = new Appointment();
        $id = $appointmentModel->insert($data);

        if ($id) {
            (new AuditLog())->log('appointment.create', "Atendimento #{$id} criado para paciente #{$patientId}");
            $this->flashSuccess('Atendimento registrado com sucesso!');
            $this->redirect("appointments/{$id}");
        }

        $this->flashError('Erro ao registrar atendimento.');
        $this->redirect('appointments/create?patient_id=' . $patientId);
    }

    public function show(string $id): void
    {
        $this->requireAuth();

        $appointmentModel = new Appointment();
        $appointment = $appointmentModel->withPatient((int) $id);

        if (!$appointment) {
            $this->flashError('Atendimento não encontrado.');
            $this->redirect('appointments');
        }

        $medicalRecordModel = new MedicalRecord();
        $records = $medicalRecordModel->getByAppointment((int) $id);

        $csrfToken = $this->csrfToken();
        $this->view('appointments.show', compact('appointment', 'records', 'csrfToken'));
    }
}
