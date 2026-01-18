<?php

class LeaveService {

    public function __construct(
        private LeaveRepository $repo,
        private PDO $pdo
    ) {}

    /* ============================
     * LIST LEAVES
     * ============================ */
    public function listLeaves($empNo) {
        $leaves = $this->repo->getLeaves($empNo);

        foreach ($leaves as &$leave) {
            $remarks = trim($leave['Remarks'] ?? 'For Recommendation');
            $status  = strtolower($remarks);

            $leave['Status'] = $remarks;
            $leave['status_class'] = match ($status) {
                'approved'           => 'success',
                'pending'            => 'warning',
                'rejected',
                'disapproved'        => 'danger',
                'cancelled'          => 'secondary',
                'for recommendation',
                'recommended'        => 'info',
                'final / archived'   => 'secondary',
                default              => 'secondary'
            };

            $leave['can_print']  = ($status === 'approved');
            $leave['can_cancel'] = ($status === 'for recommendation');
        }

        return $leaves;
    }

    /* ============================
     * CREATE LEAVE (TRANSACTION OWNER)
     * ============================ */
    public function createLeave(
        string $empNo,
        string $leaveTypeName,
        string $leaveTypeCode,
        string $dateFrom,
        string $dateTo,
        float  $totalDays,
        string $purpose = '',
        string $reason = '',
        ?string $documentFilename = null,
        array $employeeInfo = [],
        float $vacationCreditsSnapshot = 0.0,
        float $sickCreditsSnapshot = 0.0
    ): int {

        $this->pdo->beginTransaction();

        try {
            $leaveId = $this->repo->createLeave(
                $empNo,
                $leaveTypeName,
                $leaveTypeCode,
                $dateFrom,
                $dateTo,
                $totalDays,
                $purpose,
                $reason,
                $documentFilename,
                $employeeInfo,
                $vacationCreditsSnapshot,
                $sickCreditsSnapshot
            );

            if (!$leaveId) {
                throw new RuntimeException('Leave creation failed.');
            }

            $this->pdo->commit();
            return $leaveId;

        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            // Let controller / API return the message
            throw $e;
        }
    }

    /* ============================
     * CANCEL LEAVE
     * ============================ */
    public function cancelLeave($leaveId, $empNo) {
        return $this->repo->cancelLeave($leaveId, $empNo);
    }

    /* ============================
     * GET BALANCES
     * ============================ */
    public function getBalances($empNo) {
        return $this->repo->getBalances($empNo);
    }
}
