<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AuditLog;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $appointmentModel = new Appointment();

        $totalPatients = $patientModel->totalActive();
        $appointmentsMonth = $appointmentModel->countThisMonth();
        $revenueMonth = $appointmentModel->revenueThisMonth();
        $avgTicket = $appointmentsMonth > 0 ? $revenueMonth / $appointmentsMonth : 0;
        $recentAppointments = $appointmentModel->listRecent(8);

        // Chart data last 30 days
        $from = date('Y-m-d', strtotime('-29 days'));
        $to = date('Y-m-d');
        $chartData = $appointmentModel->chartData($from, $to, 'day');

        $chartLabels = array_column($chartData, 'period');
        $chartAppoints = array_column($chartData, 'total_appointments');
        $chartRevenue = array_column($chartData, 'total_revenue');

        $csrfToken = $this->csrfToken();

        $this->view('dashboard.index', compact(
            'totalPatients',
            'appointmentsMonth',
            'revenueMonth',
            'avgTicket',
            'recentAppointments',
            'chartLabels',
            'chartAppoints',
            'chartRevenue',
            'csrfToken'
        ));
    }
}
