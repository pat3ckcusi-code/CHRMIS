<?php 

session_start();
require_once('../includes/db_config.php');

date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../Classes/PhpSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT f.*, i.Fname, i.Mname, i.Lname, i.Dept, v.Position, v.Month, f.EmpNo
        FROM filedleave f
        LEFT JOIN i ON f.EmpNo = i.EmpNo
        LEFT JOIN v ON f.EmpNo = v.EmpNo
        WHERE f.LeaveID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    http_response_code(404);
    exit("Leave ID not found.");
}

// =======================
// Choose template per department
// =======================
$templatesDir = __DIR__ . "/../templates/LeaveForm";

switch ($data['Dept']) {
    case "City Human Resource Management Department":
        $templateFile = $templatesDir . "/CHRMD.xlsx";
        break;

    case "City Budget Department":
        $templateFile = $templatesDir . "/CBD.xlsx";
        break;

    default:
        $templateFile = $templatesDir . "/Leave_Form.xlsx"; // fallback template
        break;
}

if (!file_exists($templateFile)) {
    exit("❌ Template file not found: " . basename($templateFile));
}

// Load the chosen template
$spreadsheet = IOFactory::load($templateFile);

// =======================
// Fill cells
// =======================
$sheet = $spreadsheet->getSheet(0);
$spreadsheet->setActiveSheetIndex(0);
$sheet->setCellValue('E5', $data['Lname'] . ", " . $data['Fname'] . " " . $data['Mname']);
$sheet->setCellValue('B5', $data['Dept']);
$sheet->setCellValue('D6', date("F d, Y", strtotime($data['DateFiled'])));
$sheet->setCellValue('F6', $data['Position']);
$sheet->setCellValue('K6', $data['Month']);
$sheet->setCellValue('C44', date("F d, Y", strtotime($data['DateFrom'])) . " - " . date("F d, Y", strtotime($data['DateTo'])));
$sheet->setCellValue('C48', $data['NumDays']);

if ($data['LeaveType'] == "Vacation Leave") {
    $sheet->setCellValue('B11', "✔");
} elseif ($data['LeaveType'] == "Mandatory / Forced Leave") {
    $sheet->setCellValue('B13', "✔");
} elseif ($data['LeaveType'] == "Sick Leave") {
    $sheet->setCellValue('B15', "✔");
}

// =======================
// Setup output paths
// =======================
$outputDir = __DIR__ . "/../outputs";
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$timestamp = date("Ymd_His");

// Save filled sheet as temporary Excel file
$tempXlsx = $outputDir . "/Leave_Form_Filled_{$timestamp}.xlsx";
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($tempXlsx);

// Path to LibreOffice executable (Windows)
$libreOfficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';

// Convert to PDF
exec("$libreOfficePath --headless --convert-to pdf --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($tempXlsx), $output, $return_var);

if ($return_var === 0) {
    $pdfFile = $outputDir . "/Leave_Form_Filled_{$timestamp}.pdf";

    if (file_exists($pdfFile)) {
        // Stream PDF to browser
        header("Content-Type: application/pdf");
        
        //header("Content-Disposition: attachment; filename=Leave_Form_{$timestamp}.pdf"); ---eto pang download
        header("Content-Disposition: inline; filename=Leave_Form_{$timestamp}.pdf");
        header("Cache-Control: max-age=0");
        readfile($pdfFile);


        // Cleanup
        unlink($tempXlsx);
        unlink($pdfFile);
        exit;
    } else {
        echo "❌ PDF file not generated.";
    }
} else {
    echo "❌ Conversion failed. Error: " . implode("\n", $output);
}





// Restriction daw

// session_start();
// require_once('../includes/db_config.php');

// date_default_timezone_set('Asia/Manila');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// require __DIR__ . '/../Classes/PhpSpreadsheet/vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\IOFactory;

// $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// $sql = "SELECT f.*, i.Fname, i.Mname, i.Lname, i.Dept, v.Position, v.Month, f.EmpNo
//         FROM filedleave f
//         LEFT JOIN i ON f.EmpNo = i.EmpNo
//         LEFT JOIN v ON f.EmpNo = v.EmpNo
//         WHERE f.LeaveID = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$id]);
// $data = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$data) {
//     http_response_code(404);
//     exit("Leave ID not found.");
// }

// // =======================
// // Choose template per department
// // =======================
// $templatesDir = __DIR__ . "/../templates/LeaveForm";

// switch ($data['Dept']) {
//     case "City Human Resource Management Department":
//         $templateFile = $templatesDir . "/CHRMD.xlsx";
//         break;

//     case "City Budget Department":
//         $templateFile = $templatesDir . "/CBD.xlsx";
//         break;

//     default:
//         $templateFile = $templatesDir . "/Leave_Form.xlsx"; // fallback template
//         break;
// }

// if (!file_exists($templateFile)) {
//     exit("❌ Template file not found: " . basename($templateFile));
// }

// // Load the chosen template
// $spreadsheet = IOFactory::load($templateFile);

// // =======================
// // Fill cells
// // =======================
// $sheet = $spreadsheet->getActiveSheet();
// $sheet->setCellValue('E5', $data['Lname'] . ", " . $data['Fname'] . " " . $data['Mname']);
// $sheet->setCellValue('B5', $data['Dept']);
// $sheet->setCellValue('D6', date("F d, Y", strtotime($data['DateFiled'])));
// $sheet->setCellValue('F6', $data['Position']);
// $sheet->setCellValue('K6', $data['Month']);
// $sheet->setCellValue('C44', date("F d, Y", strtotime($data['DateFrom'])) . " - " . date("F d, Y", strtotime($data['DateTo'])));
// $sheet->setCellValue('C48', $data['NumDays']);

// if ($data['LeaveType'] == "Vacation Leave") {
//     $sheet->setCellValue('B11', "✔");
// } elseif ($data['LeaveType'] == "Mandatory / Forced Leave") {
//     $sheet->setCellValue('B13', "✔");
// } elseif ($data['LeaveType'] == "Sick Leave") {
//     $sheet->setCellValue('B15', "✔");
// }

// // =======================
// // Setup output paths
// // =======================
// $outputDir = __DIR__ . "/../outputs";
// if (!is_dir($outputDir)) {
//     mkdir($outputDir, 0777, true);
// }

// $timestamp = date("Ymd_His");

// // Save filled sheet as temporary Excel file
// $tempXlsx = $outputDir . "/Leave_Form_Filled_{$timestamp}.xlsx";
// $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save($tempXlsx);

// // Path to LibreOffice executable (Windows)
// $libreOfficePath = '"C:\Program Files\LibreOffice\program\soffice.exe"';

// // Convert to PDF
// exec("$libreOfficePath --headless --convert-to pdf --outdir " . escapeshellarg($outputDir) . " " . escapeshellarg($tempXlsx), $output, $return_var);

// if ($return_var === 0) {
//     $pdfFile = $outputDir . "/Leave_Form_Filled_{$timestamp}.pdf";
//     $securedPDF = $outputDir . "/Leave_Form_Secured_{$timestamp}.pdf";

//     if (file_exists($pdfFile)) {
//         // Apply PDF restrictions using qpdf
//         // Apply PDF restrictions using qpdf
//         $ownerPassword = "owner456"; // Change as needed
//         $qpdfCmd = "qpdf --encrypt '' $ownerPassword 256 --print=full --modify=none --extract=n -- " .
//                 escapeshellarg($pdfFile) . " " . escapeshellarg($securedPDF) . " 2>&1";

//         exec($qpdfCmd, $qpdfOutput, $qpdfStatus);

//         file_put_contents($outputDir . "/qpdf_debug.txt", implode("\n", $qpdfOutput));

//         if ($qpdfStatus === 0 && file_exists($securedPDF)) {
//             header("Content-Type: application/pdf");
//             header("Content-Disposition: inline; filename=Leave_Form_{$timestamp}.pdf");
//             header("Cache-Control: max-age=0");
//             readfile($securedPDF);

//             unlink($tempXlsx);
//             unlink($pdfFile);
//             unlink($securedPDF);
//             exit;
//         } else {
//             echo "❌ Failed to apply PDF restrictions.<br>";
//             echo nl2br(htmlspecialchars(implode("\n", $qpdfOutput)));
//         }
//     }
// }
?>





