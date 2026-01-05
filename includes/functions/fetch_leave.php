<?php
include '../includes/db_config.php';
session_start();

$from_date = $_POST['from_date'] ?? '';
$to_date   = $_POST['to_date'] ?? '';

$sql = "
    SELECT f.LeaveID, 
           CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
           f.LeaveType, f.DateFiled, f.DateFrom, f.DateTo, 
           f.NumDays, f.Remarks, f.Reason 
    FROM filedleave f
    JOIN i ON f.EmpNo = i.EmpNo
    WHERE f.DateFrom >= ? AND f.DateTo <= ?
";

// restrict by Dept (except HR & Mayor)
if ($_SESSION['Dept'] != "City Human Resource Management Department" && $_SESSION['Dept'] != "Office of the Mayor") {
    $sql .= " AND i.Dept = ?";
}

$stmt = mysqli_prepare($conn, $sql);

if ($_SESSION['Dept'] != "City Human Resource Management Department" && $_SESSION['Dept'] != "Office of the Mayor") {
    mysqli_stmt_bind_param($stmt, "sss", $from_date, $to_date, $_SESSION['Dept']);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $from_date, $to_date);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<tr><td colspan='9' class='text-center text-muted'>No records found</td></tr>";
} else {
    while ($Leaverow = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".htmlspecialchars($Leaverow['Name'])."</td>
                <td>".htmlspecialchars($Leaverow['LeaveType'])."</td>
                <td>".htmlspecialchars($Leaverow['DateFrom'])."</td>
                <td>".htmlspecialchars($Leaverow['DateTo'])."</td>
                <td>".htmlspecialchars($Leaverow['DateFiled'])."</td>
                <td>".htmlspecialchars($Leaverow['NumDays'])."</td>
                <td class='text-center'>
                    <a href='Approval.php?id={$Leaverow['LeaveID']}' class='btn btn-success btn-sm rounded-circle'>
                        <i class='fa fa-check'></i>
                    </a>
                </td>
                <td class='text-center'>
                    <a href='Disapproval.php?id={$Leaverow['LeaveID']}' class='btn btn-danger btn-sm rounded-circle'>
                        <i class='fa fa-times'></i>
                    </a>
                </td>
             </tr>";
    }
}

function get_all_leavecredits($pdo) {
    $sql = "SELECT 
                i.EmpNo, 
                CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name,
                l.VL, l.SL, l.CL, l.SPL, l.CTO
            FROM i
            LEFT JOIN leavecredits l ON i.EmpNo = l.EmpNo
            ORDER BY i.Lname ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
