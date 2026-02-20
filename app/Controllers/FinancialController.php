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
        $to = $_GET['to'] ?? date('Y-m-d');

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
}
