<?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');
session_start();
include("dbcon.php");
$Emmp=$conn->query("select * from student_tbl");
$journal1 = $Emmp->fetch();
$journalcount = $Emmp->rowCount();

if ($journalcount == 0)
{
	echo "<script type='text/javascript'>alert('No record exist!');</script>";
	header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
}
else
{
	/**
	 * PHPExcel
	 *
	 * Copyright (C) 2006 - 2014 PHPExcel
	 *
	 * This library is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU Lesser General Public
	 * License as published by the Free Software Foundation; either
	 * version 2.1 of the License, or (at your option) any later version.
	 *
	 * This library is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	 * Lesser General Public License for more details.
	 *
	 * You should have received a copy of the GNU Lesser General Public
	 * License along with this library; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
	 *
	 * @category   PHPExcel
	 * @package    PHPExcel
	 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
	 * @version    1.8.0, 2014-03-02
	 */

	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	date_default_timezone_set('Europe/London');

	/** PHPExcel_IOFactory */
	include 'Classes/PHPExcel.php'; include 'Classes/IOFactory.php';
	//require_once dirname(__FILE__) . '\Classes\PHPExcel\IOFactory.php';

	echo date('H:i:s') , " Load from Excel2007 template" , EOL;
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load("templates/LEAVE_JOURNAL.xlsx");
	
	$objPHPExcel->getActiveSheet()->setCellValue('A3', "Name:      " . $journal1['Lname'] . ", " . $journal1['Fname'] . " " . substr($journal1['Mname'], 0, 1) . ".")
								  ->setCellValue('A4', "Position:    " . $journal1['Designation'])
								  ->setCellValue('D3', "Civil Status:  " . $journal1['Civil'])
								  ->setCellValue('D4', "Entrance to Duty: " . date('F d, Y',strtotime($journal1['ServiceFrom'])))
								  ->setCellValue('D5', "Unit:                  " . $journal1['Dept'])
								  ->setCellValue('I4', "GSIS Policy No: " . $journal1['GSIS'])
								  ->setCellValue('I5', "TIN No. :           " . $journal1['Tin']);

	$a = 1;
	$month = 1;
	while($month <= 12)
	{	
		$AppLeave=$conn->query("select * from filedleave where EmpNo = '" . $_GET["id"] . "' and Remarks = 'APPROVED' and Month(DateFrom) = '" . $month . "'");
		$numdays=$conn->query("select sum(NumDays) as numdays from filedleave where EmpNo = '" . $_GET["id"] . "' and Remarks = 'APPROVED' and Month(DateFrom) = '" . $month . "'");
		$n = $numdays->fetch();
		while($TypeLeave = $AppLeave->fetch())
		{
			$Let = 10 + $a;
			
			if($TypeLeave['LeaveType'] == "Vacation Leave")
				$Ltype = "VL";
			else if($TypeLeave['LeaveType'] == "Sick Leave")
				$Ltype = "SL";
			else if($TypeLeave['LeaveType'] == "Compassionate Leave")
				$Ltype = "CL";
			else if($TypeLeave['LeaveType'] == "Compensatory Time Off Leave")
				$Ltype = "CTO";
			else if($TypeLeave['LeaveType'] == "Special Purpose Leave")
				$Ltype = "SPL";
			else if(($TypeLeave['LeaveType'] == "Others") || ($Leave['LeaveType'] == "Others - For Disapproval"))
				$Ltype = "Others";
		
			$remarks = $remarks . $Ltype . "(" . date('d',strtotime($TypeLeave['DateFrom'])) . "-" . date('d',strtotime($TypeLeave['DateTo'])) . ")";
			$objPHPExcel->getActiveSheet()->setCellValue('L' . $Let, $remarks);
			if($Ltype == "SL")
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $Let, $n['numdays']);
			else if($Ltype == "VL")
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $Let, $n['numdays']);
		}
		$a = $a + 1;
		$month = $month + 1;
	}
								  
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;


	// Echo memory peak usage
	echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

	// Echo done
	echo date('H:i:s') , " Done writing file" , EOL;
	echo 'File has been created in ' , getcwd() , EOL;

	header("location: LEAVE_JOURNAL.xlsx");
}
