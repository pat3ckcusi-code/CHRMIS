<?php
require_once __DIR__ . '/../includes/db_config.php';
session_start();

try {
    // Determine admin department(s)
    $adminDept = null;
    if (!empty($_SESSION['EmpID'])) {
        $stmt = $pdo->prepare("SELECT Dept FROM adminusers WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([(string)$_SESSION['EmpID']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Dept'])) {
            $adminDept = $row['Dept'];
        }
    }

    // Fallback to all admin depts
    if ($adminDept === null) {
        $stmt = $pdo->query("SELECT DISTINCT Dept FROM adminusers WHERE Dept IS NOT NULL AND TRIM(Dept) <> ''");
        $adminDepts = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } else {
        $adminDepts = [$adminDept];
    }

    if (empty($adminDepts)) {
        $statistics = [
            'leaveStatistics' => [],
            'etaLocatorStatistics' => [],
            'summary' => [
                'totalApprovedLeaves' => 0,
                'totalApprovedETA' => 0,
                'totalApprovedLocator' => 0,
                'habitualLeavers' => 0,
                'frequentETAUsers' => 0,
                'frequentLocatorUsers' => 0
            ]
        ];
    } else {
        // Get employees in the department
        $placeholders = implode(',', array_fill(0, count($adminDepts), '?'));
        $sql = "SELECT 
                    i.EmpNo,
                    TRIM(CONCAT(
                        i.Lname, ', ', i.Fname,
                        IFNULL(CONCAT(' ', LEFT(i.Mname, 1), '.'), ''),
                        CASE
                            WHEN i.Extension IS NULL THEN ''
                            WHEN LOWER(i.Extension) = 'n/a' THEN ''
                            ELSE CONCAT(' ', i.Extension)
                        END
                    )) AS name,
                    i.Dept
                FROM i
                WHERE i.Dept IN ($placeholders)
                ORDER BY i.Lname, i.Fname";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($adminDepts);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get leave statistics (approved leaves only)
        $leaveStatistics = [];
        $totalApprovedLeaves = 0;
        
        if (!empty($employees)) {
            $empNos = array_column($employees, 'EmpNo');
            $p = implode(',', array_fill(0, count($empNos), '?'));

            // Query for approved leaves with leave type and number of days
            $sql = "SELECT 
                        fl.EmpNo,
                        fl.LeaveType,
                        COUNT(*) as count,
                        SUM(fl.NumDays) as totalDays
                    FROM filedleave fl
                    WHERE fl.Remarks = 'APPROVED'
                    AND fl.EmpNo IN ($p)
                    GROUP BY fl.EmpNo, fl.LeaveType
                    ORDER BY count DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($empNos);
            $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group by employee
            $leaveByEmp = [];
            foreach ($leaves as $leave) {
                $empNo = $leave['EmpNo'];
                if (!isset($leaveByEmp[$empNo])) {
                    $leaveByEmp[$empNo] = [
                        'totalLeaves' => 0,
                        'totalDays' => 0,
                        'leaveTypes' => []
                    ];
                }
                $leaveByEmp[$empNo]['totalLeaves'] += $leave['count'];
                $leaveByEmp[$empNo]['totalDays'] += $leave['totalDays'];
                $leaveByEmp[$empNo]['leaveTypes'][$leave['LeaveType']] = [
                    'count' => $leave['count'],
                    'totalDays' => $leave['totalDays']
                ];
                $totalApprovedLeaves += $leave['count'];
            }

            // Create leave statistics with employee names
            foreach ($employees as $emp) {
                $empNo = $emp['EmpNo'];
                if (isset($leaveByEmp[$empNo])) {
                    $leaveStatistics[] = [
                        'empNo' => $empNo,
                        'name' => $emp['name'],
                        'totalLeaves' => $leaveByEmp[$empNo]['totalLeaves'],
                        'totalDays' => $leaveByEmp[$empNo]['totalDays'],
                        'leaveTypes' => $leaveByEmp[$empNo]['leaveTypes']
                    ];
                }
            }

            // Sort by total leaves (habitual leavers)
            usort($leaveStatistics, function($a, $b) {
                return $b['totalLeaves'] - $a['totalLeaves'];
            });
        }

        // Get ETA and Locator statistics (approved only)
        $etaLocatorStatistics = [];
        $totalApprovedETA = 0;
        $totalApprovedLocator = 0;

        if (!empty($employees)) {
            $empNos = array_column($employees, 'EmpNo');
            $p = implode(',', array_fill(0, count($empNos), '?'));

            // Query for approved ETA/Locator
            $sql = "SELECT 
                        EmpNo,
                        application_type,
                        COUNT(*) as count,
                        MIN(travel_date) as firstDate,
                        MAX(travel_date) as lastDate
                    FROM eta_locator
                    WHERE status = 'Approved'
                    AND EmpNo IN ($p)
                    GROUP BY EmpNo, application_type
                    ORDER BY count DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($empNos);
            $etaLocators = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group by employee
            $etaLocByEmp = [];
            foreach ($etaLocators as $item) {
                $empNo = $item['EmpNo'];
                if (!isset($etaLocByEmp[$empNo])) {
                    $etaLocByEmp[$empNo] = [
                        'totalETA' => 0,
                        'totalLocator' => 0,
                        'total' => 0
                    ];
                }
                if ($item['application_type'] === 'ETA') {
                    $etaLocByEmp[$empNo]['totalETA'] = $item['count'];
                    $totalApprovedETA += $item['count'];
                } else {
                    $etaLocByEmp[$empNo]['totalLocator'] = $item['count'];
                    $totalApprovedLocator += $item['count'];
                }
                $etaLocByEmp[$empNo]['total'] = $etaLocByEmp[$empNo]['totalETA'] + $etaLocByEmp[$empNo]['totalLocator'];
            }

            // Create ETA/Locator statistics with employee names
            foreach ($employees as $emp) {
                $empNo = $emp['EmpNo'];
                if (isset($etaLocByEmp[$empNo])) {
                    $etaLocatorStatistics[] = [
                        'empNo' => $empNo,
                        'name' => $emp['name'],
                        'totalETA' => $etaLocByEmp[$empNo]['totalETA'],
                        'totalLocator' => $etaLocByEmp[$empNo]['totalLocator'],
                        'total' => $etaLocByEmp[$empNo]['total']
                    ];
                }
            }

            // Sort by total (frequent travelers)
            usort($etaLocatorStatistics, function($a, $b) {
                return $b['total'] - $a['total'];
            });
        }

        // Calculate summary statistics
        $habitualLeavers = count(array_filter($leaveStatistics, function($item) { return $item['totalLeaves'] >= 3; }));
        $frequentETAUsers = count(array_filter($etaLocatorStatistics, function($item) { return $item['totalETA'] >= 3; }));
        $frequentLocatorUsers = count(array_filter($etaLocatorStatistics, function($item) { return $item['totalLocator'] >= 3; }));

        $statistics = [
            'leaveStatistics' => $leaveStatistics,
            'etaLocatorStatistics' => $etaLocatorStatistics,
            'summary' => [
                'totalApprovedLeaves' => $totalApprovedLeaves,
                'totalApprovedETA' => $totalApprovedETA,
                'totalApprovedLocator' => $totalApprovedLocator,
                'habitualLeavers' => $habitualLeavers,
                'frequentETAUsers' => $frequentETAUsers,
                'frequentLocatorUsers' => $frequentLocatorUsers
            ]
        ];
    }

    // Return JSON if requested
    if (isset($_GET['json']) && $_GET['json'] == '1') {
        header('Content-Type: application/json');
        echo json_encode($statistics);
        exit;
    }

    // Return as variable for partial
    $stats = $statistics;

} catch (PDOException $e) {
    $stats = [
        'leaveStatistics' => [],
        'etaLocatorStatistics' => [],
        'summary' => [
            'totalApprovedLeaves' => 0,
            'totalApprovedETA' => 0,
            'totalApprovedLocator' => 0,
            'habitualLeavers' => 0,
            'frequentETAUsers' => 0,
            'frequentLocatorUsers' => 0
        ],
        'error' => $e->getMessage()
    ];
}
?>
