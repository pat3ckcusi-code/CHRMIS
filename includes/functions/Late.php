<?php
require_once('../db_config.php');

if (isset($_POST['btnDeduct']) && isset($_POST['EmpNo'])) {
    $empNo = $_POST['EmpNo']; 
    $ded = ($_POST['numMin'] / 60) / 8;

    $stmt = $pdo->prepare("SELECT VL FROM leavecredits WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $vlLeave = $stmt->fetch();

    if ($vlLeave) {
        $res = $vlLeave['VL'] - $ded;

        if ($res >= 0) {
            $upd = $pdo->prepare("UPDATE leavecredits SET VL = ? WHERE EmpNo = ?");
            $ok = $upd->execute([$res, $empNo]);

            if ($ok) {
                echo "<script>alert('Success!');</script>";
            } else {
                echo "<script>alert('Error updating record!');</script>";
            }
        } else {
            echo "<script>alert('Insufficient Vacation Leave!');</script>";
        }
    }
    header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
