<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';
require_once '../../services/LeaveService.php';
require_once '../../controllers/LeaveController.php';

header('Content-Type: application/json');

try {
    $empNo = $_POST['empNo'] ?? null;
    $dateFrom = $_POST['date_from'] ?? null;
    $dateTo = $_POST['date_to'] ?? null;
    $reason = $_POST['reason'] ?? '';

    if (!$empNo || !$dateFrom || !$dateTo) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'empNo, date_from and date_to are required']);
        exit;
    }

    $df = DateTime::createFromFormat('Y-m-d', $dateFrom);
    $dt = DateTime::createFromFormat('Y-m-d', $dateTo);
    if (!$df || !$dt || $dt < $df) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid date range']);
        exit;
    }

    // Build weekday dates list
    $period = new DatePeriod($df, new DateInterval('P1D'), (clone $dt)->modify('+1 day'));
    $dates = [];
    foreach ($period as $d) {
        $w = (int)$d->format('w');
        if ($w === 0 || $w === 6) continue;
        $dates[] = $d->format('Y-m-d');
    }

    if (count($dates) === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Date range contains no weekdays']);
        exit;
    }

    // Prepare employee snapshot info (best-effort)
    $empInfo = ['EmployeeName' => '', 'Position' => '', 'Office' => '', 'SalaryGrade' => ''];
    try {
        $es = $pdo->prepare("SELECT CONCAT(Lname, ', ', Fname, ' ', COALESCE(Mname,'')) AS EmployeeName, Dept AS Office FROM i WHERE EmpNo = ? LIMIT 1");
        $es->execute([$empNo]);
        $row = $es->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $empInfo['EmployeeName'] = $row['EmployeeName'] ?? '';
            $empInfo['Office'] = $row['Office'] ?? '';
        }
        $ps = $pdo->prepare("SELECT Position, Salary FROM v WHERE EmpNo = ? AND (IndateTo = '0000-00-00' OR IndateTo >= CURDATE()) ORDER BY IndateFrom DESC LIMIT 1");
        $ps->execute([$empNo]);
        $prow = $ps->fetch(PDO::FETCH_ASSOC);
        if ($prow) {
            $empInfo['Position'] = $prow['Position'] ?? '';
            $empInfo['SalaryGrade'] = $prow['Salary'] ?? '';
        }
    } catch (Throwable $e) {
        // ignore
    }

    $repo = new LeaveRepository($pdo);

    // Create LWOP leave record for the employee
    $leaveId = $repo->createLeave(
        $empNo,
        'Leave Without Pay',
        'LWOP',
        $dateFrom,
        $dateTo,
        (float)count($dates),
        '',
        $reason,
        null,
        $empInfo,
        0.0,
        0.0,
        $dates
    );

    echo json_encode(['success' => true, 'leave_id' => $leaveId]);
    exit;

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
