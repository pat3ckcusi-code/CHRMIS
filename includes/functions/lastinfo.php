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

if (isset($_SESSION['EmpID'])) {
    if (isset($_POST['Save'])) {
        $empID = $_SESSION['EmpID'];

        $count  = isset($_POST['numtext'])  ? intval($_POST['numtext'])  : 0;
        $count1 = isset($_POST['numtext1']) ? intval($_POST['numtext1']) : 0;
        $count2 = isset($_POST['numtext2']) ? intval($_POST['numtext2']) : 0;

        // TABLE VI --------------------------------------------------
        $pdo->prepare("DELETE FROM vi WHERE EmpNo = ?")->execute([$empID]);
        $stmtVI = $pdo->prepare("INSERT INTO vi (EmpNo, NameandAdd, InclusiveFrom, InclusiveTo, NumHours, Position) 
                                 VALUES (?, ?, ?, ?, ?, ?)");
        for ($x = 1; $x <= $count; $x++) {
            $test = trim($_POST['Name'.$x] ?? '');
            if ($test !== "") {
                $stmtVI->execute([
                    $empID,
                    $_POST['Name'.$x] ?? null,
                    $_POST['DatesFrom'.$x] ?? null,
                    $_POST['DatesTo'.$x] ?? null,
                    $_POST['NumHours'.$x] ?? null,
                    $_POST['Position'.$x] ?? null
                ]);
            }
        }

        // TABLE VII --------------------------------------------------
        $pdo->prepare("DELETE FROM vii WHERE EmpNo = ?")->execute([$empID]);
        $stmtVII = $pdo->prepare("INSERT INTO vii (EmpNo, Title, InclusiveFrom, InclusiveTo, NumHours, LDType, ConBy) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
        for ($y = 1; $y <= $count1; $y++) {
            $test = trim($_POST['Learning'.$y] ?? '');
            if ($test !== "") {
                $stmtVII->execute([
                    $empID,
                    $_POST['Learning'.$y] ?? null,
                    $_POST['ADatesFrom'.$y] ?? null,
                    $_POST['ADatesTo'.$y] ?? null,
                    $_POST['Hours'.$y] ?? null,
                    $_POST['Type'.$y] ?? null,
                    $_POST['Conducted'.$y] ?? null
                ]);
            }
        }

        // TABLE VIII --------------------------------------------------
        $pdo->prepare("DELETE FROM viii WHERE EmpNo = ?")->execute([$empID]);
        $stmtVIII = $pdo->prepare("INSERT INTO viii (EmpNo, Skills, Recognition, Membership)  VALUES (?, ?, ?, ?)");
        if (!empty($_POST['Skills'])) {
			foreach ($_POST['Skills'] as $index => $skill) {
				$skill = trim($skill);
				$recognition = $_POST['Recognition'][$index] ?? null;
				$membership  = $_POST['Membership'][$index] ?? null;

				if ($skill !== "" || $recognition !== "" || $membership !== "") {
					$stmtVIII->execute([
						$empID,
						$skill,
						$recognition,
						$membership
					]);
				}
			}
		}

        // TABLE QUESTION ----------------------------------------------
        $pdo->prepare("DELETE FROM question WHERE EmpNo = ?")->execute([$empID]);

        $stmtQ = $pdo->prepare("INSERT INTO question 
            (EmpNo, 34a_choice, 34b_choice, 34b_details,
            35a_choice, 35a_details, 35b_choice, 35b_details,
            36a_choice, 36a_details, 37a_choice, 37a_details,
            38a_choice, 38a_details, 38b_choice, 38b_details,
            39a_choice, 39a_details, 40a_choice, 40a_details,
            40b_choice, 40b_details, 40c_choice, 40c_details)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmtQ->execute([
            $empID,
            $_POST['34a_choice'] ?? null,
            $_POST['34b_choice'] ?? null,
            $_POST['34b_details'] ?? null,
            $_POST['35a_choice'] ?? null,
            $_POST['35a_details'] ?? null,
            $_POST['35b_choice'] ?? null,
            $_POST['35b_details'] ?? null,
            $_POST['36a_choice'] ?? null,
            $_POST['36a_details'] ?? null,
            $_POST['37a_choice'] ?? null,
            $_POST['37a_details'] ?? null,
            $_POST['38a_choice'] ?? null,
            $_POST['38a_details'] ?? null,
            $_POST['38b_choice'] ?? null,
            $_POST['38b_details'] ?? null,
            $_POST['39a_choice'] ?? null,
            $_POST['39a_details'] ?? null,
            $_POST['40a_choice'] ?? null,
            $_POST['40a_details'] ?? null,
            $_POST['40b_choice'] ?? null,
            $_POST['40b_details'] ?? null,
            $_POST['40c_choice'] ?? null,
            $_POST['40c_details'] ?? null
        ]);

        // TABLE REFERENCE --------------------------------------------
        $pdo->prepare("DELETE FROM reference WHERE EmpNo = ?")->execute([$empID]);
        $stmtRef = $pdo->prepare("INSERT INTO reference (EmpNo, Name, Address, Tel) VALUES (?, ?, ?, ?)");
        for ($r = 1; $r <= 3; $r++) {
            $test = trim($_POST['txtName'.$r] ?? '');
            if ($test !== "") {
                $stmtRef->execute([
                    $empID,
                    $_POST['txtName'.$r]    ?? null,
                    $_POST['txtAddress'.$r] ?? null,
                    $_POST['txtTel'.$r]     ?? null
                ]);
            }
        }

        //Government ID's------------------------
        $pdo->prepare("DELETE FROM govid WHERE EmpNo = ?")->execute([$empID]);
        $stmtGov = $pdo->prepare("INSERT INTO govid 
            (EmpNo, GovID, GovIDNo, Issuance, Place)
            VALUES (?,?,?,?,?)");

        $stmtGov->execute([
            $empID,
            $_POST['txtGovID'] ?? null,
            $_POST['txtGovIDNo'] ?? null,
            $_POST['txtIssuance1'] ?? null,
            $_POST['txtIssuance2'] ?? null,            
        ]);
            $response = [
            "success" => true,
            "title"   => "Saved!",
            "message" => "Your information has been saved successfully.",
            "icon"    => "success"
        ];
    }
} else {
    
    $response = [
        "success" => false,
        "title"   => "Incomplete Information",
        "message" => "Please complete your Personal Information first!",
        "icon"    => "warning"
    ];
}
echo json_encode($response);
exit;
?>
