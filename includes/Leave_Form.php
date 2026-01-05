<?php
session_start();
require_once('../includes/db_config.php');
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

date_default_timezone_set('Asia/Manila');



/** PHPExcel_IOFactory */
include __DIR__ . '/../Classes/PHPExcel.php';
include __DIR__ . '/../Classes/PHPExcel/IOFactory.php';




//include 'Classes/PHPExcel.php'; include 'Classes/PHPExcel/IOFactory.php';
//require_once dirname(__FILE__) . '\Classes\PHPExcel\IOFactory.php';

// load template
echo date('H:i:s') , " Load from Excel2007 template" , EOL;
$objReader   = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load(__DIR__ . '/../templates/PDS.xlsx');


//$objPHPExcel = $objReader->load("templates/PDS.xlsx");
//$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');

//Page 1

$queryi=$pdo->query("select * from i where EmpNo = '" . $_SESSION["EmpID"] . "'");
$rowi = $queryi->fetch();

$birthDate = date('m/d/Y', strtotime($rowi['BirthDate']));

$queryii=$pdo->query("select * from ii where EmpNo = '" . $_SESSION["EmpID"] . "'");
$rowii = $queryii->fetch();

$queryiii=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "'");

$querychild=$pdo->query("select * from children where EmpNo = '" . $_SESSION["EmpID"] . "'");

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('D10', $rowi['Lname'])
							  ->setCellValue('D11', $rowi['Fname'])
							  ->setCellValue('D13', $rowi['Mname'])
							  ->setCellValue('L12', $rowi['Extension'])
							  //->setCellValue('D14', $rowi['BirthDate'])
							  ->setCellValue('D14', date('m/d/Y', strtotime($rowi['BirthDate'])))
							  ->setCellValue('D16', $rowi['PlaceBirth'])
							  ->setCellValue('D23', strval($rowi['Height']))
							  ->setCellValue('D25', strval($rowi['Weight']))
							  ->setCellValue('D26', $rowi['BloodType'])
							  ->setCellValue('D28', strval($rowi['GSIS']))
							  ->setCellValue('D30', strval($rowi['Pagibig']))
							  ->setCellValue('D32', strval($rowi['PHealth']))
							  ->setCellValue('D33', strval($rowi['SSS']))
							  ->setCellValue('D34', strval($rowi['Tin']))
							  ->setCellValue('D35', strval($rowi['AgencyEmpNo']))
							  ->setCellValue('I35', $rowi['EMail'])
							  ->setCellValue('I34', strval($rowi['MobileNo']))
							  ->setCellValue('I33', strval($rowi['TelNo']))
							  ->setCellValue('I32', strval($rowi['Perm_Zip']))
							  ->setCellValue('I30', $rowi['Perm_City'])
							  ->setCellValue('L30', $rowi['Perm_Province'])
							  ->setCellValue('L28', $rowi['Perm_Brgy'])
							  ->setCellValue('I28', $rowi['Perm_Subd'])
							  ->setCellValue('I25', strval($rowi['Perm_House']))
							  ->setCellValue('L26', $rowi['Perm_Street'])
							  ->setCellValue('I25', strval($rowi['Zip']))
							  ->setCellValue('I23', $rowi['City'])
							  ->setCellValue('L23', $rowi['Province'])
							  ->setCellValue('L20', $rowi['Brgy'])
							  ->setCellValue('I20', $rowi['Subd'])
							  ->setCellValue('I18', strval($rowi['HouseNo']))
							  ->setCellValue('L18', $rowi['Street'])
							  // Table II
							  ->setCellValue('D37', $rowii['SLname'])
							  ->setCellValue('D38', $rowii['SFname'])
							  ->setCellValue('G39', $rowii['SExtension'])
							  ->setCellValue('D40', $rowii['SMname'])
							  ->setCellValue('D41', $rowii['SOccupation'])
							  ->setCellValue('D42', $rowii['EmpBusName'])
							  ->setCellValue('D43', $rowii['BussAdd'])
							  ->setCellValue('D44', strval($rowii['TelNo']))
							  ->setCellValue('D45', $rowii['FLname'])
							  ->setCellValue('D46', $rowii['FFname'])
							  ->setCellValue('G47', $rowii['FExtension'])
							  ->setCellValue('D48', $rowii['FMname'])
							  ->setCellValue('D49', $rowii['MMaiden'])
							  ->setCellValue('D50', $rowii['MLname'])
							  ->setCellValue('D51', $rowii['MFname'])
							  ->setCellValue('D52', $rowii['MMname']);
							  
							  // Children
							$a = 1;
							while($child = $querychild->fetch())
							{	
								$Let = 37 + $a;
								if ($Let < 51)
								{
									$objPHPExcel->getActiveSheet()->setCellValue('I' . $Let, $child['ChildName'])
																  ->setCellValue('M' . $Let, $child['ChildBirth']);
									if($Let != 38 && $Let != 46)
										$a = $a + 1;
									else
										$a = $a + 2;
								}
							}
								
							  // Table III
							$b = 1;
							while($rowiii = $queryiii->fetch())
							{	
								$Letter = 56 + $b;
								$objPHPExcel->getActiveSheet()->setCellValue('D' . $Letter, $rowiii['SchoolName'])
															  ->setCellValue('G' . $Letter, $rowiii['Course'])
															  ->setCellValue('J' . $Letter, $rowiii['PeriodFrom'])
															  ->setCellValue('K' . $Letter, $rowiii['PeriodTo'])
															  ->setCellValue('L' . $Letter, $rowiii['Units'])
															  ->setCellValue('M' . $Letter, $rowiii['YearGrad'])
															  ->setCellValue('N' . $Letter, $rowiii['Honors']);
								$b = $b + 1;
							}
//Page 2	
//Eligibility
$queryiv=$pdo->query("select * from iv where EmpNo = '" . $_SESSION["EmpID"] . "'");
$queryv=$pdo->query("select * from v where EmpNo = '" . $_SESSION["EmpID"] . "'");

$objPHPExcel->setActiveSheetIndex(1);

$c = 1;
while($rowiv = $queryiv->fetch())
{	
	$Letnum = 4 + $c;
	if ($Letnum < 12)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $Letnum, $rowiv['Career'])
									  ->setCellValue('F' . $Letnum, $rowiv['Rating'])
									  ->setCellValue('G' . $Letnum, $rowiv['Date'])
									  ->setCellValue('I' . $Letnum, $rowiv['Place'])
									  ->setCellValue('L' . $Letnum, $rowiv['LiNum'])
									  ->setCellValue('M' . $Letnum, $rowiv['LiDate']);
		$c = $c + 1;
	}
}
//Work Experiences
$d = 1;
while($rowv = $queryv->fetch())
{	
	$NumExp = 17 + $d;
	if ($NumExp < 46)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $NumExp, $rowv['IndateFrom'])
									  ->setCellValue('C' . $NumExp, $rowv['IndateTo'])
									  ->setCellValue('D' . $NumExp, $rowv['Position'])
									  ->setCellValue('G' . $NumExp, $rowv['Dept'])
									  ->setCellValue('J' . $NumExp, $rowv['Month'])
									  ->setCellValue('K' . $NumExp, $rowv['Salary'])
									  ->setCellValue('L' . $NumExp, $rowv['Status'])
									  ->setCellValue('M' . $NumExp, $rowv['GovService']);
		$d = $d + 1;
	}
}

//Page 3
//Voluntary Work
$queryvi=$pdo->query("select * from vi where EmpNo = '" . $_SESSION["EmpID"] . "'");
$year5 = date("Y") - 5;
$queryvii = $pdo->query("select * from vii where YEAR(InclusiveFrom) BETWEEN '". $year5 ."' AND '". date("Y") ."' AND EmpNo = '" . $_SESSION['EmpID'] . "' order by InclusiveFrom desc");
$queryviii=$pdo->query("select * from viii where EmpNo = '" . $_SESSION["EmpID"] . "'");

$objPHPExcel->setActiveSheetIndex(2);

$e = 1;
while($rowvi = $queryvi->fetch())
{	
	$numWork = 5 + $e;
	if ($numWork < 13)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $numWork, $rowvi['NameandAdd'])
									  ->setCellValue('E' . $numWork, $rowvi['InclusiveFrom'])
									  ->setCellValue('F' . $numWork, $rowvi['InclusiveTo'])
									  ->setCellValue('G' . $numWork, $rowvi['NumHours'])
									  ->setCellValue('H' . $numWork, $rowvi['Position']);
		$e = $e + 1;
	}
}
//Learning and Development
$f = 1;
while($rowvii = $queryvii->fetch())
{	
	$NumDev = 18 + $f;
	if ($NumDev < 40)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $NumDev, $rowvii['Title'])
									  ->setCellValue('E' . $NumDev, $rowvii['InclusiveFrom'])
									  ->setCellValue('F' . $NumDev, $rowvii['InclusiveTo'])
									  ->setCellValue('G' . $NumDev, $rowvii['NumHours'])
									  ->setCellValue('H' . $NumDev, $rowvii['LDType'])
									  ->setCellValue('I' . $NumDev, $rowvii['ConBy']);
		$f = $f + 1;
	}
}
//Other Information
$g = 1;
while($rowviii = $queryviii->fetch())
{	
	$NumInfo = 42 + $g;
	if ($NumInfo < 50)
	{
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $NumInfo, $rowviii['Skills'])
									  ->setCellValue('C' . $NumInfo, $rowviii['Recognition'])
									  ->setCellValue('I' . $NumInfo, $rowviii['Membership']);
		$g = $g + 1;
	}
}

//Page 4
//References
$queryref=$pdo->query("select * from reference where EmpNo = '" . $_SESSION["EmpID"] . "'");

$objPHPExcel->setActiveSheetIndex(3);

$h = 1;
while($rowref = $queryref->fetch())
{	
	$NumRef = 51 + $h;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $NumRef, $rowref['Name'])
								  ->setCellValue('F' . $NumRef, $rowref['Address'])
								  ->setCellValue('G' . $NumRef, $rowref['Tel']);
	$h = $h + 1;
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

header("location: PDS.xlsx");
