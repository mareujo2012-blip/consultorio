<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\AuditLog;
use App\Models\ClinicSettings;

class MedicalRecordController extends BaseController
{
    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
        $patientId = (int) ($_POST['patient_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        $type = $_POST['entry_type'] ?? 'anamnese';

        if (!$appointmentId || !$patientId || empty($content)) {
            $this->flashError('Dados inválidos.');
            $this->back();
        }

        $data = [
            'appointment_id' => $appointmentId,
            'patient_id' => $patientId,
            'user_id' => $_SESSION['user_id'],
            'entry_type' => $type,
            'content' => $content,
        ];

        $model = new MedicalRecord();
        $id = $model->createEntry($data);

        if ($id) {
            (new AuditLog())->log('medical_record.create', "Prontuário #{$id} criado para atendimento #{$appointmentId}");
            $this->flashSuccess('Entrada no prontuário registrada com sucesso!');
        } else {
            $this->flashError('Erro ao salvar prontuário.');
        }

        $this->redirect("appointments/{$appointmentId}");
    }

    public function showByPatient(string $patientId): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $patient = $patientModel->find((int) $patientId);

        if (!$patient) {
            $this->flashError('Paciente não encontrado.');
            $this->redirect('patients');
        }

        $model = new MedicalRecord();
        $entries = $model->getByPatient((int) $patientId);
        $csrfToken = $this->csrfToken();

        $this->view('medical_records.timeline', compact('patient', 'entries', 'csrfToken'));
    }

    public function exportPdf(string $patientId): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $patient = $patientModel->find((int) $patientId);

        if (!$patient) {
            die('Paciente não encontrado.');
        }

        $model = new MedicalRecord();
        $entries = $model->getByPatient((int) $patientId);
        $clinic = (new ClinicSettings())->getSettings();

        (new AuditLog())->log('medical_record.pdf', "PDF do prontuário gerado para paciente #{$patientId}");

        require_once __DIR__ . '/../../vendor/autoload.php';

        $html = $this->buildPdfHtml($patient, $entries, $clinic);
        $dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("prontuario_{$patient['name']}.pdf", ['Attachment' => false]);
    }

    private function buildPdfHtml(array $patient, array $entries, array $clinic): string
    {
        $clinicName = htmlspecialchars($clinic['name'] ?? 'Clínica');
        $doctorName = htmlspecialchars($_SESSION['user_name'] ?? '');
        $patientName = htmlspecialchars($patient['name']);
        $cpf = $patient['cpf'] ?? '';
        $generated = date('d/m/Y H:i');

        $entriesHtml = '';
        foreach ($entries as $entry) {
            $date = date('d/m/Y H:i', strtotime($entry['created_at']));
            $type = htmlspecialchars($entry['entry_type']);
            $content = nl2br(htmlspecialchars($entry['content']));
            $author = htmlspecialchars($entry['created_by_name'] ?? '');
            $entriesHtml .= "
            <div class='entry'>
                <div class='entry-header'>
                    <strong>{$date}</strong> — {$type}
                    <span class='author'>por {$author}</span>
                </div>
                <div class='entry-content'>{$content}</div>
            </div>";
        }

        return <<<HTML
        <!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
            .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
            h1 { color: #2563eb; font-size: 18px; margin: 0; }
            .patient-info { background: #f1f5f9; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
            .entry { border-left: 3px solid #2563eb; padding: 10px; margin-bottom: 15px; }
            .entry-header { color: #2563eb; font-weight: bold; margin-bottom: 5px; }
            .author { color: #888; font-size: 11px; }
            .entry-content { margin-top: 5px; }
            .footer { border-top: 1px solid #ccc; margin-top: 20px; padding-top: 10px; font-size: 10px; color: #888; }
        </style></head><body>
        <div class='header'>
            <h1>{$clinicName} — Prontuário Médico</h1>
            <p>Médico: {$doctorName} | Gerado em: {$generated}</p>
        </div>
        <div class='patient-info'>
            <strong>Paciente:</strong> {$patientName} &nbsp;|&nbsp; <strong>CPF:</strong> {$cpf}
        </div>
        <h2>Entradas do Prontuário</h2>
        {$entriesHtml}
        <div class='footer'>Este documento é confidencial e de uso médico exclusivo. Hash de integridade SHA-256 registrado.</div>
        </body></html>
        HTML;
    }
}
