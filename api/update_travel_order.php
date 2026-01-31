<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) { echo json_encode(['success'=>false,'message'=>'Invalid JSON']); exit; }

$id = $data['id'] ?? null;
if (!$id) { echo json_encode(['success'=>false,'message'=>'Missing id']); exit; }

try {
    // Ensure current status allows update
    $stmt = $pdo->prepare("SELECT status FROM travel_orders WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['success'=>false,'message'=>'Travel order not found']); exit; }
    if ($row['status'] !== 'Pending Recommendation') { echo json_encode(['success'=>false,'message'=>'Only travel orders in Pending Recommendation can be edited']); exit; }

    $destination = $data['destination'] ?? '';
    $start_date = $data['start_date'] ?? null;
    $end_date = $data['end_date'] ?? null;
    $purpose = $data['purpose'] ?? '';
    $employees = $data['employees'] ?? null; // expected array of emp nos

    try {
        $pdo->beginTransaction();

        // Update only columns that exist in the travel_orders table
        // Current schema includes: destination, start_date, end_date, purpose
        $u = $pdo->prepare("UPDATE travel_orders SET destination = ?, start_date = ?, end_date = ?, purpose = ? WHERE id = ?");
        $u->execute([$destination, $start_date, $end_date, $purpose, $id]);

        if (is_array($employees)) {
            // replace employee links
            $d = $pdo->prepare("DELETE FROM travel_order_employees WHERE travel_order_id = ?");
            $d->execute([$id]);

            $ins = $pdo->prepare("INSERT INTO travel_order_employees (travel_order_id, emp_no) VALUES (?, ?)");
            foreach ($employees as $empNo) {
                $empNo = trim((string)$empNo);
                if ($empNo === '') continue;
                $ins->execute([$id, $empNo]);
            }
        }

        $pdo->commit();
        echo json_encode(['success'=>true,'message'=>'Travel order updated']); exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('update_travel_order error (save employees): '.$e->getMessage());
        echo json_encode(['success'=>false,'message'=>'Server error while saving updates']); exit;
    }

} catch (Exception $e){ error_log('update_travel_order error: '.$e->getMessage()); echo json_encode(['success'=>false,'message'=>'Server error']); exit; }
