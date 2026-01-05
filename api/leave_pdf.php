<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/FPDI_Protection.php';
session_start();
use setasign\Fpdi\Fpdi;


$appId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($appId <= 0) die("Invalid request");

// --- Fetch data ---
$sql = "SELECT * FROM filedleave WHERE LeaveID = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $appId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) die("Record not found");

$fullName = $row['EmployeeName'] ?? '';
$dept     = $row['Office'] ?? '';
$position = $row['Position'] ?? "Please update position in PDS";
$numDays = $row['TotalDays'] ?? 0;

// --- Format dates ---
$datefrom = !empty($row['DateFrom']) 
    ? date('F j, Y', strtotime($row['DateFrom'])) 
    : '';

$dateto = !empty($row['DateTo']) 
    ? date('F j, Y', strtotime($row['DateTo'])) 
    : '';

$datefiled = !empty($row['DateFiled']) 
    ? date(' F j, Y', strtotime($row['DateFiled'])) 
    : '';

$destination = $row['destination'] ?? '';
$purpose = $row['LeaveTypeName'] ?? '';
$reason  = $row['travel_detail'] ?? '';

// sa leave type to.  check kung ano field type
$leaveType = '';
foreach (['LeaveType','leave_type','Type','LeaveName','Leave'] as $f) {
    if (!empty($row[$f])) { $leaveType = (string)$row[$f]; break; }
}
if ($leaveType === '') $leaveType = $purpose;


$leaveTypeCoords = [
    'Vacation Leave'            => [15,  76], 
    'Sick'                => [15, 86],
    'Maternity Leave'           => [15, 110],
    'Paternity Leave'           => [15, 120],
    'Study Leave'               => [15, 130],
    'Special Privilege Leave'   => [15, 140],
    'Others'                    => [15, 150],
];


$selectedKey = null;
foreach ($leaveTypeCoords as $key => $coords) {
    if (stripos($leaveType, $key) !== false) { $selectedKey = $key; break; }
}
if ($selectedKey === null) {
    // fallback: exact match
    $selectedKey = array_key_exists($leaveType, $leaveTypeCoords) ? $leaveType : 'Others';
}



$templatePath = realpath(__DIR__ . '/../templates/LeaveForm/City Budget Department.pdf');
if (!$templatePath) die("Template not found");

// --- Generate PDF ---
$pdf = new FPDI_Protection();
$pdf->setSourceFile($templatePath);
$template = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($template, 0, 0, 210);
// Fill fields
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(100, 50); $pdf->Write(5, $fullName);      
$pdf->SetXY(22, 173); $pdf->Write(5, trim(($datefrom ?? '') . (($datefrom && $dateto) ? ' - ' : '') . ($dateto ?? '')));
//$pdf->SetXY(125, 57); $pdf->Write(5, $dateto);      
$pdf->SetXY(25, 50); $pdf->Write(5, $dept);          
$pdf->SetXY(100, 56); $pdf->Write(5, $position);      
$pdf->SetXY(40, 163); $pdf->Write(5, $numDays);   
$pdf->SetXY(40, 56); $pdf->Write(5, $datefiled);   


// Mark the checkbox for the leave type
if (isset($leaveTypeCoords[$selectedKey])) {
    [$lx, $ly] = $leaveTypeCoords[$selectedKey];
    $pdf->SetXY($lx, $ly);
    $pdf->Write(5, 'X');
}


$pdf->SetFont('Arial','',11);
$pdf->SetXY(23,82); 
$pdf->MultiCell(120,5,$reason);

// --- Serve PDF for PDF.js ---
if (ob_get_length()) ob_end_clean();
// Turn off error display to avoid corrupting PDF output
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/pdf');
header('Access-Control-Allow-Origin: *'); // allow PDF.js XHR
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', 'Leave_Form.pdf');
exit;
