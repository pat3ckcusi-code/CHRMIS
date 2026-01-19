<?php
session_start();
require_once('../db_config.php');

header('Content-Type: application/json');

$response = [
    "success" => false,
    "title"   => "Error",
    "message" => "Something went wrong.",
    "icon"    => "error"
];
try {
    // Get EmpNo (from session or form) 
    $empNo = $_POST['txtEmpNo'] ?? null;

    // === PERSONAL INFORMATION (i) ===
    $citizenship = $_POST['rdCitizenship'] ?? null;
    $email       = $_POST['txtEmail'] ?? null;

    // Check if record exists
    $existsStmt = $pdo->prepare("SELECT COUNT(*) FROM i WHERE EmpNo = ?");
    $existsStmt->execute([$empNo]);
    $hasPersonal = $existsStmt->fetchColumn() > 0;

    if ($hasPersonal) {
        $sql = "UPDATE i SET 
            Lname=?, Fname=?, Extension=?, Mname=?, 
            BirthDate=?, PlaceBirth=?, Gender=?, Civil=?, Height=?, Weight=?, 
            BloodType=?, GSIS=?, Pagibig=?, PHealth=?, PSN=?, Tin=?, AgencyEmpNo=?,
            Citizenship=?, Country=?,
            HouseNo=?, Street=?, Subd=?, Brgy=?, City=?, Province=?, Zip=?,
            Perm_House=?, Perm_Street=?, Perm_Subd=?, Perm_Brgy=?, Perm_City=?, Perm_Province=?, Perm_Zip=?,
            TelNo=?, MobileNo=?, EMail=?
            WHERE EmpNo=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['txtLname'] ?? null, $_POST['txtFname'] ?? null, $_POST['txtExt'] ?? null, $_POST['txtMname'] ?? null,
            $_POST['txtDate'] ?? null, $_POST['txtPlace'] ?? null, $_POST['txtSex'] ?? null, $_POST['rdCivil'] ?? null, $_POST['txtHeight'] ?? null, $_POST['txtWeight'] ?? null,
            $_POST['txtBlood'] ?? null, $_POST['txtGSIS'] ?? null, $_POST['txtPagibig'] ?? null, $_POST['txtPhilhealth'] ?? null, $_POST['txtPSN'] ?? null, $_POST['txtTIN'] ?? null, $_POST['txtAgency'] ?? null,
            $citizenship, $_POST['ddCountry'] ?? null,
            $_POST['txtHouse'] ?? null, $_POST['txtStreet'] ?? null, $_POST['txtSubd'] ?? null, $_POST['txtBrgy'] ?? null, $_POST['txtCity'] ?? null, $_POST['txtProvince'] ?? null, $_POST['txtZip1'] ?? null,
            $_POST['txtHouse1'] ?? null, $_POST['txtStreet1'] ?? null, $_POST['txtSubd1'] ?? null, $_POST['txtBrgy1'] ?? null, $_POST['txtCity1'] ?? null, $_POST['txtProvince1'] ?? null, $_POST['txtZip2'] ?? null,
            $_POST['txtTel'] ?? null, $_POST['txtMobile'] ?? null, $email,
            $empNo
        ]);
    }else {
        $sql = "INSERT INTO i (
            EmpNo, Lname, Fname, Extension, Mname,
            BirthDate, PlaceBirth, Gender, Civil, Height, Weight,
            BloodType, GSIS, Pagibig, PHealth, PSN,
            Citizenship, Country,
            HouseNo, Street, Subd, Brgy, City, Province, Zip,
            Perm_House, Perm_Street, Perm_Subd, Perm_Brgy, Perm_City, Perm_Province, Perm_Zip,
            TelNo, MobileNo, EMail
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $empNo, $_POST['txtLname'] ?? null,
                    $_POST['txtFname'] ?? null,
                    $_POST['txtExt'] ?? null,
                    $_POST['txtMname'] ?? null,
                    $_POST['txtDate'] ?? null,
                    $_POST['txtPlace'] ?? null,
                    $_POST['txtSex'] ?? null,
                    $_POST['rdCivil'] ?? null,
                    $_POST['txtHeight'] ?? null,
                    $_POST['txtWeight'] ?? null,
                    $_POST['txtBlood'] ?? null,
                    $_POST['txtGSIS'] ?? null,
                    $_POST['txtPagibig'] ?? null,
                    $_POST['txtPhilhealth'] ?? null,
                    $_POST['txtPSN'] ?? null,
                    $_POST['txtTIN'] ?? null,
                    $_POST['txtAgency'] ?? null,
                    $citizenship,
                    $_POST['ddCountry'] ?? null,
                    $_POST['txtHouse'] ?? null,
                    $_POST['txtStreet'] ?? null,
                    $_POST['txtSubd'] ?? null,
                    $_POST['txtBrgy'] ?? null,
                    $_POST['txtCity'] ?? null,
                    $_POST['txtProvince'] ?? null,
                    $_POST['txtZip1'] ?? null,
                    $_POST['txtHouse1'] ?? null,
                    $_POST['txtStreet1'] ?? null,
                    $_POST['txtSubd1'] ?? null,
                    $_POST['txtBrgy1'] ?? null,
                    $_POST['txtCity1'] ?? null,
                    $_POST['txtProvince1'] ?? null,
                    $_POST['txtZip2'] ?? null,
                    $_POST['txtTel'] ?? null,
                    $_POST['txtMobile'] ?? null,
                    $email

        ]);
    }


    // FAMILY BACKGROUND (ii) 
    if ($_SESSION['row11'] > 0) {
        $sqlFamily = "UPDATE ii SET 
            SLname = ?, SFname = ?, SMname = ?, SExtension = ?, SOccupation = ?, 
            EmpBusName = ?, BussAdd = ?, TelNo = ?, FLname = ?, FFname = ?, FMname = ?, 
            FExtension = ?, MLname = ?, MFname = ?, MMname = ? 
            WHERE EmpNo = ?";
        
        $stmtFamily = $pdo->prepare($sqlFamily);
        $stmtFamily->execute([
            $_POST['txtSLname'], $_POST['txtSFname'], $_POST['txtSMname'], $_POST['txtExt1'], $_POST['txtSoccu'],
            $_POST['txtEmpBusName'], $_POST['txtBussAdd'], $_POST['txtTel1'], $_POST['txtFLname'], $_POST['txtFFname'],
            $_POST['txtFMname'], $_POST['txtExt2'], $_POST['txtMLname'], $_POST['txtMFname'],
            $_POST['txtMMname'], $_SESSION['EmpID']
        ]);
    } else {
        $sqlFamily = "INSERT INTO ii (
            EmpNo, SLname, SFname, SMname, SExtension, SOccupation, EmpBusName, BussAdd, TelNo, 
            FLname, FFname, FMname, FExtension, MLname, MFname, MMname
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmtFamily = $pdo->prepare($sqlFamily);
        $stmtFamily->execute([
            $_SESSION['EmpID'], $_POST['txtSLname'], $_POST['txtSFname'], $_POST['txtSMname'], $_POST['txtExt1'],
            $_POST['txtSoccu'], $_POST['txtEmpBusName'], $_POST['txtBussAdd'], $_POST['txtTel1'], $_POST['txtFLname'],
            $_POST['txtFFname'], $_POST['txtFMname'], $_POST['txtExt2'], $_POST['txtMLname'],
            $_POST['txtMFname'], $_POST['txtMMname']
        ]);
    }

    // == CHILDREN ==
    try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $empNo = $_SESSION["EmpID"];

        // Delete existing children first to prevent duplicates
        $stmt = $pdo->prepare("DELETE FROM children WHERE EmpNo = ?");
        $stmt->execute([$empNo]);

        // Insert all child records from form
        $stmt = $pdo->prepare("INSERT INTO children (EmpNo, ChildName, ChildBirth) VALUES (?, ?, ?)");

        for ($i = 1; $i <= 9; $i++) {
            $childName  = $_POST["txtChild$i"] ?? "";
            $childBirth = $_POST["txtChildBirth$i"] ?? "";

            if (!empty($childName) && !empty($childBirth)) {
                $stmt->execute([$empNo, $childName, $childBirth]);
            }
        }

        $response = [
            "success" => true,
            "title"   => "Success",
            "message" => "Children information saved successfully.",
            "icon"    => "success"
            ];
        }
    } catch (Exception $e) {
        $response = [
            "success" => false,
            "title"   => "Error",
            "message" => "Failed to save children: " . $e->getMessage(),
            "icon"    => "error"
        ];
    }

    // === EDUCATIONAL BACKGROUND (iii) ===
    $pdo->prepare("DELETE FROM iii WHERE EmpNo = ?")->execute([$_SESSION['EmpID']]);

    $educationLevels = [
        'ELEMENTARY' => ['txtElem', 'txtElemEduc', 'txtElemFrom', 'txtElemTo', 'txtElemUnits', 'txtElemYearGrad', 'txtElemHonor'],
        'SECONDARY' => ['txtSecondary', 'txtSecondEduc', 'txtSecondFrom', 'txtSecondTo', 'txtSecondUnits', 'txtSecondYearGrad', 'txtSecondHonor'],
        'VOCATIONAL/TRADE COURSE' => ['txtVocational', 'txtVocDeg', 'txtVocFrom', 'txtVocTo', 'txtVocUnits', 'txtVocYearGrad', 'txtVocHonor'],
        'COLLEGE' => ['txtCollege', 'txtCollegeDeg', 'txtCollegeFrom', 'txtCollegeTo', 'txtCollegeUnits', 'txtCollegeYearGrad', 'txtCollegeHonor'],
        'GRADUATE STUDIES' => ['txtGradStudies', 'txtGradDeg', 'txtGradFrom', 'txtGradTo', 'txtGradUnits', 'txtGradYearGrad', 'txtGradHonor']
    ];

    $sqlEdu = "INSERT INTO iii (EmpNo, Level, SchoolName, Course, PeriodFrom, PeriodTo, Units, YearGrad, Honors) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtEdu = $pdo->prepare($sqlEdu);

    foreach ($educationLevels as $level => $fields) {
        $values = array_map(fn($f) => $_POST[$f] ?? '', $fields);
        if (!empty($values[0])) { // Only insert if SchoolName is not empty
            $stmtEdu->execute(array_merge([$_SESSION['EmpID'], $level], $values));
        }
    }

    // === SUCCESS RESPONSE ===
    $response = [
        "success" => true,
        "title"   => "Saved!",
        "message" => "Your information has been saved successfully.",
        "icon"    => "success"
    ];

} catch (PDOException $e) {
    $response = [
        "success" => false,
        "title"   => "Database Error",
        "message" => $e->getMessage(),
        "icon"    => "error"
    ];
}

echo json_encode($response);
exit;
?>