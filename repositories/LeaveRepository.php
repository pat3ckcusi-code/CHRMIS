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
        float $sickCreditsSnapshot = 0.0,
        ?array $dates = null
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

        /* ---- leave_dates insertion ---- */
        // Determine which dates to insert: explicit $dates (preferred) or date range fallback
        $datesToInsert = [];
        if (!empty($dates) && is_array($dates)) {
            foreach ($dates as $d) {
                $dt = DateTime::createFromFormat('Y-m-d', $d);
                if (!$dt) continue;
                $w = (int)$dt->format('w');
                if ($w === 0 || $w === 6) continue; // skip weekends
                $datesToInsert[$dt->format('Y-m-d')] = true;
            }
            $datesToInsert = array_keys($datesToInsert);
            sort($datesToInsert);
        } else {
            $start  = new DateTime($dateFrom);
            $end    = (new DateTime($dateTo))->modify('+1 day');
            $period = new DatePeriod($start, new DateInterval('P1D'), $end);
            foreach ($period as $dt) {
                $day = (int)$dt->format('w'); // 0=Sun,6=Sat
                if ($day === 0 || $day === 6) continue;
                $datesToInsert[] = $dt->format('Y-m-d');
            }
        }

        // Check if extended audit columns exist
        $colStmt = $this->pdo->prepare(
            "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leave_dates'"
        );
        $colStmt->execute();
        $cols = array_map(function($r){ return $r['COLUMN_NAME']; }, $colStmt->fetchAll(PDO::FETCH_ASSOC));

        $useExtended = in_array('IsCancelled', $cols, true) && in_array('CancelledBy', $cols, true);

        if ($useExtended) {
            $ins = $this->pdo->prepare(
                "INSERT INTO leave_dates (LeaveID, LeaveDate, IsCancelled, CancelledBy, CancelReason, CancelledAt) VALUES (?, ?, 0, NULL, NULL, NULL)"
            );
        } else {
            $ins = $this->pdo->prepare(
                "INSERT INTO leave_dates (LeaveID, LeaveDate) VALUES (?, ?)"
            );
        }

        foreach ($datesToInsert as $d) {
            $ins->execute([$leaveId, $d]);
        }

        return $leaveId;
    }

    /* ============================
     * CANCEL A SINGLE LEAVE DATE
     * Marks a date in `leave_dates` as cancelled, refunds 1 credit, and adjusts filedleave totals
     */
    public function cancelLeaveDate(int $leaveId, string $date, int $cancelledBy, ?string $reason = null): bool {
        $this->pdo->beginTransaction();
        try {
            // fetch leave date row
            $stmt = $this->pdo->prepare("SELECT LeaveDateID, IsCancelled FROM leave_dates WHERE LeaveID = ? AND LeaveDate = ? LIMIT 1");
            $stmt->execute([$leaveId, $date]);
            $ld = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ld) {
                $this->pdo->rollBack();
                return false;
            }
            if (!empty($ld['IsCancelled'])) {
                $this->pdo->rollBack();
                return false;
            }

            // fetch filedleave to determine empno and leave type
            $stmt = $this->pdo->prepare("SELECT EmpNo, LeaveTypeCode, TotalDays FROM filedleave WHERE LeaveID = ? LIMIT 1");
            $stmt->execute([$leaveId]);
            $fl = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$fl) {
                $this->pdo->rollBack();
                return false;
            }

            // mark leave_dates row cancelled
            $upd = $this->pdo->prepare("UPDATE leave_dates SET IsCancelled = 1, CancelledBy = ?, CancelReason = ?, CancelledAt = NOW() WHERE LeaveDateID = ?");
            $upd->execute([$cancelledBy, $reason, $ld['LeaveDateID']]);

            // decrement filedleave.TotalDays if present
            $dec = $this->pdo->prepare("UPDATE filedleave SET TotalDays = GREATEST(COALESCE(TotalDays,0) - 1, 0) WHERE LeaveID = ?");
            $dec->execute([$leaveId]);

            // refund one day to leavecredits depending on LeaveTypeCode
            $empNo = $fl['EmpNo'];
            $code = strtoupper(trim((string)($fl['LeaveTypeCode'] ?? '')));
            $col = null;
            if ($code === 'VL' || stripos($fl['LeaveTypeCode'] ?? '', 'VAC') !== false) $col = 'VL';
            elseif ($code === 'SL' || stripos($fl['LeaveTypeCode'] ?? '', 'SICK') !== false) $col = 'SL';
            elseif ($code === 'CL' || stripos($fl['LeaveTypeCode'] ?? '', 'CASUAL') !== false) $col = 'CL';
            elseif ($code === 'SPL' || stripos($fl['LeaveTypeCode'] ?? '', 'SPL') !== false) $col = 'SPL';
            elseif ($code === 'CTO' || stripos($fl['LeaveTypeCode'] ?? '', 'CTO') !== false) $col = 'CTO';

            if ($col !== null) {
                // if leavecredits row exists update, else insert
                $chk = $this->pdo->prepare("SELECT EmpNo FROM leavecredits WHERE EmpNo = ? LIMIT 1");
                $chk->execute([$empNo]);
                if ($chk->fetch()) {
                    $this->pdo->prepare("UPDATE leavecredits SET {$col} = COALESCE({$col},0) + 1 WHERE EmpNo = ?")->execute([$empNo]);
                } else {
                    // insert minimal row with refunded credit
                    $ins = $this->pdo->prepare("INSERT INTO leavecredits (EmpNo, VL, SL, CL, SPL, CTO) VALUES (?, ?, 0, 0, 0, 0)");
                    if ($col === 'VL') $ins->execute([$empNo, 1]);
                    else $ins->execute([$empNo, 0]);
                    if ($col === 'SL') $this->pdo->prepare("UPDATE leavecredits SET SL = 1 WHERE EmpNo = ?")->execute([$empNo]);
                    if ($col === 'CL') $this->pdo->prepare("UPDATE leavecredits SET CL = 1 WHERE EmpNo = ?")->execute([$empNo]);
                    if ($col === 'SPL') $this->pdo->prepare("UPDATE leavecredits SET SPL = 1 WHERE EmpNo = ?")->execute([$empNo]);
                    if ($col === 'CTO') $this->pdo->prepare("UPDATE leavecredits SET CTO = 1 WHERE EmpNo = ?")->execute([$empNo]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            error_log('cancelLeaveDate error: ' . $e->getMessage());
            return false;
        }
    }
}
