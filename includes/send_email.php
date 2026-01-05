<?php
require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendDocumentReadyEmail(
    $toEmail,
    $toName,
    $documentType,
    $subject = null,
    $body = null
) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.lgucalapan.ph';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no-reply@lgucalapan.ph';
        $mail->Password   = 'password'; // use ENV later
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMD');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject ?? 'Document Request Update';
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

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
    $status
) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.lgucalapan.ph';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'no-reply@lgucalapan.ph';
        $mail->Password   = 'password'; // use ENV later
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@lgucalapan.ph', 'CHRMIS');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);

        if ($status === 'Approved') {
            $mail->Subject = 'Leave Application Approved';
            $body = "<p>Dear $toName,</p>
                     <p>Your leave application has been <strong>APPROVED</strong>.</p>
                     <p><strong>Leave Details:</strong></p>
                     <ul>
                        <li>Leave Type: {$leaveType}</li>
                        <li>From: " . date("M d, Y", strtotime($dateFrom)) . "</li>
                        <li>To: " . date("M d, Y", strtotime($dateTo)) . "</li>
                     </ul>
                     <p>Please ensure to coordinate with your supervisor regarding your absence.</p>
                     <br><p>City Human Resource Office Department</p>";
        } else {
            $mail->Subject = 'Leave Application Rejected';
            $body = "<p>Dear $toName,</p>
                     <p>Your leave application has been <strong>REJECTED</strong>.</p>
                     <p><strong>Leave Details:</strong></p>
                     <ul>
                        <li>Leave Type: {$leaveType}</li>
                        <li>From: " . date("M d, Y", strtotime($dateFrom)) . "</li>
                        <li>To: " . date("M d, Y", strtotime($dateTo)) . "</li>
                     </ul>
                     <p>Please contact the HR office for more information.</p>
                     <br><p>City Human Resource Office Department</p>";
        }

        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

