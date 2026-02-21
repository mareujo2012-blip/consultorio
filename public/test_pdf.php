<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => true]);
    $dompdf->loadHtml('<h1>Teste PDF</h1>');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    echo "PDF generated successfully!";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
}
@unlink(__FILE__);
