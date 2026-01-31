<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

$dept = $_SESSION['Dept'] ?? '';

try {
    if (empty($dept)) {
        echo json_encode([]);
        exit;
    }

    // Select travel orders that include employees from this dept
    $sql = "SELECT t.id, t.travel_order_num, t.purpose, t.destination, t.start_date, t.end_date, t.status, t.created_at,
                COUNT(DISTINCT te.emp_no) AS employees_count,
                GROUP_CONCAT(DISTINCT CONCAT(i.Lname, ', ', i.Fname) SEPARATOR '||') AS employees_names_str
            FROM travel_orders t
            JOIN travel_order_employees te ON te.travel_order_id = t.id
            JOIN i ON i.EmpNo = te.emp_no
            WHERE i.Dept = ?
            GROUP BY t.id
            ORDER BY t.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($rows as $r) {
        $names = [];
        if (!empty($r['employees_names_str'])) {
            $names = array_map('trim', explode('||', $r['employees_names_str']));
        }
        $out[] = [
            'id' => (int)$r['id'],
            'travel_order_num' => $r['travel_order_num'],
            'purpose' => $r['purpose'],
            'destination' => $r['destination'],
            'start_date' => $r['start_date'],
            'end_date' => $r['end_date'],
            'status' => $r['status'],
            'created_at' => $r['created_at'],
            'employees_count' => (int)$r['employees_count'],
            'employees_names' => $names
        ];
    }

    echo json_encode($out);
    exit;

} catch (Exception $e) {
    error_log('get_travel_orders error: ' . $e->getMessage());
    echo json_encode([]);
    exit;
}
