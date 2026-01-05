<?php
	session_start();
	require_once('../includes/db_config.php');

	if($_SESSION['Dept'] == "City Human Resource Management Department") //APPROVAL
	{
		$approve = $pdo->query("UPDATE filedleave SET Remarks = 'APPROVED' WHERE LeaveID = '". $_GET["id"] ."'");
			
		if(!($approve))
		{
			echo "Error: ". $pdo->errorInfo();
		}
		else
		{
			$approved = $pdo->query("UPDATE approvingdates SET Approved = '". date("Y-m-d") ."' WHERE LeaveID = '". $_GET["id"] ."'");
			
			if(!($approved))
			{
				echo "Error: ". $pdo->errorInfo();
			}
			else
			{			
				$LeaveInfo = $pdo->query("SELECT * FROM filedleave WHERE LeaveID = '". $_GET["id"] ."'");
				$Leave = $LeaveInfo->fetch();
				
				$Leavecred = $pdo->query("SELECT * FROM leavecredits WHERE EmpNo = '". $Leave["EmpNo"] ."'");
				$Cred = $Leavecred->fetch();
				
				if($Leave['LeaveType'] == "Vacation Leave")
					$Ltype = "VL";
				else if($Leave['LeaveType'] == "Sick Leave")
					$Ltype = "SL";
				else if($Leave['LeaveType'] == "Compassionate Leave")
					$Ltype = "CL";
				else if($Leave['LeaveType'] == "Compensatory Time Off Leave")
					$Ltype = "CTO";
				else if($Leave['LeaveType'] == "Compassionate Time Off Leave")
					$Ltype = "CL";
				else if($Leave['LeaveType'] == "Special Purpose Leave")
					$Ltype = "SPL";
				else if(($Leave['LeaveType'] == "Others") || ($Leave['LeaveType'] == "Others - For Disapproval"))
					$Ltype = "Others";
				
				if ($Ltype != "Others")
				{
					$remaining = $Cred[$Ltype] - $Leave['NumDays'];
					$difcredits = $pdo->query("UPDATE leavecredits SET " . $Ltype . " = '". $remaining ."' WHERE EmpNo = '". $Leave["EmpNo"] ."'");
				}
				
				if((!($LeaveInfo))||(!($Leavecred)))
				{
					echo "Error: ". $pdo->errorInfo();
				}
				else
				{
					//==========================================================
					$LeaveInfor = $pdo->query("SELECT Lname, Fname, EMail FROM i WHERE EmpNo = '". $Leave["EmpNo"] ."'");
					$Leaver = $LeaveInfo->fetch();

					$leave = $Leave['LeaveType'];
					$deyt = date("m/d", strtotime($Leave['DateFrom'])) ." - ". date("m/d/Y", strtotime($Leave['DateTo']));
					$message = "Your $leave application on $deyt was approved. Thank you.";

					// $to_number = $Leaver['MobileNo'];
	    			// $content = $message;

	    			// $contact = $project->sendMessage(array(
			        //     'to_number' => $to_number,
			        //     'content' => $content
			        // ));
				
					echo "<script type='text/javascript'>alert('Success! Thank you for your Approval.');</script>";
					header("Refresh: 0; url=../pages/LeaveApp.php");
				}
			}
		}
	}
	else if($_SESSION['Dept'] != "Office of the Mayor") //FOR APPROVAL
	{
		$approve = $pdo->query("UPDATE filedleave SET Remarks = 'FOR APPROVAL' WHERE LeaveID = '". $_GET["id"] ."'");
			
		if(!($approve))
		{
			echo "Error: ". $pdo->errorInfo();
		}
		else
		{
			//$recommended = $pdo->query("insert into approvingdates (LeaveID, Recommended) values ('". $_GET["id"] ."', '". date("Y-m-d") ."')");
			$recommended = $pdo->query("UPDATE approvingdates SET Recommended = '". date("Y-m-d") ."' WHERE LeaveID = '". $_GET["id"] ."'");
			
			if(!($recommended))
			{
				echo "Error: ". $pdo->errorInfo();
			}
			else
			{
				$LeaveInfo = $pdo->query("SELECT * FROM filedleave WHERE LeaveID = '". $_GET["id"] ."'");
				$Leave = $LeaveInfo->fetch();
				
				$LeaveInfor = $pdo->query("SELECT Lname, Fname, Email FROM i WHERE EmpNo = '". $Leave["EmpNo"] ."'");
				$Leaver = $LeaveInfor->fetch();
				$name = $Leaver['Lname'] . ", " . $Leaver['Fname'];

				$leave = $Leave['LeaveType'];
				$deyt = date("m/d", strtotime($Leave['DateFrom'])) ." - ". date("m/d/Y", strtotime($Leave['DateTo']));
				$message = "$name filed $leave on $deyt for your approval. Thank you.";

				$to_number = "09751815825";
    			$content = $message;

    			// $contact = $project->sendMessage(array(
		        //     'to_number' => $to_number,
		        //     'content' => $content
		        // ));

				echo "<script type='text/javascript'>alert('Success! Thank you for your Approval.');</script>";
				header("Refresh: 0; url=../pages/LeaveApp.php");
			}
		}
	}
	else //RECOMMENDATION
	{
		$approve = $pdo->query("UPDATE filedleave SET Remarks = 'FOR APPROVAL' WHERE LeaveID = '". $_GET["id"] ."'");
			
		if(!($approve))
		{
			echo "Error: ". $pdo->errorInfo();
		}
		else
		{
			//$recommended = $pdo->query("insert into approvingdates (LeaveID, Recommended) values ('". $_GET["id"] ."', '". date("Y-m-d") ."')");
			$recommended = $pdo->query("UPDATE approvingdates SET Recommended = '". date("Y-m-d") ."' WHERE LeaveID = '". $_GET["id"] ."'");
			
			if(!($recommended))
			{
				echo "Error: ". $pdo->errorInfo();
			}
			else
			{
				$LeaveInfo = $pdo->query("SELECT * FROM filedleave WHERE LeaveID = '". $_GET["id"] ."'");
				$Leave = $LeaveInfo->fetch();
				
				$LeaveInfor = $pdo->query("SELECT Lname, Fname, Email FROM i WHERE EmpNo = '". $Leave["EmpNo"] ."'");
				$Leaver = $LeaveInfor->fetch();
				$name = $Leaver['Lname'] . ", " . $Leaver['Fname'];

				$leave = $Leave['LeaveType'];
				$deyt = date("m/d", strtotime($Leave['DateFrom'])) ." - ". date("m/d/Y", strtotime($Leave['DateTo']));
				$message = "$name filed $leave on $deyt for your approval. Thank you.";

				$to_number = "09751815825";
    			$content = $message;

    			$contact = $project->sendMessage(array(
		            'to_number' => $to_number,
		            'content' => $content
		        ));

				echo "<script type='text/javascript'>alert('Success! Thank you for your Recoomendation.');</script>";
				header("Refresh: 0; url=../pages/LeaveApp.php");
			}
		}
	}
?>