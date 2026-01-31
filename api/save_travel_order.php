<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/send_email.php';

$raw = file_get_contents('php://input');
if (!$raw) {
    echo json_encode(['success' => false, 'message' => 'No input received']);
    exit;
}

$data = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$employees = $data['employees'] ?? [];
$departure = $data['departure_date'] ?? '';
$return = $data['return_date'] ?? '';
$destination = trim($data['destination'] ?? '');
$purpose = trim($data['purpose'] ?? '');
$per_diem = trim($data['per_diem'] ?? '');
$appropriation = trim($data['appropriation'] ?? '');
$remarks = trim($data['remarks'] ?? '');

if (!is_array($employees) || empty($employees) || !$departure || !$return || !$destination || !$purpose) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Prepare values
$created_by = $_SESSION['EmpID'] ?? null;
$created_by = is_numeric($created_by) ? (int)$created_by : null;
// Use provided recommender if sent, otherwise fall back to the creator (so column is not NULL)
$recommender = isset($data['recommender']) ? ($data['recommender'] === '' ? null : $data['recommender']) : null;
$recommender = is_numeric($recommender) ? (int)$recommender : ($created_by ?? 0);
$status = 'Pending Recommendation';

// Generate a travel order number
$travel_order_num = 'TO-' . date('YmdHis') . '-' . random_int(1000, 9999);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO travel_orders (travel_order_num, purpose, destination, start_date, end_date, remarks, recommender, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $travel_order_num,
        $purpose,
        $destination,
        $departure,
        $return,
        $remarks,
        $recommender,
        $created_by,
        $status
    ]);

    $travel_order_id = (int)$pdo->lastInsertId();

    $insEmp = $pdo->prepare("INSERT INTO travel_order_employees (travel_order_id, emp_no) VALUES (?, ?)");
    foreach ($employees as $empNo) {
        // sanitize empNo as string
        $empNo = trim((string)$empNo);
        if ($empNo === '') continue;
        $insEmp->execute([$travel_order_id, $empNo]);
    }

    // Optional: write a server-side debug log
    @file_put_contents(__DIR__ . '/../logs/travel_orders_log.json', json_encode([
        'travel_order_id' => $travel_order_id,
        'travel_order_num' => $travel_order_num,
        'created_by' => $created_by,
        'received' => $data
    ]) . PHP_EOL, FILE_APPEND | LOCK_EX);

    $pdo->commit();

        // Optional: write a server-side debug log
        @file_put_contents(__DIR__ . '/../logs/travel_orders_log.json', json_encode([
            'travel_order_id' => $travel_order_id,
            'travel_order_num' => $travel_order_num,
            'created_by' => $created_by,
            'received' => $data
        ]) . PHP_EOL, FILE_APPEND | LOCK_EX);

        // Notify Department Head(s)
        $deptEmailSent = false;
        $deptName = $_SESSION['Dept'] ?? null;
        $creatorName = $_SESSION['EmpName'] ?? $_SESSION['UserName'] ?? 'Staff';
        $employeesCount = count($employees);

        if (!empty($deptName)) {
            try {
                $hstmt = $pdo->prepare("SELECT AcctName, Email FROM adminusers WHERE Dept = ? AND access_level = 'Department Head'");
                $hstmt->execute([$deptName]);
                $heads = $hstmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($heads as $head) {
                    if (empty($head['Email'])) continue;
                    $toEmail = $head['Email'];
                    $toName = $head['AcctName'] ?: $toEmail;

                    try {
                        $mail = getMailer();
                        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
                        $mail->addAddress($toEmail, $toName);
                        $mail->isHTML(true);
                        $mail->Subject = 'Notification: Travel Order ' . $travel_order_num;

                        $body = "<p>Dear " . htmlspecialchars($toName) . ",</p>";
                        $body .= "<p>A new Travel Order <strong>" . htmlspecialchars($travel_order_num) . "</strong> has been submitted by <strong>" . htmlspecialchars($creatorName) . "</strong> for <strong>" . intval($employeesCount) . "</strong> employee(s) from your department.</p>";
                        $body .= "<ul>";
                        $body .= "<li><strong>Destination:</strong> " . htmlspecialchars($destination) . "</li>";
                        $body .= "<li><strong>Departure:</strong> " . (!empty($departure) ? htmlspecialchars(date('l, M j, Y', strtotime($departure))) : '') . "</li>";
                        $body .= "<li><strong>Return:</strong> " . (!empty($return) ? htmlspecialchars(date('l, M j, Y', strtotime($return))) : '') . "</li>";
                        $body .= "<li><strong>Purpose:</strong> " . nl2br(htmlspecialchars($purpose)) . "</li>";
                        if (!empty($per_diem)) $body .= "<li><strong>Per Diem / Expenses:</strong> " . htmlspecialchars($per_diem) . "</li>";
                        if (!empty($appropriation)) $body .= "<li><strong>Appropriation:</strong> " . htmlspecialchars($appropriation) . "</li>";
                        if (!empty($remarks)) $body .= "<li><strong>Remarks:</strong> " . nl2br(htmlspecialchars($remarks)) . "</li>";
                        $body .= "</ul>";
                        $body .= "<p>Please review and take any necessary action via the CHRMIS portal.</p>";
                        $body .= "<br><p><strong>City Human Resource Office</strong></p>";

                        $mail->Body = $body;
                        $mail->AltBody = strip_tags($body);
                        $mail->send();
                        $deptEmailSent = true;
                    } catch (Exception $e) {
                        error_log('Travel Order Dept Email Error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
                    }
                }

            } catch (Exception $e) {
                error_log('Travel Order Dept Head Lookup Error: ' . $e->getMessage());
            }
        }

        echo json_encode(['success' => true, 'message' => 'Travel order saved.', 'travel_order_id' => $travel_order_id, 'travel_order_num' => $travel_order_num, 'deptEmailSent' => $deptEmailSent]);
        exit;

    echo json_encode(['success' => true, 'message' => 'Travel order saved.', 'travel_order_id' => $travel_order_id, 'travel_order_num' => $travel_order_num]);
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('Save Travel Order Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
