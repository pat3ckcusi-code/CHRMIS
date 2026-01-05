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
