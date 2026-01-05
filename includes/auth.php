<?php
session_start();

if (!isset($_SESSION['EmpID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

function currentUserId() {
    return $_SESSION['EmpID'];
}
