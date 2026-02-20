<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PatientController;
use App\Controllers\AppointmentController;
use App\Controllers\MedicalRecordController;
use App\Controllers\PrescriptionController;
use App\Controllers\FinancialController;
use App\Controllers\SettingsController;

$router = new Router();

// ── Auth ───────────────────────────────────────────────────────────────────
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// ── Dashboard ──────────────────────────────────────────────────────────────
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// ── Patients ───────────────────────────────────────────────────────────────
$router->get('/patients', [PatientController::class, 'index']);
$router->get('/patients/create', [PatientController::class, 'create']);
$router->post('/patients', [PatientController::class, 'store']);
$router->get('/patients/{id}', [PatientController::class, 'show']);
$router->get('/patients/{id}/edit', [PatientController::class, 'edit']);
$router->post('/patients/{id}', [PatientController::class, 'update']);

// Patient medical records
$router->get('/patients/{patientId}/records', [MedicalRecordController::class, 'showByPatient']);
$router->get('/patients/{patientId}/records/pdf', [MedicalRecordController::class, 'exportPdf']);

// Patient prescriptions history
$router->get('/patients/{patientId}/prescriptions', [PrescriptionController::class, 'history']);

// ── Appointments ───────────────────────────────────────────────────────────
$router->get('/appointments', [AppointmentController::class, 'index']);
$router->get('/appointments/create', [AppointmentController::class, 'create']);
$router->post('/appointments', [AppointmentController::class, 'store']);
$router->get('/appointments/{id}', [AppointmentController::class, 'show']);

// ── Medical Records (POST only – immutable) ──────────────────────────────
$router->post('/medical-records', [MedicalRecordController::class, 'store']);

// ── Prescriptions ─────────────────────────────────────────────────────────
$router->get('/prescriptions/appointment/{appointmentId}', [PrescriptionController::class, 'create']);
$router->post('/prescriptions', [PrescriptionController::class, 'store']);
$router->get('/prescriptions/{id}/pdf', [PrescriptionController::class, 'pdf']);

// ── Financial ─────────────────────────────────────────────────────────────
$router->get('/financial', [FinancialController::class, 'index']);

// ── Settings ──────────────────────────────────────────────────────────────
$router->get('/settings', [SettingsController::class, 'index']);
$router->post('/settings/user', [SettingsController::class, 'updateUser']);
$router->post('/settings/password', [SettingsController::class, 'updatePassword']);
$router->post('/settings/clinic', [SettingsController::class, 'updateClinic']);

// ── API (internal) ────────────────────────────────────────────────────────
$router->get('/api/patients/search', function () {
    require_once __DIR__ . '/../app/Config/Database.php';
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo '[]';
        exit;
    }
    $q = trim($_GET['q'] ?? '');
    if (strlen($q) < 2) {
        echo '[]';
        exit;
    }
    $db = \App\Config\Database::getInstance();
    $like = "%{$q}%";
    $stmt = $db->prepare("SELECT id, name, cpf FROM patients WHERE deleted_at IS NULL AND (name LIKE ? OR cpf LIKE ?) ORDER BY name LIMIT 10");
    $stmt->execute([$like, $like]);
    header('Content-Type: application/json');
    echo json_encode($stmt->fetchAll());
    exit;
});

return $router;
