<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/send_email.php';

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['success'=>false,'message'=>'Missing parameters']); exit;
}

try {
    // Only allow if current status is Pending Recommendation
    $stmt = $pdo->prepare("SELECT status FROM travel_orders WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['success'=>false,'message'=>'Travel order not found']); exit; }
    if ($row['status'] !== 'Pending Recommendation') {
        echo json_encode(['success'=>false,'message'=>'Only travel orders in Pending Recommendation can be updated/canceled']); exit;
    }

    $u = $pdo->prepare("UPDATE travel_orders SET status = ? WHERE id = ?");
    $u->execute([$status, $id]);

    // Fetch travel order details for email
    $tstmt = $pdo->prepare("SELECT t.* FROM travel_orders t WHERE t.id = ? LIMIT 1");
    $tstmt->execute([$id]);
    $toRow = $tstmt->fetch(PDO::FETCH_ASSOC);

    // Get employee names
    $emps = [];
    $est = $pdo->prepare("SELECT te.emp_no, CONCAT(i.Lname, ', ', i.Fname) AS name FROM travel_order_employees te JOIN i ON i.EmpNo = te.emp_no WHERE te.travel_order_id = ?");
    $est->execute([$id]);
    $rows = $est->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) { $emps[] = $r['name']; }

    // Notify Mayor(s)
    $mayorEmailSent = false;
    try {
        $mstmt = $pdo->prepare("SELECT AcctName, Email FROM adminusers WHERE access_level = 'Mayor'");
        $mstmt->execute();
        $mayors = $mstmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($mayors as $m) {
            if (empty($m['Email'])) continue;
            try {
                $mail = getMailer();
                $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
                $mail->addAddress($m['Email'], $m['AcctName']);
                $mail->isHTML(true);
                $mail->Subject = 'Notification: Travel Order ' . ($toRow['travel_order_num'] ?? '');

                $body = "<p>Dear " . htmlspecialchars($m['AcctName']) . ",</p>";
                $body .= "<p>The travel order <strong>" . htmlspecialchars($toRow['travel_order_num'] ?? '') . "</strong> has been <strong>" . htmlspecialchars($status) . "</strong> by the Department Head.</p>";
                $body .= "<ul>";
                $body .= "<li><strong>Destination:</strong> " . htmlspecialchars($toRow['destination'] ?? '') . "</li>";
                $body .= "<li><strong>Departure:</strong> " . (!empty($toRow['start_date']) ? htmlspecialchars(date('l, M j, Y', strtotime($toRow['start_date']))) : '') . "</li>";
                $body .= "<li><strong>Return:</strong> " . (!empty($toRow['end_date']) ? htmlspecialchars(date('l, M j, Y', strtotime($toRow['end_date']))) : '') . "</li>";
                $body .= "<li><strong>Purpose:</strong> " . nl2br(htmlspecialchars($toRow['purpose'] ?? '')) . "</li>";
                if (!empty($emps)) $body .= "<li><strong>Employees:</strong> " . htmlspecialchars(implode(', ', $emps)) . "</li>";
                $body .= "</ul>";
                $body .= "<p>Please review the travel order in the CHRMIS portal.</p>";
                $body .= "<br><p><strong>City Human Resource Office</strong></p>";

                $mail->Body = $body;
                $mail->AltBody = strip_tags($body);
                $mail->send();
                $mayorEmailSent = true;
            } catch (Exception $e) {
                error_log('Travel Order Mayor Email Error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
            }
        }
    } catch (Exception $e) {
        error_log('Travel Order Mayor Lookup Error: ' . $e->getMessage());
    }

    echo json_encode(['success'=>true,'message'=>'Status updated','mayorEmailSent'=>$mayorEmailSent]); exit;

} catch (Exception $e){ error_log('update_travel_order_status error: '.$e->getMessage()); echo json_encode(['success'=>false,'message'=>'Server error']); exit; }
