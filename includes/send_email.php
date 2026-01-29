<?php
require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function getMailer(): PHPMailer
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'mail.lgucalapan.ph';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no-reply@lgucalapan.ph';
    $mail->Password   = 'Adminpa55w0rd'; 
    $mail->Port       = 465;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;


    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->CharSet = 'UTF-8';

    return $mail;
}
function sendDocumentReadyEmail(
    $toEmail,
    $toName,
    $documentType,
    $subject = null,
    $body = null
) {
    try {
        $mail = getMailer();

        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMD');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject ?? 'Document Request Update';

        $mail->Body = $body ?? "
            <p>Dear {$toName},</p>
            <p>Your requested <strong>{$documentType}</strong> is now ready.</p>
            <p>Please proceed to the office for claiming.</p>
            <br>
            <p>City Human Resource Office</p>
        ";

        $mail->AltBody = strip_tags($mail->Body);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
function sendLeaveApprovalEmail(
    $toEmail,
    $toName,
    $leaveType,
    $dateFrom,
    $dateTo,
    $status,
    $leaveBalance = null,
    $rejectionNote = null    
) {
    try {
        $mail = getMailer();

        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        
        $subjects = [
            'Recommended'  => 'Leave Application Recommended',
            'Approved'     => 'Leave Application Approved',
            'Disapproved'  => 'Leave Application Disapproved'
        ];

        $mail->Subject = $subjects[$status] ?? 'Leave Application Update';

        
        $statusMessage = match ($status) {
            'Recommended' => 'has been <strong>RECOMMENDED</strong> and forwarded for final approval.',
            'Approved'    => 'has been <strong>APPROVED</strong>.',
            'Disapproved' => 'has been <strong>DISAPPROVED</strong>.',
            default       => 'status has been updated.'
        };

        //Rejection Note Section
        $rejectionHtml = '';

        if ($status === 'Disapproved' && !empty($rejectionNote)) {
            $rejectionHtml = '
                <h4 style="margin:15px 0 5px;color:#b00020;">
                    Reason for Rejection
                </h4>
                <div style="
                    padding:10px;
                    background:#fff4f4;
                    border-left:4px solid #b00020;
                    font-size:13px;
                ">
                    ' . nl2br(htmlspecialchars($rejectionNote)) . '
                </div>
                <br>
            ';
        }

        //leave balance table
        $balanceHtml = '';

        if (is_array($leaveBalance)) {
            $balanceHtml = '
                <h4 style="margin:15px 0 8px;font-family:Arial,sans-serif;color:#333;font-size:16px;">
                    Leave Balance
                </h4>

                <table cellpadding="4" cellspacing="0" 
                    style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:13px;width:auto;">
                    <thead>
                        <tr style="background-color:#f2f2f2;">
                            <th align="left" style="padding:6px 10px;border:1px solid #ccc;">Leave Type</th>
                            <th align="right" style="padding:6px 10px;border:1px solid #ccc;">Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:4px 10px;border:1px solid #ccc;">Vacation Leave (VL)</td>
                            <td align="right" style="padding:4px 10px;border:1px solid #ccc;">' . ($leaveBalance['VL'] ?? 0) . '</td>
                        </tr>
                        <tr style="background-color:#fafafa;">
                            <td style="padding:4px 10px;border:1px solid #ccc;">Sick Leave (SL)</td>
                            <td align="right" style="padding:4px 10px;border:1px solid #ccc;">' . ($leaveBalance['SL'] ?? 0) . '</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 10px;border:1px solid #ccc;">Compensatory Leave (CL)</td>
                            <td align="right" style="padding:4px 10px;border:1px solid #ccc;">' . ($leaveBalance['CL'] ?? 0) . '</td>
                        </tr>
                        <tr style="background-color:#fafafa;">
                            <td style="padding:4px 10px;border:1px solid #ccc;">Special Privilege Leave (SPL)</td>
                            <td align="right" style="padding:4px 10px;border:1px solid #ccc;">' . ($leaveBalance['SPL'] ?? 0) . '</td>
                        </tr>
                        <tr>
                            <td style="padding:4px 10px;border:1px solid #ccc;">Compensatory Time Off (CTO)</td>
                            <td align="right" style="padding:4px 10px;border:1px solid #ccc;">' . ($leaveBalance['CTO'] ?? 0) . '</td>
                        </tr>
                    </tbody>
                </table>
                <br>
            ';
        }

        $mail->Body = "
            <p>Dear {$toName},</p>

            <p>Your leave application {$statusMessage}</p>

            <ul>
                <li><strong>Leave Type:</strong> {$leaveType}</li>
                <li><strong>From:</strong> " . date('M d, Y', strtotime($dateFrom)) . "</li>
                <li><strong>To:</strong> " . date('M d, Y', strtotime($dateTo)) . "</li>
            </ul>
            {$rejectionHtml}
            {$balanceHtml}

            <p>Please coordinate with the City Human Resource Office for further instructions.</p>

            <br>
            <p><strong>City Human Resource Office</strong></p>
        ";

        $mail->AltBody = strip_tags($mail->Body);
        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

// Send notification to the applicant for ETA/Locator status updates
function sendETANotificationEmail($toEmail, $toName, $applicationType, $status, $meta = [], $rejectionNote = null)
{
    try {
        $mail = getMailer();
        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        $subjects = [
            'Pending' => 'Application Received',
            'Approved' => 'Application Approved',
            'Rejected' => 'Application Rejected'
        ];

        $mail->Subject = $subjects[$status] ?? 'Application Update';

        $body = "<p>Dear " . htmlspecialchars($toName) . ",</p>";
        $body .= "<p>Your " . htmlspecialchars($applicationType) . " application has been <strong>" . htmlspecialchars($status) . "</strong>.</p>";

        if (!empty($meta) && is_array($meta)) {
            $body .= "<ul>";
            foreach ($meta as $k => $v) {
                $body .= "<li><strong>" . htmlspecialchars($k) . ":</strong> " . htmlspecialchars((string)$v) . "</li>";
            }
            $body .= "</ul>";
        }

        if (!empty($rejectionNote)) {
            $body .= "<h4 style=\"color:#b00020;\">Reason</h4>";
            $body .= "<div style=\"padding:8px;background:#fff4f4;border-left:4px solid #b00020;\">" . nl2br(htmlspecialchars($rejectionNote)) . "</div>";
        }

        $body .= "<p>Please check the CHRMIS portal for further details.</p>";
        $body .= "<br><p><strong>City Human Resource Office</strong></p>";

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error (ETA to applicant): ' . ($mail->ErrorInfo ?? $e->getMessage()));
        return false;
    }
}

// Notify department head about ETA/Locator update. $app is the row from eta_locator JOIN i
function notify_department_head_eta(PDO $pdo, array $app, $status, $rejectionNote = null)
{
    try {
        $empNo = $app['EmpNo'] ?? null;
        if (!$empNo) return false;

        // Get employee department and full name
        $stmt = $pdo->prepare("SELECT Dept, CONCAT(COALESCE(Fname,''),' ',COALESCE(Lname,'')) AS EmpName FROM i WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([$empNo]);
        $emp = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$emp) return false;

        $dept = $emp['Dept'] ?? null;
        $employeeName = trim($app['Fname'] . ' ' . $app['Lname']) ?: ($emp['EmpName'] ?? 'Employee');

        if (empty($dept)) return false;

        // Get department head
        $h = $pdo->prepare("SELECT AcctName, Email FROM adminusers WHERE Dept = ? LIMIT 1");
        $h->execute([$dept]);
        $head = $h->fetch(PDO::FETCH_ASSOC);
        if (!$head || empty($head['Email'])) return false;

        $toEmail = $head['Email'];
        $toName = $head['AcctName'];

        $mail = getMailer();
        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        // Determine application type
        $appType = $app['application_type'] ?? ($app['application'] ?? 'Request');

        // Subject formatting per examples
        $upperEmpName = strtoupper($employeeName);
        if (strtoupper($appType) === 'ETA') {
            $mail->Subject = "Notification: ETA Request Approved for {$upperEmpName}";
        } else {
            $mail->Subject = "Notification: {$appType} Request Approved for {$upperEmpName}";
        }

        // Greeting uses comma style shown in example
        $body = "<p>Dear, " . htmlspecialchars(strtoupper($toName)) . ",</p>";
        $body .= "<p>This is to inform you that the " . htmlspecialchars($appType) . " request for <strong>" . htmlspecialchars($upperEmpName) . "</strong> has been <strong>Approved</strong>. Please find below the travel details for your reference:</p>";

        // Core details
        $body .= "<p><strong>Destination / Location:</strong> " . htmlspecialchars($app['destination'] ?? ($app['location'] ?? '')) . "</p>";

        if (!empty($app['travel_date'])) {
            // Use Y-m-d format if original appears as date, else show raw
            $body .= "<p><strong>Travel Date:</strong> " . htmlspecialchars(date('Y-m-d', strtotime($app['travel_date']))) . "</p>";
        }

        if (!empty($app['travel_detail'])) {
            $body .= "<p><strong>Travel Details:</strong> " . htmlspecialchars($app['travel_detail']) . "</p>";
        } elseif (!empty($app['other_purpose'])) {
            $body .= "<p><strong>Travel Details:</strong> " . htmlspecialchars($app['other_purpose']) . "</p>";
        }

        // Locator-specific times
        if (strtoupper($appType) === 'LOCATOR') {
            if (!empty($app['intended_departure'])) {
                $body .= "<p><strong>Time of Departure:</strong> " . date('h:i A', strtotime($app['intended_departure'])) . "</p>";
            }
            if (!empty($app['intended_arrival'])) {
                $body .= "<p><strong>Time of Arrival:</strong> " . date('h:i A', strtotime($app['intended_arrival'])) . "</p>";
            }
        }

        if (!empty($rejectionNote)) {
            $body .= "<h4 style=\"color:#b00020;\">Remarks</h4>";
            $body .= "<div style=\"padding:8px;background:#fff4f4;border-left:4px solid #b00020;\">" . nl2br(htmlspecialchars($rejectionNote)) . "</div>";
        }

        $body .= "<p>Please check the CHRMIS portal for further details and any required clarifications.</p>";
        $body .= "<br><p><strong>City Human Resource Office</strong></p>";

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log('Mailer Error (Dept Head ETA): ' . ($mail->ErrorInfo ?? $e->getMessage()));
        return false;
    }
}
// Send notification to Department Head when an application is approved (Clarification)
function sendDeptHeadApprovalNotification(PDO $pdo, $dept, $employeeName, $applicationType = '', $details = [], $approvedBy = null)
{
    try {
        $stmt = $pdo->prepare("SELECT AcctName, Email FROM adminusers WHERE Dept = ? LIMIT 1");
        $stmt->execute([$dept]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['Email'])) {
            return false;
        }

        $toEmail = $row['Email'];
        $toName = $row['AcctName'];

        $mail = getMailer();
        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        $mail->Subject = 'Clarification: Application Approved - ' . $employeeName;

        $body = "<p>Dear " . htmlspecialchars($toName) . ",</p>";
        $body .= "<p>This is to notify you that the application of <strong>" . htmlspecialchars($employeeName) . "</strong> from your department (<strong>" . htmlspecialchars($dept) . "</strong>) has been <strong>APPROVED</strong>.</p>";

        if (!empty($applicationType)) {
            $body .= "<p><strong>Application Type:</strong> " . htmlspecialchars($applicationType) . "</p>";
        }

        if (!empty($details) && is_array($details)) {
            $body .= "<h4 style=\"margin:10px 0 6px;\">Details</h4><ul>";
            foreach ($details as $k => $v) {
                $body .= "<li><strong>" . htmlspecialchars($k) . ":</strong> " . nl2br(htmlspecialchars((string)$v)) . "</li>";
            }
            $body .= "</ul>";
        }

        if (!empty($approvedBy)) {
            $body .= "<p><strong>Approved by:</strong> " . htmlspecialchars($approvedBy) . "</p>";
        }

        $body .= "<p>Please check the CHRMIS portal for further details and any required clarifications.</p>";
        $body .= "<br><p><strong>City Human Resource Office</strong></p>";

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
        return false;
    }
}





