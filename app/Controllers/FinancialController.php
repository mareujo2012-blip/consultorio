<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Appointment;
use App\Models\AuditLog;

class FinancialController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-t');

        $appointmentModel = new Appointment();
        $entries = $appointmentModel->financialReport($from, $to);
        $total = array_sum(array_column($entries, 'value'));
        $count = count($entries);
        $avgTicket = $count > 0 ? $total / $count : 0;

        $chartData = $appointmentModel->chartData($from, $to, 'day');
        $chartLabels = array_column($chartData, 'period');
        $chartRevenue = array_column($chartData, 'total_revenue');

        $csrfToken = $this->csrfToken();

        $this->view('financial.index', compact(
            'entries',
            'total',
            'count',
            'avgTicket',
            'from',
            'to',
            'chartLabels',
            'chartRevenue',
            'csrfToken'
        ));
    }

    public function exportPdf(): void
    {
        $this->requireAuth();

        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-t');

        $appointmentModel = new Appointment();
        $entries = $appointmentModel->financialReport($from, $to);
        $total = array_sum(array_column($entries, 'value'));

        $clinic = (new \App\Models\ClinicSettings())->getSettings();
        $clinicName = htmlspecialchars($clinic['name'] ?? 'Clínica');
        (new AuditLog())->log('financial.export', "Exportação PDF Financeiro gerada ({$from} a {$to})");

        require_once __DIR__ . '/../../vendor/autoload.php';

        $html = $this->buildPdfHtml($entries, $total, $from, $to, $clinicName);

        $dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("relatorio_financeiro_{$from}_{$to}.pdf", ['Attachment' => false]);
    }

    private function buildPdfHtml(array $entries, float $total, string $from, string $to, string $clinicName): string
    {
        $generated = date('d/m/Y H:i');
        $fromDate = date('d/m/Y', strtotime($from));
        $toDate = date('d/m/Y', strtotime($to));
        $totalFmt = number_format($total, 2, ',', '.');
        $author = htmlspecialchars($_SESSION['user_name'] ?? '');

        $rows = '';
        foreach ($entries as $e) {
            $date = date('d/m/Y H:i', strtotime($e['appointment_date']));
            $pat = htmlspecialchars($e['patient_name']);
            $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $e['patient_cpf'] ?? '');
            $val = number_format((float) $e['value'], 2, ',', '.');
            $rows .= "<tr>
                <td style='padding:8px; border-bottom:1px solid #eee;'>{$date}</td>
                <td style='padding:8px; border-bottom:1px solid #eee;'>{$pat}</td>
                <td style='padding:8px; border-bottom:1px solid #eee;'>{$cpf}</td>
                <td style='padding:8px; border-bottom:1px solid #eee; text-align:right;'>R$ {$val}</td>
            </tr>";
        }

        return <<<HTML
        <!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; }
            .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
            h1 { color: #2563eb; font-size: 18px; margin: 0; }
            .info { background: #f1f5f9; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 11px; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th { text-align: left; background: #f8fafc; padding: 10px; font-size: 11px; color: #64748b; border-bottom: 2px solid #e2e8f0; }
            .footer { border-top: 1px solid #ccc; margin-top: 30px; padding-top: 10px; font-size: 10px; color: #888; text-align: center; }
            .total-row { font-weight: bold; background: #eff6ff; }
        </style></head><body>
        <div class='header'>
            <h1>{$clinicName} — Extrato Financeiro</h1>
            <p style='margin: 5px 0 0 0; color: #64748b; font-size: 11px;'>Gerado por: {$author} | Data: {$generated}</p>
        </div>
        <div class='info'>
            <strong>Período Analisado:</strong> {$fromDate} até {$toDate} &nbsp;|&nbsp;
            <strong>Total de Registros:</strong> <span style='color:#059669;'>R$ {$totalFmt}</span>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Paciente</th>
                    <th>CPF</th>
                    <th style='text-align:right;'>Valor Líquido</th>
                </tr>
            </thead>
            <tbody>
                {$rows}
                <tr class='total-row'>
                    <td colspan='3' style='padding:10px; text-align:right;'>Total Arrecadado no Período</td>
                    <td style='padding:10px; text-align:right; color:#059669; font-size: 14px;'>R$ {$totalFmt}</td>
                </tr>
            </tbody>
        </table>
        
        <div class='footer'>
            ControleConsultório &copy; Intelligence Financeira — Documento gerado eletronicamente.
        </div>
        </body></html>
        HTML;
    }
}
