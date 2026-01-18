<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';
require_once '../../services/LeaveService.php';
require_once '../../controllers/LeaveController.php';

header('Content-Type: application/json');

$leaveId = $_POST['leave_id'] ?? null;

if (!$leaveId) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$controller = new LeaveController(
    new LeaveService(new LeaveRepository($pdo), $pdo)
);

$success = $controller->cancel($leaveId, currentUserId());

echo json_encode(['success' => $success]);
