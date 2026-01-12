<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

try {
    // determine admin department from session
    $adminDept = null;
    if (!empty($_SESSION['EmpID'])) {
        $stmt = $pdo->prepare("SELECT Dept FROM adminusers WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([ (string)$_SESSION['EmpID'] ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Dept'])) {
            $adminDept = $row['Dept'];
        }
    }

    $month = isset($_GET['month']) && (int)$_GET['month'] > 0 ? (int)$_GET['month'] : null;
    $year  = isset($_GET['year']) && (int)$_GET['year'] > 0 ? (int)$_GET['year'] : null;

    $sql = "
        SELECT 
            el.EmpNo,
            emp.Lname,
            emp.Fname,
            emp.Mname,
            emp.Extension,
            emp.Dept,
            SUM(CASE WHEN el.application_type = 'ETA' AND el.status = 'Approved' THEN 1 ELSE 0 END) AS eta_count,
            SUM(CASE WHEN el.application_type = 'Locator' AND el.status = 'Approved' THEN 1 ELSE 0 END) AS locator_count,
            SUM(CASE WHEN el.status = 'Approved' THEN 1 ELSE 0 END) AS total_usage
        FROM eta_locator el
        LEFT JOIN i emp ON el.EmpNo = emp.EmpNo
    ";

    $conditions = [];
    $params = [];

    if ($adminDept !== null) {
        $conditions[] = 'emp.Dept = :dept';
        $params[':dept'] = $adminDept;
    }

    if ($month !== null && $year !== null) {
        $conditions[] = 'MONTH(el.date_filed) = :month AND YEAR(el.date_filed) = :year';
        $params[':month'] = $month;
        $params[':year']  = $year;
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= " GROUP BY el.EmpNo, emp.Lname, emp.Fname, emp.Mname, emp.Extension, emp.Dept
              ORDER BY total_usage DESC ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
