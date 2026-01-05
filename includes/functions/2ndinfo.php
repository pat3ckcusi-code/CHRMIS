<?php
session_start();
require_once('../db_config.php');

header('Content-Type: application/json');

$response = [
    "success" => false,
    "title"   => "Error",
    "message" => "Something went wrong.",
    "icon"    => "error"
];

if (isset($_SESSION['EmpID'])) {
    if (isset($_POST['Save'])) {
        $count  = $_POST['numtext'];
        $count1 = $_POST['numtext1'];

        // delete old records in iv
            $delStmt = $pdo->prepare("DELETE FROM iv WHERE EmpNo = :empno");
            $delStmt->execute([':empno' => $_SESSION['EmpID']]);

            // INSERT query 
            $insertStmt = $pdo->prepare("
                INSERT INTO iv (EmpNo, Career, Rating, Date, Place, LiNum, LiDate)
                VALUES (:empno, :career, :rating, :date, :place, :linum, :lidate)
            ");

            for ($x = 1; $x <= $count; $x++) {
                $career = $_POST['Career' . $x] ?? '';
                
                if (!empty($career)) {
                    $insertStmt->execute([
                        ':empno' => $_SESSION['EmpID'],
                        ':career' => $career,
                        ':rating' => $_POST['Rating' . $x] ?? null,
                        ':date'   => $_POST['Date' . $x] ?? null,
                        ':place'  => $_POST['Place' . $x] ?? null,
                        ':linum'  => $_POST['LiNum' . $x] ?? null,
                        ':lidate' => $_POST['LiDate' . $x] ?? null,
                    ]);
                }
            }


        // delete old records in v        
            $delStmt2 = $pdo->prepare("DELETE FROM v WHERE EmpNo = :empno");
            $delStmt2->execute([':empno' => $_SESSION['EmpID']]);

            // INSERT query 
            $insertStmt2 = $pdo->prepare("
                INSERT INTO v (EmpNo, InDateFrom, InDateTo, Position, Dept, Month, Salary, Status, GovService)
                VALUES (:empno, :indatefrom, :indateto, :position, :dept, :month, :salary, :status, :govservice)
            ");

            for ($y = 1; $y <= $count1; $y++) {
                $dept = $_POST['Dept' . $y] ?? '';

                if (!empty($dept)) {
                    $insertStmt2->execute([
                        ':empno'      => $_SESSION['EmpID'],
                        ':indatefrom' => $_POST['IndateFrom' . $y] ?? null,
                        ':indateto'   => $_POST['IndateTo' . $y] ?? null,
                        ':position'   => $_POST['Position' . $y] ?? null,
                        ':dept'       => $dept,
                        ':month'      => $_POST['Month' . $y] ?? null,
                        ':salary'     => $_POST['Salary' . $y] ?? null,
                        ':status'     => $_POST['Status' . $y] ?? null,
                        ':govservice' => $_POST['GovService' . $y] ?? null,
                    ]);
                }
            }      
        $response = [
            "success" => true,
            "title"   => "Saved!",
            "message" => "Your information has been saved successfully.",
            "icon"    => "success"
        ];
    }
} else {
    
    $response = [
        "success" => false,
        "title"   => "Incomplete Information",
        "message" => "Please complete your Personal Information first!",
        "icon"    => "warning"
    ];
}

echo json_encode($response);
exit;
?>
