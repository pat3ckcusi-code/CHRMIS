<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

try {
    $empNo = isset($_GET['empNo']) ? trim($_GET['empNo']) : '';
    $type  = isset($_GET['type']) ? trim($_GET['type']) : '';
    $month = isset($_GET['month']) && (int)$_GET['month'] > 0 ? (int)$_GET['month'] : null;
    $year  = isset($_GET['year']) && (int)$_GET['year'] > 0 ? (int)$_GET['year'] : null;

    if ($empNo === '' || ($type !== 'ETA' && $type !== 'Locator')) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        exit;
    }

    $sql = "SELECT * FROM eta_locator WHERE EmpNo = :empNo AND application_type = :type AND status = 'Approved'";
    $params = [':empNo' => $empNo, ':type' => $type];

    // filter by month/year on date_filed when provided
    if ($month !== null && $year !== null) {
        $sql .= ' AND MONTH(date_filed) = :month AND YEAR(date_filed) = :year';
        $params[':month'] = $month;
        $params[':year']  = $year;
    }

    $sql .= ' ORDER BY date_filed DESC, travel_date DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
