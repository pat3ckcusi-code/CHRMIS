<?php

class LeaveRepository {
    public function __construct(private PDO $pdo) {}

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

    public function cancelLeave($leaveId, $empNo) {
        $stmt = $this->pdo->prepare("
            UPDATE filedleave
            SET Status = 'Cancelled'
            WHERE LeaveID = ? AND EmpNo = ?
              AND Status = 'For Recommendation'
        ");
        return $stmt->execute([$leaveId, $empNo]);
    }

    public function getBalances($empNo) {
        $stmt = $this->pdo->prepare("
            SELECT
              COALESCE(VL, 0) AS VL,
              COALESCE(SL, 0) AS SL,
              COALESCE(CL, 0) AS CL,
              COALESCE(SPL, 0) AS SPL,
              COALESCE(CTO, 0) AS CTO
            FROM leavecredits
            WHERE EmpNo = ?
            LIMIT 1
        ");

        $stmt->execute([$empNo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // Return zeros when no record exists
            $row = ['VL' => 0, 'SL' => 0, 'CL' => 0, 'SPL' => 0, 'CTO' => 0];
        }

        return [
            'vacation' => (float)$row['VL'],   
            'sick'     => (float)$row['SL'],   
            'casual'   => (float)$row['CL'],  
            'special'  => (float)$row['SPL'],  
            'cto'      => (float)$row['CTO']   
        ];
    }

    /**
     * Create a leave record. Returns the inserted LeaveID on success, false otherwise.
     */
    /**
     * Create a leave record. Returns the inserted LeaveID on success, false otherwise.
     *
     * Parameters added to match `filedleave` schema:
     * - $leaveTypeName (e.g., 'Vacation')
     * - $leaveTypeCode (e.g., 'VL')
     * - $totalDays (decimal, e.g., 1.0, 2.5)
     * - $purposeOfLeave (longer description)
     * - $vacationCreditsSnapshot, $sickCreditsSnapshot (decimal)
     * - $employeeInfo: optional array with keys EmployeeName, Position, Office, SalaryGrade
     */
    public function createLeave(
        string $empNo,
        string $leaveTypeName,
        string $leaveTypeCode,
        string $dateFrom,
        string $dateTo,
        float $totalDays,
        string $purposeOfLeave = '',
        string $reason = '',
        ?string $documentFilename = null,
        ?array $employeeInfo = null,
        float $vacationCreditsSnapshot = 0.0,
        float $sickCreditsSnapshot = 0.0
    ) {
        try {
            $this->pdo->beginTransaction();

            $referenceNo = sprintf('%s-%s-%s', $empNo, date('Ymd'), random_int(1000, 9999));

            $employeeName = $employeeInfo['EmployeeName'] ?? null;
            $position = $employeeInfo['Position'] ?? null;
            $office = $employeeInfo['Office'] ?? null;
            $salaryGrade = $employeeInfo['SalaryGrade'] ?? null;

            $fileNote = $documentFilename ? (' [doc:' . $documentFilename . ']') : '';
            $reasonField = trim($reason) . $fileNote;

            $stmt = $this->pdo->prepare(
                "INSERT INTO filedleave 
                (RefNo, EmpNo, EmployeeName, Position, Office, SalaryGrade, LeaveTypeName, LeaveTypeCode, Purpose, DateFrom, DateTo, TotalDays, DateFiled, Status, Reason, PurposeOfLeave, VacationLeaveCredits, SickLeaveCredits, RequestedCommutation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            
            try {
                $executed = $stmt->execute([
                    $referenceNo,
                    $empNo,
                    $employeeName,
                    $position,
                    $office,
                    $salaryGrade,
                    $leaveTypeName,
                    $leaveTypeCode,
                    '', 
                    $dateFrom,
                    $dateTo,
                    $totalDays,
                    date('Y-m-d'),
                    'For Recommendation',
                    $reasonField,
                    $purposeOfLeave,
                    $vacationCreditsSnapshot,
                    $sickCreditsSnapshot,
                    'No'
                ]);
            } catch (Throwable $e) {
                // Log the exception for debugging
                $msg = sprintf("createLeave: modern insert failed: %s", $e->getMessage());
                $logDir = dirname(__DIR__, 2) . '/logs';
                if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                error_log($msg . PHP_EOL . $e->getTraceAsString(), 3, $logDir . '/leave_errors.log');
                $executed = false;
            }

            // If modern insert failed (or returned false), attempt legacy insert for older schema.
            if (!$executed) {
                // Log a short info that we're switching to legacy insert path
                $logDir = dirname(__DIR__, 2) . '/logs';
                if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                error_log(sprintf("[createLeave][info] modern insert failed — switching to legacy insert | emp=%s | ref=%s", $empNo, $referenceNo) . PHP_EOL, 3, $logDir . '/leave_errors.log');

                // Previous transaction may have been rolled back implicitly by the DB in case of certain errors.
                // Ensure we are in a clean transaction state before attempting legacy insert.
                try {
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }
                } catch (Throwable $e) {
                    // ignore rollBack exceptions here — we'll start a new transaction anyway
                }

                // Start a fresh transaction for the legacy insert path
                $this->pdo->beginTransaction();

                try {
                    $legacy = $this->pdo->prepare("INSERT INTO filedleave (RefNo, EmpNo, LeaveType, Purpose, DateFrom, DateTo, NumDays, DateFiled, Remarks, Reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $legacyType = $leaveTypeName; // keep the same human-readable type
                    $legacyExecuted = $legacy->execute([
                        $referenceNo,
                        $empNo,
                        $legacyType,
                        $purposeOfLeave ?: '',
                        $dateFrom,
                        $dateTo,
                        (int)$totalDays,
                        date('Y-m-d'),
                        'FOR RECOMMENDATION',
                        $reasonField
                    ]);
                    if (!$legacyExecuted) {
                        $msg = sprintf("createLeave: legacy insert returned false for EmpNo %s", $empNo);
                        $logDir = dirname(__DIR__, 2) . '/logs';
                        if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                        error_log($msg, 3, $logDir . '/leave_errors.log');
                        $this->pdo->rollBack();
                        return false;
                    }
                } catch (Throwable $e) {
                    $msg = sprintf("createLeave: legacy insert exception: %s", $e->getMessage());
                    $logDir = dirname(__DIR__, 2) . '/logs';
                    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                    error_log($msg . PHP_EOL . $e->getTraceAsString(), 3, $logDir . '/leave_errors.log');
                    $this->pdo->rollBack();
                    return false;
                }
            }

            // Try to get the insert id. Not all legacy schemas may have AUTO_INCREMENT —
            $leaveIdRaw = $this->pdo->lastInsertId();
            $leaveId = $leaveIdRaw ? (int)$leaveIdRaw : 0;

            // If lastInsertId returned falsy (0), try to locate the inserted row by the unique RefNo
            if (!$leaveId) {
                $stmt = $this->pdo->prepare("SELECT LeaveID FROM filedleave WHERE RefNo = ? ORDER BY LeaveID DESC LIMIT 1");
                $stmt->execute([$referenceNo]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && isset($row['LeaveID'])) {
                    $leaveId = (int)$row['LeaveID'];
                }
            }

            // Insert into approvingdates if the table exists (best-effort)
            if ($leaveId) {
                $this->pdo->prepare("INSERT INTO approvingdates (LeaveID) VALUES (?)")->execute([$leaveId]);
            }

            // Best-effort: backfill legacy columns to ensure other parts (approval flow) work correctly
            try {
                if ($leaveId) {
                    // Map short type names to older 'LeaveType' strings expected by Approval.php
                    
                    $legacyMap = [
                        'Vacation' => 'Vacation Leave',
                        'Sick' => 'Sick Leave',
                        'Maternity' => 'Maternity Leave',
                        'Paternity' => 'Paternity Leave',
                        'Adoption' => 'Adoption Leave',
                        'Solo Parent' => 'Solo Parent Leave',
                        'VAWC' => 'VAWC Leave',
                        'Gynecological' => 'Special Leave (Gynecological)',
                        'Emergency' => 'Special Emergency (Calamity) Leave',
                        'Special Privilege' => 'Special Leave Privilege',
                        'Study' => 'Study / Examination Leave',
                        'LWOP' => 'Leave Without Pay'
                    ];
                    $legacyType = $legacyMap[$leaveTypeName] ?? $leaveTypeName;

                    // Set LeaveType (legacy column) and NumDays to the same computed value so Approval.php sees NumDays
                    $upd = $this->pdo->prepare("UPDATE filedleave SET LeaveType = ?, NumDays = ?, TotalDays = ? WHERE LeaveID = ?");
                    $upd->execute([$legacyType, (int)$totalDays, (float)$totalDays, $leaveId]);
                }
            } catch (Throwable $e) {
                // Ignore — this is best-effort to maintain compatibility with older schemas
            }

            // Create leave_dates table if it does not exist (best-effort)
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS leave_dates (
                    LeaveDateID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    LeaveID INT(11) NOT NULL,
                    LeaveDate DATE NOT NULL,
                    IsCancelled TINYINT(1) NOT NULL DEFAULT 0,
                    CancelledBy INT(11) DEFAULT NULL,
                    CancelReason VARCHAR(255) DEFAULT NULL,
                    CancelledAt DATETIME DEFAULT NULL,
                    INDEX (LeaveID),
                    INDEX (LeaveDate)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
            );

            // Insert individual leave dates (only weekdays)
            $start = new DateTime($dateFrom);
            $end = new DateTime($dateTo);
            $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
            $ins = $this->pdo->prepare("INSERT INTO leave_dates (LeaveID, LeaveDate) VALUES (?, ?)");
            foreach ($period as $dt) {
                $day = (int)$dt->format('w'); // 0=Sun,6=Sat
                if ($day === 0 || $day === 6) continue; // skip weekends
                if ($leaveId) $ins->execute([$leaveId, $dt->format('Y-m-d')]);
            }

            // Commit safely — only if a transaction is active; capture unexpected state for debugging.
            try {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->commit();
                } else {
                    $msg = sprintf("[createLeave][warning] No active transaction at commit | emp=%s | from=%s | to=%s | totalDays=%s | ref=%s", $empNo, $dateFrom, $dateTo, $totalDays, $referenceNo);
                    $logDir = dirname(__DIR__, 2) . '/logs';
                    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                    error_log($msg, 3, $logDir . '/leave_errors.log');
                }
            } catch (Throwable $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                $msg = sprintf("[createLeave][exception][commit] %s | emp=%s | from=%s | to=%s | totalDays=%s | ref=%s", $e->getMessage(), $empNo, $dateFrom, $dateTo, $totalDays, $referenceNo);
                $logDir = dirname(__DIR__, 2) . '/logs';
                if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                error_log($msg . PHP_EOL . $e->getTraceAsString(), 3, $logDir . '/leave_errors.log');
                return false;
            }

            return $leaveId;

        } catch (Throwable $e) {
            // roll back and log the error
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $msg = sprintf("[createLeave][exception] %s | emp=%s | from=%s | to=%s | totalDays=%s | ref=%s", $e->getMessage(), $empNo, $dateFrom, $dateTo, $totalDays, $referenceNo);
            $logDir = dirname(__DIR__, 2) . '/logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
            error_log($msg . PHP_EOL . $e->getTraceAsString(), 3, $logDir . '/leave_errors.log');
            return false;
        }
    }

    /**
     * Cancel a specific date within a leave and adjust credits if the leave was already approved.
     * Returns true on success, false otherwise.
     */
    public function cancelLeaveDate(int $leaveId, string $date, int $cancelledBy, ?string $reason = null) {
        try {
            $this->pdo->beginTransaction();

            // Mark the leave date as cancelled
            $stmt = $this->pdo->prepare("UPDATE leave_dates SET IsCancelled = 1, CancelledBy = ?, CancelReason = ?, CancelledAt = ? WHERE LeaveID = ? AND LeaveDate = ? AND IsCancelled = 0");
            $ok = $stmt->execute([$cancelledBy, $reason, date('Y-m-d H:i:s'), $leaveId, $date]);
            if (!$ok || $stmt->rowCount() === 0) {
                $this->pdo->rollBack();
                return false;
            }

            // If the main leave is approved, refund one day (or .5 depending on stored values) to the employee's credits and adjust filedleave.TotalDays/NumDays
            $lf = $this->pdo->prepare("SELECT LeaveID, EmpNo, LeaveTypeCode, TotalDays, DateFrom, DateTo, Status FROM filedleave WHERE LeaveID = ? LIMIT 1");
            $lf->execute([$leaveId]);
            $leave = $lf->fetch(PDO::FETCH_ASSOC);
            if (!$leave) {
                $this->pdo->rollBack();
                return false;
            }

            // If approved, add back credit and decrement TotalDays/NumDays
            
            if (strtolower($leave['Status']) === 'approved' || strtolower($leave['Remarks'] ?? '') === 'approved') {
                // Map leave code to column
                $code = $leave['LeaveTypeCode'] ?? $leave['LeaveType'] ?? '';
                $col = match (strtolower($code)) {
                    'vl', 'vacation', 'vacation leave' => 'VL',
                    'sl', 'sick', 'sick leave' => 'SL',
                    'cl', 'casual', 'compassionate' => 'CL',
                    'spl' => 'SPL',
                    'cto' => 'CTO',
                    default => null
                };

                if ($col) {
                    // Increase credit by 1.0 (assumes full day). If you use fractional days, modify accordingly.
                    $up = $this->pdo->prepare("UPDATE leavecredits SET {$col} = COALESCE({$col},0) + 1 WHERE EmpNo = ?");
                    $up->execute([$leave['EmpNo']]);

                    // Decrement TotalDays or NumDays on filedleave
                    // Use TotalDays if exists, otherwise use NumDays (legacy)
                    
                    if (array_key_exists('TotalDays', $leave)) {
                        $upd = $this->pdo->prepare("UPDATE filedleave SET TotalDays = GREATEST(0, TotalDays - 1) WHERE LeaveID = ?");
                        $upd->execute([$leaveId]);
                    }
                    $upd2 = $this->pdo->prepare("UPDATE filedleave SET NumDays = GREATEST(0, NumDays - 1) WHERE LeaveID = ?");
                    $upd2->execute([$leaveId]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return false;
        }
    }
}
