<?php
session_start();
require_once('../includes/db_config.php');


date_default_timezone_set('Asia/Manila');
if (isset($_POST['btnFile'])) {
    $maxLeaveID = $pdo->query("SELECT MAX(LeaveID) AS 'LID' FROM filedleave");
    $maxID = $maxLeaveID->fetch();
    $RefLID = $maxID['LID'] + 1;

    if ($_POST['ddLeaveType'] == "Compassionate Time Off Leave") {
        $Filedresult = $pdo->query("INSERT INTO filedleave VALUES ('','" . $_SESSION['EmpID'] . "-" . date("Ymd") . "-" . $RefLID . "','" . $_SESSION['EmpID'] . "','" . $_POST['ddLeaveType'] . "','" . $_POST['ddReason'] . "','" . $_POST['txtFrom'] . "','" . $_POST['txtTo'] . "','" . $_POST['txtWorkDays'] . "','" . date("Y-m-d") . "','FOR RECOMMENDATION','')");
        $recommended = $pdo->query("INSERT INTO approvingdates (LeaveID) VALUES ('" . $RefLID . "')");
    } else {
        $Filedresult = $pdo->query("INSERT INTO filedleave VALUES ('','" . $_SESSION['EmpID'] . "-" . date("Ymd") . "-" . $RefLID . "','" . $_SESSION['EmpID'] . "','" . $_POST['ddLeaveType'] . "','','" . $_POST['txtFrom'] . "','" . $_POST['txtTo'] . "','" . $_POST['txtWorkDays'] . "','" . date("Y-m-d") . "','FOR RECOMMENDATION','')");
        $recommended = $pdo->query("INSERT INTO approvingdates (LeaveID) VALUES ('" . $RefLID . "')");
    }

    if (!$Filedresult) {
        // redirect with error
        //header("Location: ../pages/Leave.php?error=1");
		exit;
    } else {
        // redirect with success
        //header("Location: ../pages/Leave.php?success=1");
        echo $Filedresult ? "success" : "error: insert failed";
		exit;
    }
}
?>

<?php

// session_start();
// require_once('../includes/db_config.php');

// date_default_timezone_set('Asia/Manila');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// if (isset($_POST['btnFile'])) {
//     try {
//         $maxLeaveID = $pdo->query("SELECT MAX(LeaveID) AS LID FROM filedleave");
//         $maxID = $maxLeaveID->fetch();
//         $RefLID = ($maxID['LID'] ?? 0) + 1;

//         $ReferenceNo = $_POST['txtID'] . "-" . date("Ymd") . "-" . $RefLID;

//         $stmt = $pdo->prepare("
//             INSERT INTO filedleave 
//             (RefNo, EmpNo, LeaveType, Purpose, DateFrom, DateTo, NumDays, DateFiled, Remarks, Reason) 
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
//         ");

//         $reason = ($_POST['ddLeaveType'] == "Compassionate Time Off Leave") ? $_POST['ddReason'] : '';

//         $Filedresult = $stmt->execute([
//             $ReferenceNo,
//             $_POST['txtID'],
//             $_POST['ddLeaveType'],
//             $reason,
//             $_POST['txtFrom'],
//             $_POST['txtTo'],
//             $_POST['txtWorkDays'],
//             date("Y-m-d"),
//             "FOR RECOMMENDATION",
//             ''
//         ]);

//         $pdo->prepare("INSERT INTO approvingdates (LeaveID) VALUES (?)")
//             ->execute([$RefLID]);

//         echo $Filedresult ? "success" : "error: insert failed";

//     } catch (Exception $e) {
//         echo "error: " . $e->getMessage();  // <-- return actual error
//     }
// } else {
//     echo "error: btnFile not set";
// }

?>



