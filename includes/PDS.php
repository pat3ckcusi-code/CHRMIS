<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../includes/db_config.php'; 

// Composer autoload for PhpSpreadsheet
require __DIR__ . '/../Classes/PhpSpreadsheet/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    if (empty($_SESSION['EmpID'])) {
        throw new RuntimeException("No employee selected.");
    }

    $empNo = $_SESSION['EmpID'];

    // === Load template ===
    $templatePath = __DIR__ . '/../templates/PDS.xlsx';
    if (!file_exists($templatePath)) {
        throw new RuntimeException("Template file not found: $templatePath");
    }

    $spreadsheet = IOFactory::load($templatePath);

    /* ------------------------------------------------------------------
     * PAGE 1: Personal Info + Family + Education
     * ------------------------------------------------------------------ */
    $stmt = $pdo->prepare("SELECT * FROM i WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $rowi = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $stmt = $pdo->prepare("SELECT * FROM ii WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $rowii = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $stmt = $pdo->prepare("SELECT * FROM iii WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $rowsIII = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM children WHERE EmpNo = ?");
    $stmt->execute([$empNo]);
    $rowsChildren = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sheet = $spreadsheet->setActiveSheetIndex(0);

    if ($rowi) {    
    $sheet
        ->setCellValue('D10', $rowi['Lname']        ?? '')
        ->setCellValue('D11', $rowi['Fname']        ?? '')
        ->setCellValue('D12', $rowi['Mname']        ?? '')
        ->setCellValue('D13', !empty($rowi['BirthDate']) ? date('d/m/Y', strtotime($rowi['BirthDate'])) : '')
        ->setCellValue('D15', $rowi['PlaceBirth']   ?? '')
        ->setCellValue('D16', $rowi['Gender']       ?? '')
        ->setCellValue('D17', $rowi['Civil']        ?? '')
        ->setCellValue('J13', $rowi['Citizenship']  ?? '')
        ->setCellValue('J16', $rowi['Country']  ?? '')
        ->setCellValue('D22', isset($rowi['Height'])   ? (string) $rowi['Height']   : '')
        ->setCellValue('D24', isset($rowi['Weight'])   ? (string) $rowi['Weight']   : '')
        ->setCellValue('D25', $rowi['BloodType']    ?? '')
        ->setCellValue('D27', isset($rowi['GSIS'])     ? (string) $rowi['GSIS']     : '')
        ->setCellValue('D29', isset($rowi['Pagibig'])  ? (string) $rowi['Pagibig']  : '')
        ->setCellValue('D31', isset($rowi['PHealth'])  ? (string) $rowi['PHealth']  : '')
        ->setCellValue('D32', isset($rowi['SSS'])      ? (string) $rowi['SSS']      : '')
        ->setCellValue('D33', isset($rowi['Tin'])      ? (string) $rowi['Tin']      : '')
        ->setCellValue('D34', isset($rowi['AgencyEmpNo']) ? (string) $rowi['AgencyEmpNo'] : '')
        ->setCellValue('I34', $rowi['EMail']        ?? '')
        ->setCellValue('I33', isset($rowi['MobileNo'])  ? (string) $rowi['MobileNo']  : '')
        ->setCellValue('I32', isset($rowi['TelNo'])     ? (string) $rowi['TelNo']     : '')
        ->setCellValue('I31', isset($rowi['Perm_Zip'])  ? (string) $rowi['Perm_Zip']  : '')
        ->setCellValue('I29', $rowi['Perm_City']    ?? '')
        ->setCellValue('L29', $rowi['Perm_Province']?? '')
        ->setCellValue('L27', $rowi['Perm_Brgy']    ?? '')
        ->setCellValue('I27', $rowi['Perm_Subd']    ?? '')
        ->setCellValue('I25', isset($rowi['Perm_House']) ? (string) $rowi['Perm_House'] : '')
        ->setCellValue('L25', $rowi['Perm_Street']  ?? '')
        ->setCellValue('I24', isset($rowi['Zip'])      ? (string) $rowi['Zip']      : '')
        ->setCellValue('I22', $rowi['City']         ?? '')
        ->setCellValue('L22', $rowi['Province']     ?? '')
        ->setCellValue('L19', $rowi['Brgy']         ?? '')
        ->setCellValue('I19', $rowi['Subd']         ?? '')
        ->setCellValue('I17', isset($rowi['HouseNo'])   ? (string) $rowi['HouseNo']   : '')
        ->setCellValue('L17', $rowi['Street']       ?? '');
}


    if ($rowii) {
        $sheet->setCellValue('D36', $rowii['SLname'] ?? '')
              ->setCellValue('D37', $rowii['SFname'] ?? '')
			->setCellValue('G38', $rowii['SExtension'])
				->setCellValue('D38', $rowii['SMname'])
				->setCellValue('D39', $rowii['SOccupation'])
				->setCellValue('D40', $rowii['EmpBusName'])
				->setCellValue('D41', $rowii['BussAdd'])
				->setCellValue('D42', strval($rowii['TelNo']))
				->setCellValue('D43', $rowii['FLname'])
				->setCellValue('D44', $rowii['FFname'])
				->setCellValue('G45', $rowii['FExtension'])
				->setCellValue('D45', $rowii['FMname'])
				->setCellValue('D46', $rowii['MMaiden'])
				->setCellValue('D47', $rowii['MLname'])
				->setCellValue('D48', $rowii['MFname'])
				->setCellValue('D49', $rowii['MMname']);
    }

    $rowIdx = 38;
    foreach ($rowsChildren as $child) {
        if ($rowIdx > 50) break;
        $sheet->setCellValue("I{$rowIdx}", $child['ChildName'] ?? '')
              ->setCellValue("M{$rowIdx}", date('m/d/Y', strtotime($child['ChildBirth'])) ?? '');
        $rowIdx++;
    }

    $rowIdx = 54;
    foreach ($rowsIII as $rowiii) {
        $sheet->setCellValue("D{$rowIdx}", $rowiii['SchoolName'] ?? '')
              ->setCellValue("G{$rowIdx}", $rowiii['Course'] ?? '')
			  ->setCellValue("J{$rowIdx}" . $Letter, $rowiii['PeriodFrom'])
			->setCellValue("K{$rowIdx}" . $Letter, $rowiii['PeriodTo'])
			->setCellValue("L{$rowIdx}" . $Letter, $rowiii['Units'])
			->setCellValue("M{$rowIdx}" . $Letter, $rowiii['YearGrad'])
			->setCellValue("N{$rowIdx}" . $Letter, $rowiii['Honors']);
        $rowIdx++;
    }

    // ------------------------------------------
	// Page 2 - Eligibility
	// ------------------------------------------
	// ------------------------------------------
// Page 2 - Eligibility
// ------------------------------------------

// Initialize counters
$c = 0;
$d = 1;

// Fetch eligibility (iv) using prepared statement
$stmtIV = $pdo->prepare("SELECT * FROM iv WHERE EmpNo = ?");
$stmtIV->execute([$empNo]);
$rowsIV = $stmtIV->fetchAll(PDO::FETCH_ASSOC);

// Fetch work experience (v) using prepared statement
$stmtV = $pdo->prepare("SELECT * FROM v WHERE EmpNo = ? ORDER BY IndateTo DESC");
$stmtV->execute([$empNo]);
$rowsV = $stmtV->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet->setActiveSheetIndex(1);
$sheet = $spreadsheet->getActiveSheet();

// Populate Eligibility (iv)
foreach ($rowsIV as $rowiv) {
    $Letnum = 5 + $c;
    if ($Letnum < 12) { // limit rows
        $sheet
            ->setCellValue('A' . $Letnum, $rowiv['Career'] ?? '')
            ->setCellValue('F' . $Letnum, $rowiv['Rating'] ?? '')
            ->setCellValue('I' . $Letnum, $rowiv['Place'] ?? '')
            ->setCellValue('J' . $Letnum, $rowiv['LiNum'] ?? '');

        // Format dates
        $examDate = (!empty($rowiv['Date']) && $rowiv['Date'] != "0000-00-00") 
            ? date('d/m/Y', strtotime($rowiv['Date'])) 
            : "N/A";
        $sheet->setCellValue('G' . $Letnum, $examDate);

        $liDate = (!empty($rowiv['LiDate']) && $rowiv['LiDate'] != "0000-00-00") 
            ? date('d/m/Y', strtotime($rowiv['LiDate'])) 
            : "N/A";
        $sheet->setCellValue('K' . $Letnum, $liDate);

        $c++;
    }
}

// Separate ongoing and finished work experience
$ongoingRows = [];
$finishedRows = [];

foreach ($rowsV as $row) {
    if ($row['IndateTo'] === "0000-00-00") {
        $ongoingRows[] = $row;
    } else {
        $finishedRows[] = $row;
    }
}

// Sort finished rows by IndateTo descending
usort($finishedRows, function($a, $b) {
    return strtotime($b['IndateTo']) - strtotime($a['IndateTo']);
});

// Merge ongoing first, then finished
$sortedRows = array_merge($ongoingRows, $finishedRows);

// Populate work experience (v)
foreach ($sortedRows as $rowv) {
    $NumExp = 17 + $d;
    if ($NumExp < 46) {
        $indateFrom = (!empty($rowv['IndateFrom']) && $rowv['IndateFrom'] !== "0000-00-00") 
            ? date("d/m/Y", strtotime($rowv['IndateFrom'])) 
            : "";

        $indateTo = ($rowv['IndateTo'] === "0000-00-00") 
            ? "PRESENT" 
            : date("d/m/Y", strtotime($rowv['IndateTo']));

        $sheet
            ->setCellValue('A' . $NumExp, $indateFrom)
            ->setCellValue('C' . $NumExp, $indateTo)
            ->setCellValue('D' . $NumExp, $rowv['Position'] ?? '')
            ->setCellValue('G' . $NumExp, $rowv['Dept'] ?? '')
            ->setCellValue('J' . $NumExp, $rowv['Status'] ?? '')
            ->setCellValue('K' . $NumExp, $rowv['GovService'] ?? '');
        $d++;
    }
}



	// ------------------------------------------
	// Page 3 - Voluntary Work
	// ------------------------------------------
	$queryvi   = $pdo->query("SELECT * FROM vi WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
	$year5     = date("Y") - 5;
	$queryvii  = $pdo->query("SELECT * FROM vii 
							WHERE YEAR(InclusiveFrom) BETWEEN '" . $year5 . "' AND '" . date("Y") . "' 
							AND EmpNo = '" . $_SESSION['EmpID'] . "' 
							ORDER BY InclusiveFrom DESC");
	$queryviii = $pdo->query("SELECT * FROM viii WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");

	$spreadsheet->setActiveSheetIndex(2);
	$sheet = $spreadsheet->getActiveSheet();

	$e = 1;
	while ($rowvi = $queryvi->fetch()) {
		$numWork = 5 + $e;

		if ($numWork < 13) {
			$sheet
				->setCellValue('A' . $numWork, $rowvi['NameandAdd']     ?? '')
				// ->setCellValue('E' . $numWork, $rowvi['InclusiveFrom']  ?? '')
                ->setCellValue('E' . $numWork, (($rowvi['InclusiveFrom'] ?? '') === "0000-00-00") ? 'N/A' : ($rowvi['InclusiveFrom'] ?? ''))
                ->setCellValue('F' . $numWork, (($rowvi['InclusiveTo'] ?? '') === "0000-00-00") ? 'N/A' : ($rowvi['InclusiveTo'] ?? ''))
				// ->setCellValue('F' . $numWork, $rowvi['InclusiveTo']    ?? '')
				->setCellValue('G' . $numWork, $rowvi['NumHours']       ?? '')
				->setCellValue('H' . $numWork, $rowvi['Position']       ?? '');
			$e++;
		}
	}
	// ------------------------------------------
	// Page 3 - Learning and Development
	// ------------------------------------------
	
	$startRow = 18;
$maxRow   = 38; // last row in template
$rowsPerSheet = $maxRow - $startRow + 1; // 23 rows available
$f = 1;
$extraSheetIndex = 0;
$targetSheet = $sheet; // start with the main sheet

while ($rowvii = $queryvii->fetch()) {
    $NumDev = $startRow + (($f - 1) % $rowsPerSheet);

    // If we exceed the first 23 records, move to continuation sheet(s)
    if ($f > $rowsPerSheet && ($f - 1) % $rowsPerSheet == 0) {
        $extraSheetIndex++;
        $clonedSheet = clone $sheet;
        $clonedSheet->setTitle("L&D (Cont. {$extraSheetIndex})");

        // Clear only data cells (A–I, rows 17–39)
        for ($r = $startRow; $r <= $maxRow; $r++) {
            foreach (range('A', 'I') as $col) {
                $clonedSheet->setCellValue($col . $r, '');
            }
        }

        $spreadsheet->addSheet($clonedSheet);
        $targetSheet = $clonedSheet;
        $NumDev = $startRow; // reset row pointer in the new sheet
    }

    // Format InclusiveFrom
    $inclusiveFrom = '';
    if (!empty($rowvii['InclusiveFrom']) && $rowvii['InclusiveFrom'] != '0000-00-00') {
        $inclusiveFrom = date('d/m/Y', strtotime($rowvii['InclusiveFrom']));
    }

    // Format InclusiveTo
    $inclusiveTo = '';
    if (!empty($rowvii['InclusiveTo']) && $rowvii['InclusiveTo'] != '0000-00-00') {
        $inclusiveTo = date('d/m/Y', strtotime($rowvii['InclusiveTo']));
    }

    // Write values into the active target sheet
    $targetSheet
        ->setCellValue('A' . $NumDev, $rowvii['Title']   ?? '')
        ->setCellValue('E' . $NumDev, $inclusiveFrom)
        ->setCellValue('F' . $NumDev, $inclusiveTo)
        ->setCellValue('G' . $NumDev, $rowvii['NumHours'] ?? '')
        ->setCellValue('H' . $NumDev, $rowvii['LDType']   ?? '')
        ->setCellValue('I' . $NumDev, $rowvii['ConBy']    ?? '');

    $f++;
}	

	// ------------------------------------------
	// Page 3 - Other Information
	// ------------------------------------------
	$g = 1;
	while ($rowviii = $queryviii->fetch()) {
		$NumInfo = 41 + $g;

		if ($NumInfo < 50) {
			$sheet
				->setCellValue('A' . $NumInfo, $rowviii['Skills']      ?? '')
				->setCellValue('C' . $NumInfo, $rowviii['Recognition'] ?? '')
				->setCellValue('I' . $NumInfo, $rowviii['Membership']  ?? '');
			$g++;
		}
	}

	// ------------------------------------------
	// Page 4 - Questions
	// ------------------------------------------
	$queryQuestion = $pdo->query("SELECT * FROM question WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
$rowQuestion   = $queryQuestion->fetch(PDO::FETCH_ASSOC);

$spreadsheet->setActiveSheetIndex(3);
$sheet = $spreadsheet->getActiveSheet();

// Define question mappings (DB field prefix => [NO-cell, YES-cell, details-cell])
$questions = [
    '34a' => ['K6',  'H6',  null],
    '34b' => ['K8',  'H8',  'G11'],
    '35a' => ['K14', 'H14', 'G16'],
    '35b' => ['K19', 'H19', 'K20'], 
    '36a' => ['K25', 'H25', 'G27'],
    '37a' => ['K30', 'H30', 'G33'],
    '38a' => ['K36', 'H36', 'L37'],
    '38b' => ['K39', 'H39', 'L40'],
    '39a' => ['K43', 'H43', 'G45'],
    '40a' => ['K49', 'H49', 'K50'],
    '40b' => ['K51', 'H51', 'L52'],
    '40c' => ['K53', 'H53', 'L54'],
];

foreach ($questions as $field => [$cellNo, $cellYes, $cellDetails]) {
    $choiceField = $field . "_choice";   // e.g. "34a_choice"
    $detailsField = $field . "_details"; // e.g. "34a_details"

    $choice  = $rowQuestion[$choiceField] ?? '';
    $details = $rowQuestion[$detailsField] ?? '';

    if ($choice === 'NO') {
        $sheet->setCellValue($cellNo, "✓");
    } elseif ($choice === 'YES') {
        $sheet->setCellValue($cellYes, "✓");
        if ($cellDetails && !empty($details)) {
            $sheet->setCellValue($cellDetails, $details);
        }
    }
}

	// ------------------------------------------
	// Page 4 - References
	// ------------------------------------------
	$queryRef = $pdo->query("SELECT * FROM reference WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");

	$spreadsheet->setActiveSheetIndex(3);
	$sheet = $spreadsheet->getActiveSheet();

	$h = 1;
	while ($rowRef = $queryRef->fetch(PDO::FETCH_ASSOC)) {
		$NumRef = 57 + $h;

		$sheet
			->setCellValue('A' . $NumRef, $rowRef['Name']    ?? '')
			->setCellValue('F' . $NumRef, $rowRef['Address'] ?? '')
			->setCellValue('G' . $NumRef, $rowRef['Tel']     ?? '');

		$h++;
	}

	// ------------------------------------------
	// Page 4 - Government Issued ID
	// ------------------------------------------
	$stmt = $pdo->prepare("SELECT * FROM govid WHERE EmpNo = :empno");
	$stmt->execute(['empno' => $_SESSION["EmpID"]]);
	$rowGovID = $stmt->fetch(PDO::FETCH_ASSOC);

	$spreadsheet->setActiveSheetIndex(3);
	$sheet = $spreadsheet->getActiveSheet();
	$sheet
		->setCellValue('D67', $rowGovID['GovID']    ?? '')
		->setCellValue('D68', $rowGovID['GovIDNo']  ?? '')
		->setCellValue('D70',($rowGovID['Issuance'] ?? '') . ((($rowGovID['Issuance'] ?? '') && ($rowGovID['Place'] ?? '')) ? ' / ' : '') . ($rowGovID['Place'] ?? ''));

    /* ------------------------------------------------------------------
     * OUTPUT FILE
     * ------------------------------------------------------------------ */
    //$fileName = "PDS_output_" . date('Ymd_His') . ".xlsx";
	$empNo = $_SESSION['EmpID'] ?? 'unknown';
	$fileName = "PDS_output_" . $empNo . ".xlsx";

    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$fileName}\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Throwable $e) {
    if (ob_get_length()) {
        ob_end_clean();
    }
    header('Content-Type: text/plain; charset=utf-8', true, 500);
    echo "Error generating spreadsheet: " . $e->getMessage();
    exit;
}
