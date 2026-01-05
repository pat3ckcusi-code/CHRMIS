<?php

require_once('../db_config.php');
//require_once('../autoload.php');
require_once('../Excel/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory; // us
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


function update_sf($pdo) {
    // Load the existing Excel file
    $filePath = 'C:/xampp/htdocs/chklist/SF1.xls';  // Correct path to your existing file
    $spreadsheet = IOFactory::load($filePath);
    
    // Get the active sheet 
    $sheet = $spreadsheet->getActiveSheet();

    // Fetch the student data from the database
    $sql = "SELECT * FROM student_tbl order by sex desc, student_name";  
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Start inserting data from row 7 
    $row = 7;  
    foreach ($students as $student) {
        // Set data 
        $sheet->setCellValue('A' . $row, $student['lrn'])           
              ->setCellValue('C' . $row, $student['student_name'])  
              ->setCellValue('H' . $row, $student['section'])       
              ->setCellValue('L' . $row, $student['sex'])           
              ->setCellValue('P' . $row, $student['age']);          
        $row++;  
    }

  
    // Create a new filename for the modified file
    $newFileName = 'Updated_SF1_' . date('Y-m-d_H-i-s') . '.xlsx';  // Adds timestamp to the new file name

    // Save the modified file as a new file (instead of overwriting the original)
    $writer = new Xlsx($spreadsheet);

    // Force the new file to be downloaded
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $newFileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');  // This will trigger the file download
    exit;  
}

// Call the function to update the existing file and generate a new file for download
update_sf($pdo);



?>
