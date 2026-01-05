<?php
session_start();	

function get_all_leaveapp($pdo, $startDate = null, $endDate = null) {
    $query = "SELECT 
                CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
                i.Dept, 
                f.LeaveID,
                f.LeaveType, 
                f.Purpose, 
                f.DateFiled, 
                f.DateFrom, 
                f.DateTo, 
                a.Recommended, 
                a.Checked, 
                a.Approved,
                f.Reason
              FROM approvingdates a
              JOIN filedleave f ON a.LeaveID = f.LeaveID
              JOIN i ON f.EmpNo = i.EmpNo
              WHERE f.Remarks = 'APPROVED'";

    $params = [];

    // Department-specific condition
    if (!in_array($_SESSION['Dept'], ["City Human Resource Management Department", "Office of the Mayor"])) {
        $query .= " AND i.Dept = :dept";
        $params[':dept'] = $_SESSION['Dept'];
    }

    // Optional date range filter
    if ($startDate && $endDate) {
        $query .= " AND f.DateFiled BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    $statement = $pdo->prepare($query);
    $statement->execute($params);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Add Print button column for City HR department
    if ($_SESSION['Dept'] === "City Human Resource Management Department") {
        foreach ($rows as &$row) {
            $btnClass = ($row['Reason'] !== 'Printed') ? 'btn-info' : 'btn-primary';
            $title = ($row['Reason'] !== 'Printed') ? 'Print' : 'Reprint';
            $row['printButton'] = "<a href='../includes/APPLICATION_FOR_LEAVE.php?id={$row['LeaveID']}'>
                                        <button class='btn {$btnClass} btn-sm rounded-circle' type='button' title='{$title}'>
                                            <i class='fa fa-print'></i>
                                        </button>
                                     </a>";
        }
    } else {
        foreach ($rows as &$row) {
            $row['printButton'] = ''; 
        }
    }

    return $rows;
}

function get_all_disapprovedleave($pdo, $startDate = null, $endDate = null) {
    // Base query for disapproved leaves
    $query = "SELECT 
                CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
                i.Dept,
                f.LeaveType, 
                f.Purpose, 
                f.DateFiled, 
                f.DateFrom,
                f.DateTo,
                a.Disapproved,
                f.LeaveID
              FROM approvingdates a
              JOIN filedleave f ON a.LeaveID = f.LeaveID
              JOIN i ON f.EmpNo = i.EmpNo
              WHERE f.Remarks = 'DISAPPROVED'";

    $params = [];

    // Department-specific condition
    if (!in_array($_SESSION['Dept'], ["City Human Resource Management Department", "Office of the Mayor"])) {
        $query .= " AND i.Dept = :dept";
        $params[':dept'] = $_SESSION['Dept'];
    }

    // Optional date range filter
    if ($startDate && $endDate) {
        $query .= " AND f.DateFiled BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    $statement = $pdo->prepare($query);
    $statement->execute($params);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_ForRecom($pdo, $startDate = null, $endDate = null) {
    // Base query for FOR RECOMMENDATION leaves
    $query = "SELECT 
                CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name,
                i.Dept,
                f.LeaveID,
                f.LeaveType,
                f.Purpose,
                f.DateFiled,
                f.DateFrom,
                f.DateTo,
                a.Recommended,
                a.Checked,
                a.Approved
              FROM approvingdates a
              JOIN filedleave f ON a.LeaveID = f.LeaveID
              JOIN i ON f.EmpNo = i.EmpNo
              WHERE f.Remarks = 'FOR RECOMMENDATION'";

    $params = [];

    // Department-specific condition
    if (!in_array($_SESSION['Dept'], ["City Human Resource Management Department", "Office of the Mayor"])) {
        $query .= " AND i.Dept = :dept";
        $params[':dept'] = $_SESSION['Dept'];
    }

    // Optional date range filter
    if ($startDate && $endDate) {
        $query .= " AND f.DateFiled BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    $statement = $pdo->prepare($query);
    $statement->execute($params);

    // Return all rows as an array
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_departments($pdo) {
    $query = "SELECT DISTINCT Dept FROM i WHERE Dept IS NOT NULL ORDER BY Dept ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_leavecredits($pdo, $dept = null) {
    $query = "SELECT 
                  i.EmpNo,
                  CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name,
                  l.VL,
                  l.SL,
                  l.SPL,
                  l.CTO,
                  i.Dept
              FROM i
              LEFT JOIN leavecredits l ON i.EmpNo = l.EmpNo";

    if (!empty($dept)) {
        $query .= " WHERE i.Dept = :dept";
    }

    $query .= " ORDER BY i.Lname ASC";

    $stmt = $pdo->prepare($query);
    if (!empty($dept)) {
        $stmt->bindParam(':dept', $dept, PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
