<?php
session_start();
require_once('../includes/db_config.php');

date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 0);
error_reporting(0);

include __DIR__ . '/../Classes/PHPExcel.php';
include __DIR__ . '/../Classes/PHPExcel/IOFactory.php';

// Load template
$templateFile = __DIR__ . '/Leave_Form.xlsx';
if (!file_exists($templateFile)) {
    header("HTTP/1.1 500 Internal Server Error");
    exit("Template not found: " . $templateFile);
}
$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007')->load($templateFile);

// Get LeaveID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch leave + employee
$stmt = $pdo->prepare("SELECT f.*, i.Fname, i.Mname, i.Lname, i.Dept 
                       FROM filedleave f 
                       JOIN i ON f.EmpNo = i.EmpNo 
                       WHERE f.LeaveID = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
    header("HTTP/1.1 404 Not Found");
    exit("Leave ID not found.");
}

// Fill only specific cells — everything else stays
$sheet = $objPHPExcel->getActiveSheet();
$sheet->setCellValue('E6', $data['Lname'] . ", " . $data['Fname'] . " " . $data['Mname']);


// Clear output buffer
if (ob_get_length()) ob_end_clean();

// Send Excel to browser
$filename = "Leave_Form_" . $id . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007')->save('php://output');
exit;
