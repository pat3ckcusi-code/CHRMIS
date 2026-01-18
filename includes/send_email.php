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



