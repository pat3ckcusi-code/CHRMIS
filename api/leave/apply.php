<?php
require_once '../../includes/db_config.php';
require_once '../../includes/auth.php';
require_once '../../repositories/LeaveRepository.php';
require_once '../../services/LeaveService.php';
require_once '../../controllers/LeaveController.php';

header('Content-Type: application/json');

// Basic input
$leaveType = $_POST['leave_type'] ?? null;
$dateFrom = $_POST['date_from'] ?? null;
$dateTo = $_POST['date_to'] ?? null;
$reason = $_POST['reason'] ?? null;
$dates = $_POST['dates'] ?? null; // expected as array of YYYY-MM-DD strings

if (!$leaveType) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required field: leave_type']);
    exit;
}

// If multi-dates were submitted, require at least one; otherwise require date_from/date_to
if (!empty($dates)) {
    if (!is_array($dates) || count($dates) === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'dates must be a non-empty array of YYYY-MM-DD strings']);
        exit;
    }
} else {
    if (!$dateFrom || !$dateTo) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: date_from, date_to']);
        exit;
    }
}

// Normalize dates
$df = $dateFrom ? DateTime::createFromFormat('Y-m-d', $dateFrom) : null;
$dt = $dateTo ? DateTime::createFromFormat('Y-m-d', $dateTo) : null;
$today = new DateTime();
$today->setTime(0,0,0);

if (empty($dates)) {
    if (!$df || !$dt) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD.']);
        exit;
    }
}

$map = [
    'Vacation' => 'VL',
    'Sick' => 'SL',
    'Maternity' => 'ML',
    'Paternity' => 'PL',
    'Adoption' => 'AD',
    'Solo Parent' => 'SPL',
    'VAWC' => 'VAWC',
    'Gynecological' => 'GY',
    'Emergency' => 'EM',
    'Special Privilege' => 'SPP',
    'Study' => 'ST',
    'LWOP' => 'LWOP'
];

$leaveCode = $map[$leaveType] ?? strtoupper(substr(preg_replace('/\s+/', '', $leaveType), 0, 3));

// Only apply 'Date From cannot be in the past' for Vacation Leave (VL)
// When using multi-date selection, each date is validated later — skip the early check.
if (empty($dates) && $leaveCode === 'VL' && $df < $today) {
    http_response_code(400);
    echo json_encode(['error' => 'Date From cannot be in the past for Vacation Leave.']);
    exit;
}


// If multi-dates submitted, validate each; otherwise build $dates from range
if (!empty($dates)) {
    $validated = [];

    // Check for holiday table existence
    $holidayTableExists = false;
    try {
        $chk = $pdo->prepare("SELECT COUNT(*) as c FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'holidays'");
        $chk->execute();
        $row = $chk->fetch(PDO::FETCH_ASSOC);
        if ($row && (int)$row['c'] > 0) $holidayTableExists = true;
    } catch (Throwable $e) {
        $holidayTableExists = false;
    }

    foreach ($dates as $d) {
        $d = trim($d);
        $dtm = DateTime::createFromFormat('Y-m-d', $d);
        if (!$dtm) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid date in dates array', 'bad_date' => $d]);
            exit;
        }
        $w = (int)$dtm->format('w');
        if ($w === 0 || $w === 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Weekends are not allowed. Please select weekdays only.', 'bad_date' => $d]);
            exit;
        }
        if ($leaveCode === 'VL' && $dtm < $today) {
            http_response_code(400);
            echo json_encode(['error' => 'Vacation leave dates cannot be in the past.', 'bad_date' => $d]);
            exit;
        }

        if ($holidayTableExists) {
            try {
                $hstmt = $pdo->prepare("SELECT 1 FROM holidays WHERE HolidayDate = ? LIMIT 1");
                $hstmt->execute([$d]);
                if ($hstmt->fetch()) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Selected date falls on a holiday and cannot be filed.', 'bad_date' => $d]);
                    exit;
                }
            } catch (Throwable $e) {
                // ignore holiday check failures (schema may differ)
            }
        }

        $validated[$dtm->format('Y-m-d')] = true;
    }

    $dates = array_keys($validated);
    sort($dates);
    $totalDays = count($dates);
    $dateFrom = $dates[0];
    $dateTo = $dates[count($dates)-1];

} else {
    if ($dt < $df) {
        http_response_code(400);
        echo json_encode(['error' => 'Date To cannot be before Date From.']);
        exit;
    }

    // Collect all dates in range and validate weekends
    $period = new DatePeriod($df, new DateInterval('P1D'), (clone $dt)->modify('+1 day'));
    $dates = [];
    foreach ($period as $d) {
        $w = (int)$d->format('w'); // 0=Sun,6=Sat
        if ($w === 0 || $w === 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Weekends are not allowed. Please select weekdays only.', 'bad_date' => $d->format('Y-m-d')]);
            exit;
        }
        $dates[] = $d->format('Y-m-d');
    }

    $totalDays = count($dates); // only weekdays
}

$map = [
    'Vacation' => 'VL',
    'Sick' => 'SL',
    'Maternity' => 'ML',
    'Paternity' => 'PL',
    'Adoption' => 'AD',
    'Solo Parent' => 'SPL',
    'VAWC' => 'VAWC',
    'Gynecological' => 'GY',
    'Emergency' => 'EM',
    'Special Privilege' => 'SPP',
    'Study' => 'ST',
    'LWOP' => 'LWOP'
];

$leaveCode = $map[$leaveType] ?? strtoupper(substr(preg_replace('/\s+/', '', $leaveType), 0, 3));

$repo = new LeaveRepository($pdo);
// get snapshots of current credits
$cred = $pdo->prepare("SELECT COALESCE(VL,0) AS VL, COALESCE(SL,0) AS SL FROM leavecredits WHERE EmpNo = ? LIMIT 1");
$cred->execute([currentUserId()]);
$cc = $cred->fetch(PDO::FETCH_ASSOC) ?: ['VL' => 0, 'SL' => 0];

$vacSnap = (float)$cc['VL'];
$sickSnap = (float)$cc['SL'];

// If requested leave exceeds available credits (for VL or SL), reject early
if ($leaveCode === 'VL' && $totalDays > $vacSnap) {
    http_response_code(400);
    echo json_encode(['error' => 'Vacation leave days requested exceed available VL credits.']);
    exit;
}
// For Sick Leave, if not enough SL credits, allow if VL credits are available, and mark for VL deduction
$deductFromVL = false;
if ($leaveCode === 'SL' && $totalDays > $sickSnap) {
    if ($totalDays <= $vacSnap) {
        // Allow application, but mark for VL deduction
        $deductFromVL = true;
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Sick leave days requested exceed available SL credits and fallback VL credits.']);
        exit;
    }
}

// Save uploaded document if present
$documentFilename = null;
if (!empty($_FILES['document']['tmp_name'])) {
    $up = $_FILES['document'];
    $ext = pathinfo($up['name'], PATHINFO_EXTENSION);
    $safe = sprintf('%s_%s.%s', currentUserId(), time(), $ext);
    $destDir = __DIR__ . '/../../uploads';
    if (!is_dir($destDir)) mkdir($destDir, 0755, true);
    if (move_uploaded_file($up['tmp_name'], $destDir . '/' . $safe)) {
        $documentFilename = $safe;
    }
}

// Employee info snapshot (best-effort)
$empInfo = ['EmployeeName' => '', 'Position' => '', 'Office' => '', 'SalaryGrade' => ''];
try {
    // Get basic employee data (including Dept -> Office)
    $empStmt = $pdo->prepare("SELECT CONCAT(Lname, ', ', Fname, ' ', COALESCE(Mname,'')) AS EmployeeName, Dept AS Office FROM i WHERE EmpNo = ? LIMIT 1");
    $empStmt->execute([currentUserId()]);
    $row = $empStmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $empInfo['EmployeeName'] = $row['EmployeeName'] ?? '';
        $empInfo['Office'] = $row['Office'] ?? '';
    }

    // Fetch latest active record from `v` table for Position and Salary (Salary -> SalaryGrade)
    $posStmt = $pdo->prepare("SELECT Position, Salary FROM v WHERE EmpNo = ? AND (IndateTo = '0000-00-00' OR IndateTo >= CURDATE()) ORDER BY IndateFrom DESC LIMIT 1");
    $posStmt->execute([currentUserId()]);
    $posRow = $posStmt->fetch(PDO::FETCH_ASSOC);
    if ($posRow) {
        $empInfo['Position'] = $posRow['Position'] ?? '';
        $empInfo['SalaryGrade'] = $posRow['Salary'] ?? '';
    }
} catch (Throwable $e) {
    
}

// Create leave record
$leaveId = $repo->createLeave(
    currentUserId(),
    $leaveType,
    $leaveCode,
    $dateFrom,
    $dateTo,
    (float)$totalDays,
    $deductFromVL ? 'VL' : '', // use this field to indicate deduction from VL if needed
    $reason ?: '',
    $documentFilename,
    $empInfo,
    $vacSnap,
    $sickSnap,
    $dates
);

if ($leaveId === false) {
    // Attempt to fetch recent server log lines for quick debugging if available (non-sensitive, local dev)
    $logPath = __DIR__ . '/../../logs/leave_errors.log';
    $debug = null;
    if (is_readable($logPath)) {
        $lines = array_slice(file($logPath), -20);
        $debug = implode('', $lines);
    }
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create leave. Check server logs for details.', 'debug' => $debug]);
    exit;
}

echo json_encode(['success' => true, 'leave_id' => $leaveId, 'total_days' => $totalDays]);
exit;