<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/FPDI_Protection.php';

use setasign\Fpdi\Fpdi;

// Params
$appId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($appId <= 0) die("Invalid request");

// --- Fetch data ---
$sql = "SELECT el.*, i.Fname, i.Lname, i.Mname, i.Extension, i.EmpNo, i.Dept
        FROM eta_locator el
        INNER JOIN i ON el.EmpNo = i.EmpNo
        WHERE el.id = :id
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $appId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) die("Record not found");

$ext = (!empty($row['Extension']) && strtoupper(trim($row['Extension'])) !== 'N/A') ? ' ' . trim($row['Extension']) : '';
$mname = !empty($row['Mname']) ? ' ' . substr(trim($row['Mname']), 0, 1) . '.' : '';
$fullName = trim($row['Lname'] . ', ' . $row['Fname'] . $mname . $ext);

$travel_date = $row['travel_date'] ?? '';
$departure   = !empty($row['intended_departure']) ? date("h:i A", strtotime($row['intended_departure'])) : '';
$arrival     = !empty($row['intended_arrival']) ? date("h:i A", strtotime($row['intended_arrival'])) : '';
$purpose     = $row['business_type'] ?? '';
$reason      = $row['travel_detail'] ?? '';

//original template path
// $templatePath = realpath(__DIR__ . '/../templates/LocatorForm/' . $row['Dept'] . '.pdf');
// if (!$templatePath) die("Template not found");

$templatePath = realpath(__DIR__ . '/../templates/LocatorForm/' . $row['Dept'] . '.pdf');

// If not found, use the default template
if ($templatePath === false || !file_exists($templatePath)) {
    $templatePath = realpath(__DIR__ . '/../templates/LocatorForm/LOCATOR FOR ALL.pdf');
}

if ($templatePath === false) {
    die("No template file found.");
}



// --- Generate PDF ---
$pdf = new FPDI_Protection();
$pdf->setSourceFile($templatePath);
$template = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($template, 0, 0, 210);
$pdf->SetFont('Arial', '', 9);

// Fill fields
$pdf->SetXY(10, 37); $pdf->Write(5, $fullName);
$pdf->SetXY(75, 36); $pdf->Write(5, !empty($travel_date) ? date("M d, Y", strtotime($travel_date)) : '');
$pdf->SetXY(75, 63); $pdf->Write(5, $departure);
$pdf->SetXY(75, 68); $pdf->Write(5, $arrival);

$pdf->SetFont('Arial', 'B', 14);
if (strtolower($purpose)==='official') { $pdf->SetXY(36,75); $pdf->Write(5,'X'); }
elseif (strtolower($purpose)==='personal') { $pdf->SetXY(68,75); $pdf->Write(5,'X'); }

$pdf->SetFont('Arial','',9);
$pdf->SetXY(23,82); $pdf->MultiCell(70,5,$reason);

// fill fields 2nd copy
$pdf->SetXY(110, 37); $pdf->Write(5, $fullName);
$pdf->SetXY(175, 36); $pdf->Write(5, !empty($travel_date) ? date("M d, Y", strtotime($travel_date)) : '');
$pdf->SetXY(175, 63); $pdf->Write(5, $departure);
$pdf->SetXY(175, 68); $pdf->Write(5, $arrival);

$pdf->SetFont('Arial', 'B', 14);
if (strtolower($purpose)==='official') { $pdf->SetXY(136,75); $pdf->Write(5,'X'); }
elseif (strtolower($purpose)==='personal') { $pdf->SetXY(168,75); $pdf->Write(5,'X'); }

$pdf->SetFont('Arial','',9);
$pdf->SetXY(123,82); $pdf->MultiCell(70,5,$reason);

// --- Serve PDF for PDF.js ---
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/pdf');
header('Access-Control-Allow-Origin: *'); // allow PDF.js XHR
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', 'ETA_Form.pdf');
exit;
