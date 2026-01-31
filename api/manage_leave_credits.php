<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/auth.php';

try {
    $empNo = $_POST['empNo'] ?? null;
    $leaveType = strtoupper(trim($_POST['leaveType'] ?? ''));
    $deduction = isset($_POST['deduction']) ? floatval($_POST['deduction']) : 0.0;
    $tardiness = isset($_POST['tardiness']) ? (int)$_POST['tardiness'] : 0;
    $undertime = isset($_POST['undertime']) ? (int)$_POST['undertime'] : 0;

    if (!$empNo) throw new RuntimeException('Missing employee identifier');
    $allowed = ['VL','SL','CL','SPL','CTO'];
    if (!in_array($leaveType, $allowed, true)) throw new RuntimeException('Invalid leave type');
    if ($deduction <= 0) throw new RuntimeException('Deduction must be greater than zero');

    // Begin transaction
    $pdo->beginTransaction();

    // Lock row for update
    $stmt = $pdo->prepare("SELECT EmpNo, VL, SL, CL, SPL, CTO FROM leavecredits WHERE EmpNo = ? LIMIT 1 FOR UPDATE");
    $stmt->execute([$empNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'No leave credits record found for this employee.']);
        exit;
    }

    $col = $leaveType; // column matches allowed codes
    $current = isset($row[$col]) ? floatval($row[$col]) : 0.0;
    $new = max(0.0, $current - $deduction);

    $upd = $pdo->prepare("UPDATE leavecredits SET {$col} = ? WHERE EmpNo = ?");
    $ok = $upd->execute([$new, $empNo]);
    if (!$ok) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'Failed to update leave credits']);
        exit;
    }

    // Optionally insert audit log if table exists (best-effort)
    try {
        $pdo->prepare("INSERT INTO leavecredits_audit (EmpNo, ChangedBy, LeaveType, OldValue, NewValue, TardinessMinutes, UndertimeMinutes, ChangedAt) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())")
            ->execute([$empNo, currentUserId(), $col, $current, $new, $tardiness, $undertime]);
    } catch (Throwable $e) {
        // ignore if audit table does not exist
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'new_balance' => $new]);
    exit;

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
