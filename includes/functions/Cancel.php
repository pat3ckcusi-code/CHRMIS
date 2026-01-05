<?php
session_start();
require_once('../db_config.php');

function showAlert($title, $message, $icon = 'info') {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Cancel Leave</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$title',
                text: '$message',
                icon: '$icon',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '" . $_SERVER['HTTP_REFERER'] . "';
            });
        });
        </script>
    </body>
    </html>";
    exit;
}

if(!($_SESSION['Access'] == 'Admin'))
{
    $cancelLeave = $pdo->query("UPDATE filedleave SET Remarks = 'CANCELLED' WHERE LeaveID = '". $_GET['id'] ."'");

    if(!$cancelLeave) {
        showAlert('Database Error', 'Failed to cancel leave.', 'error');
    } else {
        showAlert('Success', 'You have successfully cancelled the leave.', 'success');
    }
}
else
{
    if(isset($_POST['btnCancel']))
    {
        $cancelLeave = $pdo->query("SELECT * FROM filedleave WHERE LeaveID = '". $_GET['id'] ."'");
        $cancelrow = $cancelLeave->fetch();
        
        $days = $_POST['numdays'];
        if ($days <= $cancelrow['NumDays'])
        {
            $newNumDays = $cancelrow['NumDays'] - $days;
            if ($_POST['rdCancel'] == "Start") {
                $newdate = date('Y-m-d', strtotime($cancelrow['DateFrom']. ' + '. $days . 'days'));
                
                if ($newNumDays == 0) {
                    $updateleave = $pdo->query("UPDATE filedleave SET Remarks = 'CANCELLED', Reason = 'Exigency of Service' WHERE LeaveID = '". $_GET['id'] ."'");
                } else {
                    $updateleave = $pdo->query("UPDATE filedleave SET DateFrom = '". $newdate ."', NumDays = '". $newNumDays ."' WHERE LeaveID = '". $_GET['id'] ."'");
                }
            } else {
                $newdate = date('Y-m-d', strtotime($cancelrow['DateTo']. ' - '. $days . 'days'));
                
                if ($newNumDays == 0) {
                    $updateleave = $pdo->query("UPDATE filedleave SET Remarks = 'CANCELLED', Reason = 'Exigency of Service' WHERE LeaveID = '". $_GET['id'] ."'");
                } else {
                    $updateleave = $pdo->query("UPDATE filedleave SET DateFrom = '". $newdate ."', NumDays = '". $newNumDays ."' WHERE LeaveID = '". $_GET['id'] ."'");
                }
            }

            if(!$updateleave) {
                showAlert('Database Error', 'Something went wrong while updating leave.', 'error');
            } else {
                showAlert('Success', 'You have successfully cancelled the leave.', 'success');
            }
        }
        else
        {
            showAlert('Invalid Input', 'Invalid Number of Days Inputted!', 'warning');
        }
    }
}
