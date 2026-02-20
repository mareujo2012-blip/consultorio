<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\AuditLog;
use App\Models\ClinicSettings;

class PrescriptionController extends BaseController
{
    public function create(string $appointmentId): void
    {
        $this->requireAuth();

        $appointmentModel = new Appointment();
        $appointment = $appointmentModel->withPatient((int) $appointmentId);

        if (!$appointment) {
            $this->flashError('Atendimento não encontrado.');
            $this->redirect('appointments');
        }

        $existingPrescription = (new Prescription())->getByAppointment((int) $appointmentId);
        $csrfToken = $this->csrfToken();

        $this->view('prescriptions.create', compact('appointment', 'existingPrescription', 'csrfToken'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if (!$appointmentId || !$patientId || empty($content)) {
            $this->flashError('Dados inválidos.');
            $this->back();
        }

        $prescriptionModel = new Prescription();

        // Check if prescription already exists for this appointment
        $existing = $prescriptionModel->getByAppointment($appointmentId);
        if ($existing) {
            // Update existing
            $prescriptionModel->update($existing['id'], [
                'content' => $content,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $id = $existing['id'];
        } else {
            $id = $prescriptionModel->insert([
                'appointment_id' => $appointmentId,
                'patient_id' => $patientId,
                'user_id' => $_SESSION['user_id'],
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        (new AuditLog())->log('prescription.create', "Receita #{$id} para atendimento #{$appointmentId}");
        $this->flashSuccess('Receita salva com sucesso!');
        $this->redirect("prescriptions/{$id}/pdf");
    }

    public function pdf(string $id): void
    {
        $this->requireAuth();

        $prescriptionModel = new Prescription();
        $prescription = $prescriptionModel->withDetails((int) $id);

        if (!$prescription) {
            die('Receita não encontrada.');
        }

        $clinic = (new ClinicSettings())->getSettings();
        (new AuditLog())->log('prescription.pdf', "PDF da receita #{$id} gerado");

        require_once __DIR__ . '/../../vendor/autoload.php';

        $html = $this->buildPrescriptionPdf($prescription, $clinic);
        $dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("receita_{$prescription['patient_name']}.pdf", ['Attachment' => false]);
    }

    public function history(string $patientId): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $patient = $patientModel->find((int) $patientId);

        if (!$patient) {
            $this->flashError('Paciente não encontrado.');
            $this->redirect('patients');
        }

        $prescriptions = (new Prescription())->getByPatient((int) $patientId);
        $csrfToken = $this->csrfToken();

        $this->view('prescriptions.history', compact('patient', 'prescriptions', 'csrfToken'));
    }

    private function buildPrescriptionPdf(array $p, array $clinic): string
    {
        $clinicName = htmlspecialchars($clinic['name'] ?? 'Clínica Médica');
        $clinicAddr = htmlspecialchars($clinic['address'] ?? '');
        $clinicPhone = htmlspecialchars($clinic['phone'] ?? '');
        $doctorName = htmlspecialchars($p['doctor_name'] ?? $_SESSION['user_name'] ?? '');
        $patientName = htmlspecialchars($p['patient_name']);
        $cpf = $p['patient_cpf'] ?? '';
        $content = nl2br(htmlspecialchars($p['content']));
        $date = date('d/m/Y', strtotime($p['appointment_date']));
        $city = htmlspecialchars($clinic['city'] ?? '');

        // Logo
        $logoHtml = '';
        if (!empty($clinic['logo'])) {
            $logoPath = __DIR__ . '/../../public/' . $clinic['logo'];
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $mimeType = mime_content_type($logoPath);
                $logoHtml = "<img src='data:{$mimeType};base64,{$logoData}' style='max-height:70px;' alt='Logo'>";
            }
        }

        return <<<HTML
        <!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #222; margin: 0; padding: 30px; }
            .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1d4ed8; padding-bottom: 15px; margin-bottom: 20px; }
            .clinic-info h2 { margin: 0; font-size: 16px; color: #1d4ed8; }
            .clinic-info p { margin: 2px 0; font-size: 11px; color: #555; }
            .patient-box { background: #f0f4ff; border-left: 4px solid #1d4ed8; padding: 10px 15px; margin-bottom: 25px; }
            .rx { font-size: 40px; color: #1d4ed8; font-family: Georgia, serif; margin-bottom: 10px; }
            .content { min-height: 300px; font-size: 13px; line-height: 1.8; }
            .signature-box { margin-top: 60px; border-top: 1px solid #555; width: 280px; text-align: center; padding-top: 8px; }
            .footer { position: fixed; bottom: 20px; left: 30px; right: 30px; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
            .date-city { text-align: right; margin-bottom: 20px; font-size: 12px; }
        </style></head><body>
        <div class='header'>
            <div>{$logoHtml}</div>
            <div class='clinic-info'>
                <h2>{$clinicName}</h2>
                <p>{$clinicAddr}</p>
                <p>Tel: {$clinicPhone}</p>
            </div>
        </div>
        <div class='patient-box'>
            <strong>Paciente:</strong> {$patientName} &nbsp;|&nbsp; <strong>CPF:</strong> {$cpf}
        </div>
        <div class='date-city'>{$city}, {$date}</div>
        <div class='rx'>Rx</div>
        <div class='content'>{$content}</div>
        <div class='signature-box'>
            <p>{$doctorName}</p>
        </div>
        <div class='footer'>Receita emitida pelo sistema ControleConsultório — {$clinicName}</div>
        </body></html>
        HTML;
    }
}
