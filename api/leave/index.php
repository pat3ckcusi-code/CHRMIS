<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';
require_once '../../services/LeaveService.php';
require_once '../../controllers/LeaveController.php';

header('Content-Type: application/json');

$repo = new LeaveRepository($pdo);
$service = new LeaveService($repo, $pdo);
$controller = new LeaveController($service);

echo json_encode($controller->index(currentUserId()));
