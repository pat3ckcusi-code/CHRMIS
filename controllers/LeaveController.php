<?php

class LeaveController {
    public function __construct(private LeaveService $service) {}

    public function index($empNo) {
        return $this->service->listLeaves($empNo);
    }

    public function balance($empNo) {
        return $this->service->getBalances($empNo);
    }

    public function cancel($leaveId, $empNo) {
        return $this->service->cancelLeave($leaveId, $empNo);
    }
}
try {
    require_once '../../includes/auth.php';
} catch (Throwable $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
