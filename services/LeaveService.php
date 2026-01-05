<?php

class LeaveService {
    public function __construct(private LeaveRepository $repo) {}

    public function listLeaves($empNo) {
        $leaves = $this->repo->getLeaves($empNo);

        foreach ($leaves as &$leave) {
            $remarks = trim($leave['Remarks'] ?? 'For Recommendation');
            $status = strtolower($remarks);

            $leave['Status'] = $remarks; // Use Remarks as Status for display
            $leave['status_class'] = match ($status) {
                'approved' => 'success',
                'pending'  => 'warning',
                'rejected' => 'danger',
                'disapproved' => 'danger',
                'cancelled' => 'secondary',
                'for recommendation' => 'info',
                'recommended' => 'info',
                'final / archived' => 'secondary',
                default    => 'secondary'
            };

            $leave['can_print'] = ($status === 'approved');
            $leave['can_cancel'] = ($status === 'for recommendation');
        }

        return $leaves;
    }

    public function cancelLeave($leaveId, $empNo) {
        return $this->repo->cancelLeave($leaveId, $empNo);
    }

    public function getBalances($empNo) {
        return $this->repo->getBalances($empNo);
    }
}
