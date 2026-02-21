<?php
require_once __DIR__ . '/../bootstrap/app.php';

$db = \App\Config\Database::getInstance();
$stmt = $db->query("SELECT * FROM appointments WHERE id = 2");
$row = $stmt->fetch();
print_r($row);

$stmt = $db->query("SELECT * FROM patients WHERE id = " . ($row ? $row['patient_id'] : '0'));
$p = $stmt->fetch();
print_r($p);

@unlink(__FILE__);
