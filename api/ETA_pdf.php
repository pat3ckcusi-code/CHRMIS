<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
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

$Posquery = $pdo->prepare("
    SELECT Position 
    FROM v 
    WHERE EmpNo = ? AND IndateTo = '0000-00-00'
");
$Posquery->execute([$_SESSION["EmpID"]]);
$Posrow = $Posquery->fetch(PDO::FETCH_ASSOC);  

$ext = (!empty($row['Extension']) && strtoupper(trim($row['Extension'])) !== 'N/A') ? ' ' . trim($row['Extension']) : '';
$mname = !empty($row['Mname']) ? ' ' . substr(trim($row['Mname']), 0, 1) . '.' : '';
$fullName = trim($row['Lname'] . ', ' . $row['Fname'] . $mname . $ext);
$dept     = $row['Dept'] ?? '';
$position = $Posrow ? $Posrow['Position'] : "Please update position in PDS";

// --- Format dates ---
$departure = !empty($row['travel_date']) 
    ? date('l, F j, Y', strtotime($row['travel_date'])) 
    : '';

$arrival = !empty($row['arrival_date']) 
    ? date('l, F j, Y', strtotime($row['arrival_date'])) 
    : '';

$dateapproved = !empty($row['last_updated']) 
    ? date('l, F j, Y', strtotime($row['last_updated'])) 
    : '';

$destination = $row['destination'] ?? '';
$purpose = $row['business_type'] ?? '';
$reason  = $row['travel_detail'] ?? '';

//Original template path
// $templatePath = realpath(__DIR__ . '/../templates/ETA/' . $row['Dept'] . '.pdf');
// if (!$templatePath) die("Template not found");

$templatePath = realpath(__DIR__ . '/../templates/ETA/' . $row['Dept'] . '.pdf');

// If not found, use the default template
if ($templatePath === false || !file_exists($templatePath)) {
    $templatePath = realpath(__DIR__ . '/../templates/ETA/ETA.pdf');
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
// Fill fields
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(40, 42); $pdf->Write(5, $fullName);      
$pdf->SetXY(125, 47); $pdf->Write(5, $departure);    
$pdf->SetXY(125, 57); $pdf->Write(5, $arrival);      
$pdf->SetXY(40, 47); $pdf->Write(5, $dept);          
$pdf->SetXY(40, 52); $pdf->Write(5, $position);      
$pdf->SetXY(40, 57); $pdf->Write(5, $destination);   
$pdf->SetXY(120, 143); $pdf->Write(5, $dateapproved);   

// --- Purpose checkboxes ---
$pdf->SetFont('Arial', 'B', 14);
$etaPurposes = [
    'Audit-Inspection-Licensing' => [57, 62],
    'Client Support'              => [106, 62],
    'Conference'                  => [140, 62],
    'Construction Repair Maintenance' => [9, 67],
    'Economic Development'        => [73, 67],
    'Legal-Law Enforcement'       => [9, 72],
    'Legislator'                  => [56, 72],
    'Meeting'                     => [90, 72],
    'Training'                    => [123, 72],
    'Seminar'                     => [156, 72],
    'General Expense/Other'       => [123, 67]
];

if (isset($etaPurposes[$purpose])) {
    [$x, $y] = $etaPurposes[$purpose];
    $pdf->SetXY($x, $y);
    $pdf->Write(5,'X');
}
$pdf->SetXY(40, 131);$pdf->Write(5,'X');
$pdf->SetFont('Arial','',11);
$pdf->SetXY(23,82); 
$pdf->MultiCell(120,5,$reason);

// --- Serve PDF for PDF.js ---
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/pdf');
header('Access-Control-Allow-Origin: *'); // allow PDF.js XHR
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('I', 'ETA_Form.pdf');
exit;
