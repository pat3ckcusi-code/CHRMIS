<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';
require_once '../../services/LeaveService.php';
require_once '../../controllers/LeaveController.php';

header('Content-Type: application/json');

$controller = new LeaveController(
    new LeaveService(new LeaveRepository($pdo), $pdo)
);

echo json_encode($controller->balance(currentUserId()));
