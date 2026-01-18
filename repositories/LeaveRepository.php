<?php

class LeaveRepository {
    public function __construct(private PDO $pdo) {}

    /* ============================
     * LIST LEAVES
     * ============================ */
    public function getLeaves($empNo) {
        $stmt = $this->pdo->prepare("
            SELECT
                LeaveID,
                DateFiled,
                COALESCE(LeaveTypeName, LeaveTypeCode) AS LeaveType,
                DateFrom,
                DateTo,
                COALESCE(Status, 'For Recommendation') AS Remarks
            FROM filedleave
            WHERE EmpNo = ?
            ORDER BY DateFiled DESC
        ");
        $stmt->execute([$empNo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
     * CANCEL LEAVE
     * ============================ */
    public function cancelLeave($leaveId, $empNo) {
        $stmt = $this->pdo->prepare("
            UPDATE filedleave
            SET Status = 'Cancelled'
            WHERE LeaveID = ?
              AND EmpNo = ?
              AND Status = 'For Recommendation'
        ");
        return $stmt->execute([$leaveId, $empNo]);
    }

    /* ============================
     * GET BALANCES
     * ============================ */
    public function getBalances($empNo) {
        $stmt = $this->pdo->prepare("
            SELECT
                COALESCE(VL,0)  AS VL,
                COALESCE(SL,0)  AS SL,
                COALESCE(CL,0)  AS CL,
                COALESCE(SPL,0) AS SPL,
                COALESCE(CTO,0) AS CTO
            FROM leavecredits
            WHERE EmpNo = ?
            LIMIT 1
        ");
        $stmt->execute([$empNo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'vacation' => (float)($row['VL']  ?? 0),
            'sick'     => (float)($row['SL']  ?? 0),
            'casual'   => (float)($row['CL']  ?? 0),
            'special'  => (float)($row['SPL'] ?? 0),
            'cto'      => (float)($row['CTO'] ?? 0),
        ];
    }

    /* =====================================================
     * CREATE LEAVE — CLEAN, SINGLE-PATH, SCHEMA-ALIGNED
     * ===================================================== */
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

        $referenceNo = sprintf(
            '%s-%s-%s',
            $empNo,
            date('YmdHis'),
            bin2hex(random_bytes(3))
        );

        $employeeName = $employeeInfo['EmployeeName'] ?? null;
        $position     = $employeeInfo['Position']     ?? null;
        $office       = $employeeInfo['Office']       ?? null;
        $salaryGrade  = $employeeInfo['SalaryGrade']  ?? null;

        $fileNote    = $documentFilename ? (' [doc:' . $documentFilename . ']') : '';
        $reasonField = trim($reason) . $fileNote;

        $stmt = $this->pdo->prepare("
            INSERT INTO filedleave (
                RefNo,
                EmpNo,
                EmployeeName,
                Position,
                Office,
                SalaryGrade,
                LeaveTypeName,
                LeaveTypeCode,
                Purpose,
                DateFrom,
                DateTo,
                TotalDays,
                DateFiled,
                Status,
                Reason,
                VacationLeaveCredits,
                SickLeaveCredits,
                RequestedCommutation
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $ok = $stmt->execute([
            $referenceNo,
            $empNo,
            $employeeName,
            $position,
            $office,
            $salaryGrade,
            $leaveTypeName,
            $leaveTypeCode,
            $purpose,
            $dateFrom,
            $dateTo,
            $totalDays,
            date('Y-m-d'),
            'For Recommendation',
            $reasonField,
            $vacationCreditsSnapshot,
            $sickCreditsSnapshot,
            'No'
        ]);

        if (!$ok) {
            throw new RuntimeException('Failed to insert leave record.');
        }

        /* ---- Fetch LeaveID safely ---- */
        $idStmt = $this->pdo->prepare(
            "SELECT LeaveID FROM filedleave WHERE RefNo = ? LIMIT 1"
        );
        $idStmt->execute([$referenceNo]);
        $row = $idStmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['LeaveID'])) {
            throw new RuntimeException('Leave inserted but ID not found.');
        }

        $leaveId = (int)$row['LeaveID'];

        /* ---- approvingdates (best-effort) ---- */
        try {
            $this->pdo
                ->prepare("INSERT INTO approvingdates (LeaveID) VALUES (?)")
                ->execute([$leaveId]);
        } catch (Throwable $e) {
            error_log('[leave][approvingdates] ' . $e->getMessage());
        }

        /* ---- leave_dates (weekdays only) ---- */
        $start  = new DateTime($dateFrom);
        $end    = (new DateTime($dateTo))->modify('+1 day');
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        $ins = $this->pdo->prepare(
            "INSERT INTO leave_dates (LeaveID, LeaveDate) VALUES (?, ?)"
        );

        foreach ($period as $dt) {
            $day = (int)$dt->format('w'); // 0=Sun,6=Sat
            if ($day === 0 || $day === 6) continue;
            $ins->execute([$leaveId, $dt->format('Y-m-d')]);
        }

        return $leaveId;
    }
}
