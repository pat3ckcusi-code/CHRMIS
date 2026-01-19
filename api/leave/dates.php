<?php
require_once '../../includes/db_config.php';
require_once '../../includes/sessions.php';
header('Content-Type: application/json');

$leaveId = $_GET['leave_id'] ?? null;
if (!$leaveId) {
    http_response_code(400);
    echo json_encode(['error' => 'leave_id is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT LeaveDate, 
        COALESCE(IsCancelled, 0) AS IsCancelled, 
        COALESCE(CancelledBy, '') AS CancelledBy, 
        COALESCE(CancelReason, '') AS CancelReason, 
        COALESCE(CancelledAt, '') AS CancelledAt
        FROM leave_dates WHERE LeaveID = ? ORDER BY LeaveDate ASC");
    $stmt->execute([$leaveId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'dates' => $rows]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch leave dates']);
}
