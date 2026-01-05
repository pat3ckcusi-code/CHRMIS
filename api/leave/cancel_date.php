<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';

header('Content-Type: application/json');

$leaveId = isset($_POST['leave_id']) ? (int)$_POST['leave_id'] : null;
$date = $_POST['date'] ?? null;
$reason = $_POST['reason'] ?? null;

if (!$leaveId || !$date) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing leave_id or date']);
    exit;
}

$repo = new LeaveRepository($pdo);
$ok = $repo->cancelLeaveDate($leaveId, $date, (int)currentUserId(), $reason);

if ($ok) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to cancel leave date (maybe already cancelled or not found)']);
}
