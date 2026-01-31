<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

$id = $_GET['id'] ?? null;
if (!$id) { echo json_encode([]); exit; }

try {
    $stmt = $pdo->prepare("SELECT t.*, GROUP_CONCAT(te.emp_no) AS emp_nos FROM travel_orders t LEFT JOIN travel_order_employees te ON te.travel_order_id = t.id WHERE t.id = ? GROUP BY t.id LIMIT 1");
    $stmt->execute([$id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) { echo json_encode([]); exit; }
    $out = [
        'id' => (int)$r['id'],
        'travel_order_num' => $r['travel_order_num'],
        'purpose' => $r['purpose'],
        'destination' => $r['destination'],
        'start_date' => $r['start_date'],
        'end_date' => $r['end_date'],
        'per_diem' => $r['per_diem'] ?? '',
        'appropriation' => $r['appropriation'] ?? '',
        'remarks' => $r['remarks'] ?? '',
        'rejection_note' => $r['rejection_note'] ?? '',
        'status' => $r['status'] ?? '',
        'employees' => $r['emp_nos'] ? explode(',', $r['emp_nos']) : []
    ];
    echo json_encode($out);
    exit;
} catch (Exception $e) { error_log('get_travel_order error: '.$e->getMessage()); echo json_encode([]); exit; }
