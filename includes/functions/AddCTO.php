<?php
require_once('../db_config.php');

if (isset($_POST['btnAdd']) && isset($_POST['EmpNo'])) {
    $empNo    = $_POST['EmpNo'];
    $reason   = strtoupper($_POST['reason']);
    $ctoHours = $_POST['ctoHours'];   // 1, 1.25, 1.5
    $numHours = $_POST['numHours'];
    $holiType = isset($_POST['HoliType']) ? strtoupper($_POST['HoliType']) : 'REGULAR DAY';

    // compute added CTO
    $addValue = $numHours * ($ctoHours / 8);

    // fetch current CTO
    $stmt = $pdo->prepare("SELECT CTO FROM leavecredits WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $row = $stmt->fetch();

    if ($row) {
        $newCTO = $row['CTO'] + $addValue;

        // update leavecredits
        $upd = $pdo->prepare("UPDATE leavecredits SET CTO = ? WHERE EmpNo = ?");
        $ok1 = $upd->execute([$newCTO, $empNo]);

        // insert into history
        $ins = $pdo->prepare("INSERT INTO ctohistory (EmpNo, Date, NumHours, Reason, HolidayType) 
                              VALUES (?, ?, ?, ?, ?)");
        $ok2 = $ins->execute([$empNo, date("Y-m-d"), $numHours, $reason, $holiType]);

        if ($ok1 && $ok2) {
            echo "<script>alert('Compensatory Time Off Leave Successfully Added!');</script>";
        } else {
            echo "<script>alert('Error saving CTO leave!');</script>";
        }
    }
    header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
