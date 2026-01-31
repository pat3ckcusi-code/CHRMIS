<?php
require_once('../initialize.php');
require_once('../send_email.php');

if(isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $rejectionNote = isset($_POST['rejection_note']) ? trim($_POST['rejection_note']) : null;

    if (!empty($rejectionNote)) {
        $sql = "UPDATE eta_locator SET status = ?, RejectionNotes = ?, last_updated = NOW() WHERE id = ? AND status = 'Pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $rejectionNote, $id]);
    } else {
        $sql = "UPDATE eta_locator SET status = ?, last_updated = NOW() WHERE id = ? AND status = 'Pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $id]);
    }

    // If updated, fetch applicant info and notify them
    $appEmailSent = null;
    $deptEmailSent = null;
    $updated = ($stmt->rowCount() > 0);

    if ($updated) {
        try {
            $q = "SELECT el.*, i.Fname, i.Lname, i.EMail
                  FROM eta_locator el
                  INNER JOIN i ON el.EmpNo = i.EmpNo
                  WHERE el.id = ? LIMIT 1";
            $s = $pdo->prepare($q);
            $s->execute([$id]);
            $app = $s->fetch(PDO::FETCH_ASSOC);

            if ($app) {
                $toEmail = $app['EMail'] ?? '';
                $toName = trim(($app['Fname'] ?? '') . ' ' . ($app['Lname'] ?? '')) ?: 'Applicant';
                $applicationType = $app['application_type'] ?? 'ETA/Locator';
                // Include additional fields so applicant and dept head emails have complete details
                $meta = [
                    'destination' => $app['destination'] ?? '',
                    'travel_date' => $app['travel_date'] ?? $app['date_filed'] ?? '',
                    'business_type' => $app['business_type'] ?? '',
                    'travel_detail' => $app['travel_detail'] ?? $app['other_purpose'] ?? '',
                    'intended_departure' => $app['intended_departure'] ?? $app['departure_time'] ?? '',
                    'intended_arrival' => $app['intended_arrival'] ?? $app['arrival_time'] ?? ''
                ];

                // Determine rejection note from POST (preferred) or from fetched row
                $noteToSend = $rejectionNote ?? ($app['RejectionNotes'] ?? $app['rejection_note'] ?? null);

                if (!empty($toEmail)) {
                    $appEmailSent = sendETANotificationEmail($toEmail, $toName, $applicationType, $status, $meta, $noteToSend);
                } else {
                    $appEmailSent = false;
                }

                // Notify Department Head about this update (includes ETA/Locator details)
                try {
                    $deptEmailSent = notify_department_head_eta($pdo, $app, $status, $noteToSend);
                } catch (Throwable $e) {
                    error_log('Notify Dept Head ETA error: ' . $e->getMessage());
                    $deptEmailSent = false;
                }
            }
        } catch (Throwable $e) {
            error_log('ETA notify error: ' . $e->getMessage());
        }
    }
    echo json_encode([
        'success' => true,
        'updated' => $updated,
        'appEmailSent' => $appEmailSent,
        'deptEmailSent' => $deptEmailSent
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
