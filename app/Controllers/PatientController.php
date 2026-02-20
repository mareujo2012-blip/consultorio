<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Patient;
use App\Models\AuditLog;

class PatientController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $search = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 15;

        $patientModel = new Patient();

        if ($search !== '') {
            $result = $patientModel->search($search, $page, $perPage);
        } else {
            $result = $patientModel->listActive($page, $perPage);
        }

        $patients = $result['data'];
        $pagination = $result;
        $csrfToken = $this->csrfToken();

        $this->view('patients.index', compact('patients', 'pagination', 'search', 'csrfToken'));
    }

    public function create(): void
    {
        $this->requireAuth();
        $csrfToken = $this->csrfToken();
        $this->view('patients.create', compact('csrfToken'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'cpf' => preg_replace('/\D/', '', $_POST['cpf'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'birth_date' => $_POST['birth_date'] ?? null,
            'sex' => $_POST['sex'] ?? '',
            'address' => $this->sanitize($_POST['address'] ?? ''),
            'city' => $this->sanitize($_POST['city'] ?? ''),
            'state' => $this->sanitize($_POST['state'] ?? ''),
            'zip' => preg_replace('/\D/', '', $_POST['zip'] ?? ''),
            'notes' => $this->sanitize($_POST['notes'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $_SESSION['user_id'],
        ];

        // Validate required
        if (empty($data['name']) || empty($data['cpf'])) {
            $this->flashError('Nome e CPF são obrigatórios.');
            $this->redirect('patients/create');
        }

        // Handle photo upload
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photoPath = $this->handlePhotoUpload($_FILES['photo']);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        $patientModel = new Patient();
        $id = $patientModel->insert($data);

        if ($id) {
            (new AuditLog())->log('patient.create', "Paciente #{$id} criado: {$data['name']}");
            $this->flashSuccess('Paciente cadastrado com sucesso!');
            $this->redirect("patients/{$id}");
        }

        $this->flashError('Erro ao cadastrar paciente.');
        $this->redirect('patients/create');
    }

    public function show(string $id): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $patient = $patientModel->find((int) $id);

        if (!$patient) {
            $this->flashError('Paciente não encontrado.');
            $this->redirect('patients');
        }

        $csrfToken = $this->csrfToken();
        $this->view('patients.show', compact('patient', 'csrfToken'));
    }

    public function edit(string $id): void
    {
        $this->requireAuth();

        $patientModel = new Patient();
        $patient = $patientModel->find((int) $id);

        if (!$patient) {
            $this->flashError('Paciente não encontrado.');
            $this->redirect('patients');
        }

        $csrfToken = $this->csrfToken();
        $this->view('patients.edit', compact('patient', 'csrfToken'));
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $patientId = (int) $id;
        $patientModel = new Patient();
        $patient = $patientModel->find($patientId);

        if (!$patient) {
            $this->flashError('Paciente não encontrado.');
            $this->redirect('patients');
        }

        $data = [
            'name' => $this->sanitize($_POST['name'] ?? ''),
            'cpf' => preg_replace('/\D/', '', $_POST['cpf'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'phone' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
            'birth_date' => $_POST['birth_date'] ?? null,
            'sex' => $_POST['sex'] ?? '',
            'address' => $this->sanitize($_POST['address'] ?? ''),
            'city' => $this->sanitize($_POST['city'] ?? ''),
            'state' => $this->sanitize($_POST['state'] ?? ''),
            'zip' => preg_replace('/\D/', '', $_POST['zip'] ?? ''),
            'notes' => $this->sanitize($_POST['notes'] ?? ''),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($_FILES['photo']['tmp_name'])) {
            $photoPath = $this->handlePhotoUpload($_FILES['photo']);
            if ($photoPath) {
                $data['photo'] = $photoPath;
            }
        }

        $patientModel->update($patientId, $data);
        (new AuditLog())->log('patient.update', "Paciente #{$patientId} atualizado");
        $this->flashSuccess('Paciente atualizado com sucesso!');
        $this->redirect("patients/{$patientId}");
    }

    private function handlePhotoUpload(array $file): string|false
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        if (!in_array($file['type'], $allowedMimes)) {
            return false;
        }
        if ($file['size'] > $maxSize) {
            return false;
        }

        $dir = __DIR__ . '/../../public/uploads/photos/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        $destPath = $dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return 'uploads/photos/' . $filename;
        }

        return false;
    }
}
