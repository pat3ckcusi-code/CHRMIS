<?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Detect AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Basic validation for required fields
$empNo = isset($_POST['userID']) ? trim($_POST['userID']) : '';
$level = isset($_POST['level']) ? trim($_POST['level']) : '';
$schoolName = isset($_POST['schoolName']) ? trim($_POST['schoolName']) : '';
$course = isset($_POST['degree']) ? trim($_POST['degree']) : '';

if (empty($empNo) || empty($level) || empty($schoolName) || empty($course)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Redirect back with error for normal submits
    $ref = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    $sep = (strpos($ref, '?') === false) ? '?' : '&';
    header('Location: ' . $ref . $sep . 'edu=missing');
    exit;
}

$periodFrom = isset($_POST['periodFrom']) ? trim($_POST['periodFrom']) : '';
$periodTo   = isset($_POST['periodTo']) ? trim($_POST['periodTo']) : '';
$units      = isset($_POST['units']) ? trim($_POST['units']) : '';
$yearGrad   = isset($_POST['yearGraduated']) ? trim($_POST['yearGraduated']) : '';
$honors     = isset($_POST['honors']) ? trim($_POST['honors']) : '';

try {
    $sql = "INSERT INTO iii (EmpNo, `Level`, SchoolName, Course, PeriodFrom, PeriodTo, Units, YearGrad, Honors)
            VALUES (:EmpNo, :Level, :SchoolName, :Course, :PeriodFrom, :PeriodTo, :Units, :YearGrad, :Honors)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':EmpNo' => $empNo,
        ':Level' => $level,
        ':SchoolName' => $schoolName,
        ':Course' => $course,
        ':PeriodFrom' => $periodFrom,
        ':PeriodTo' => $periodTo,
        ':Units' => $units,
        ':YearGrad' => $yearGrad,
        ':Honors' => $honors
    ]);

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Educational background saved']);
        exit;
    }

    $ref = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    $sep = (strpos($ref, '?') === false) ? '?' : '&';
    header('Location: ' . $ref . $sep . 'edu=success');
    exit;

} catch (Exception $e) {
    // Log error if available and redirect back with error flag
    error_log('add_education error: ' . $e->getMessage());
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Server error']);
        exit;
    }

    $ref = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    $sep = (strpos($ref, '?') === false) ? '?' : '&';
    header('Location: ' . $ref . $sep . 'edu=error');
    exit;
}

?>
