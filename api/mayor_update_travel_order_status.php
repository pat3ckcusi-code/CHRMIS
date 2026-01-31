<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/send_email.php';

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;
$note = trim($_POST['rejection_note'] ?? '');

if (!$id || !$status) {
    echo json_encode(['success'=>false,'message'=>'Missing parameters']); exit;
}

// Only Mayor can perform here
if (($_SESSION['access_level'] ?? '') !== 'Mayor') {
    echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

try {
    // Only allow if current status is Pending Approval
    $stmt = $pdo->prepare("SELECT status FROM travel_orders WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { echo json_encode(['success'=>false,'message'=>'Travel order not found']); exit; }
    if ($row['status'] !== 'Pending Approval') {
        echo json_encode(['success'=>false,'message'=>'Only travel orders in Pending Approval can be processed by the Mayor']); exit;
    }

    if (strtolower($status) === 'rejected') {
        if (empty($note)) {
            echo json_encode(['success'=>false,'message'=>'Rejection reason is required']); exit;
        }
    }

    // Persist status; if rejection note provided and status is Rejected, store it in `rejection_note`.
    if (strtolower($status) === 'rejected') {
        // assume `rejection_note` column exists (as per DB migration)
        $u = $pdo->prepare("UPDATE travel_orders SET status = ?, rejection_note = ? WHERE id = ?");
        $u->execute([$status, $note, $id]);
    } else {
        $u = $pdo->prepare("UPDATE travel_orders SET status = ? WHERE id = ?");
        $u->execute([$status, $id]);
    }

    // fetch travel order and employees
    $tstmt = $pdo->prepare("SELECT t.* FROM travel_orders t WHERE t.id = ? LIMIT 1");
    $tstmt->execute([$id]);
    $toRow = $tstmt->fetch(PDO::FETCH_ASSOC);

    $emps = [];
    $est = $pdo->prepare("SELECT te.emp_no, CONCAT(i.Lname, ', ', i.Fname) AS name, i.EMail FROM travel_order_employees te JOIN i ON i.EmpNo = te.emp_no WHERE te.travel_order_id = ?");
    $est->execute([$id]);
    $rows = $est->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) { $emps[] = $r; }

    // notify creator and employees
    $emailsSent = false;
    try {
        $mailerErrors = [];
        $recipients = [];
        // include creator (if created_by maps to i.EmpNo)
        if (!empty($toRow['created_by'])) {
            // attempt to find email in i or adminusers
            $c = $pdo->prepare("SELECT EMail, CONCAT(Lname, ', ', Fname) AS name FROM i WHERE EmpNo = ? LIMIT 1");
            $c->execute([$toRow['created_by']]);
            $cv = $c->fetch(PDO::FETCH_ASSOC);
            if ($cv && !empty($cv['EMail'])) $recipients[] = ['email'=>$cv['EMail'],'name'=>$cv['name']];
        }
        // add employee emails
        foreach ($emps as $e) {
            if (!empty($e['EMail'])) $recipients[] = ['email'=>$e['EMail'],'name'=>$e['name']];
        }

        foreach ($recipients as $r) {
            try {
                $mail = getMailer();
                $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
                $mail->addAddress($r['email'], $r['name']);
                $mail->isHTML(true);
                $mail->Subject = 'Travel Order ' . ($toRow['travel_order_num'] ?? '') . ' - ' . $status;

                $body = "<p>Dear " . htmlspecialchars($r['name']) . ",</p>";
                $body .= "<p>The travel order <strong>" . htmlspecialchars($toRow['travel_order_num'] ?? '') . "</strong> has been <strong>" . htmlspecialchars($status) . "</strong> by the Mayor.</p>";
                if (!empty($note) && strtolower($status) === 'rejected') {
                    $body .= "<p><strong>Rejection reason:</strong> " . nl2br(htmlspecialchars($note)) . "</p>";
                }
                $body .= "<ul>";
                $body .= "<li><strong>Destination:</strong> " . htmlspecialchars($toRow['destination'] ?? '') . "</li>";
                $body .= "<li><strong>Departure:</strong> " . (!empty($toRow['start_date']) ? htmlspecialchars(date('l, M j, Y', strtotime($toRow['start_date']))) : '') . "</li>";
                $body .= "<li><strong>Return:</strong> " . (!empty($toRow['end_date']) ? htmlspecialchars(date('l, M j, Y', strtotime($toRow['end_date']))) : '') . "</li>";
                $body .= "<li><strong>Purpose:</strong> " . nl2br(htmlspecialchars($toRow['purpose'] ?? '')) . "</li>";
                $body .= "</ul>";
                $body .= "<p>Regards,<br>City Human Resource Office</p>";

                $mail->Body = $body;
                $mail->AltBody = strip_tags($body);
                $mail->send();
                $emailsSent = true;
            } catch (Exception $e) {
                error_log('Mayor travel order email error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
            }
        }
    } catch (Throwable $e) {
        error_log('Mayor notify error: ' . $e->getMessage());
    }

    echo json_encode(['success'=>true,'message'=>'Status updated','emailsSent'=>$emailsSent]); exit;

} catch (Exception $e){ error_log('mayor_update_travel_order_status error: '.$e->getMessage()); echo json_encode(['success'=>false,'message'=>'Server error']); exit; }

