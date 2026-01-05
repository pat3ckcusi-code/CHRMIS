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

    if ($adminDept === null) {
        $stmt = $pdo->query("SELECT DISTINCT Dept FROM adminusers WHERE Dept IS NOT NULL AND TRIM(Dept) <> ''");
        $adminDepts = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } else {
        $adminDepts = [$adminDept];
    }

    if (empty($adminDepts)) {
        $statistics = null;
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
                    )) AS name
                FROM i
                WHERE i.Dept IN ($placeholders)
                ORDER BY i.Lname, i.Fname";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($adminDepts);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get leave statistics
        $leaveStatistics = [];
        if (!empty($employees)) {
            $empNos = array_column($employees, 'EmpNo');
            $p = implode(',', array_fill(0, count($empNos), '?'));

            $sql = "SELECT 
                        fl.EmpNo,
                        fl.LeaveType,
                        COUNT(*) as count,
                        SUM(fl.NumDays) as totalDays
                    FROM filedleave fl
                    WHERE fl.Remarks = 'APPROVED'
                    AND fl.EmpNo IN ($p)
                    GROUP BY fl.EmpNo, fl.LeaveType";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($empNos);
            $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $leaveByEmp = [];
            foreach ($leaves as $leave) {
                $empNo = $leave['EmpNo'];
                if (!isset($leaveByEmp[$empNo])) {
                    $leaveByEmp[$empNo] = ['totalLeaves' => 0, 'totalDays' => 0];
                }
                $leaveByEmp[$empNo]['totalLeaves'] += $leave['count'];
                $leaveByEmp[$empNo]['totalDays'] += $leave['totalDays'];
            }

            foreach ($employees as $emp) {
                if (isset($leaveByEmp[$emp['EmpNo']])) {
                    $leaveStatistics[] = [
                        'empNo' => $emp['EmpNo'],
                        'name' => $emp['name'],
                        'totalLeaves' => $leaveByEmp[$emp['EmpNo']]['totalLeaves'],
                        'totalDays' => $leaveByEmp[$emp['EmpNo']]['totalDays']
                    ];
                }
            }

            usort($leaveStatistics, function($a, $b) {
                return $b['totalLeaves'] - $a['totalLeaves'];
            });
        }

        // Get ETA and Locator statistics
        $etaLocatorStatistics = [];
        if (!empty($employees)) {
            $empNos = array_column($employees, 'EmpNo');
            $p = implode(',', array_fill(0, count($empNos), '?'));

            $sql = "SELECT 
                        EmpNo,
                        application_type,
                        COUNT(*) as count
                    FROM eta_locator
                    WHERE status = 'Approved'
                    AND EmpNo IN ($p)
                    GROUP BY EmpNo, application_type";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($empNos);
            $etaLocators = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $etaLocByEmp = [];
            foreach ($etaLocators as $item) {
                $empNo = $item['EmpNo'];
                if (!isset($etaLocByEmp[$empNo])) {
                    $etaLocByEmp[$empNo] = ['totalETA' => 0, 'totalLocator' => 0];
                }
                if ($item['application_type'] === 'ETA') {
                    $etaLocByEmp[$empNo]['totalETA'] = $item['count'];
                } else {
                    $etaLocByEmp[$empNo]['totalLocator'] = $item['count'];
                }
            }

            foreach ($employees as $emp) {
                if (isset($etaLocByEmp[$emp['EmpNo']])) {
                    $etaLocatorStatistics[] = [
                        'empNo' => $emp['EmpNo'],
                        'name' => $emp['name'],
                        'totalETA' => $etaLocByEmp[$emp['EmpNo']]['totalETA'],
                        'totalLocator' => $etaLocByEmp[$emp['EmpNo']]['totalLocator'],
                        'total' => $etaLocByEmp[$emp['EmpNo']]['totalETA'] + $etaLocByEmp[$emp['EmpNo']]['totalLocator']
                    ];
                }
            }

            usort($etaLocatorStatistics, function($a, $b) {
                return $b['total'] - $a['total'];
            });
        }

        $statistics = [
            'leaveStatistics' => $leaveStatistics,
            'etaLocatorStatistics' => $etaLocatorStatistics
        ];
    }
} catch (PDOException $e) {
    $statistics = null;
}
?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="leave-tab" data-toggle="pill" href="#leaveStats" role="tab">Habitual Leave</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="eta-tab" data-toggle="pill" href="#etaStats" role="tab">ETA / Locator</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- Leave Statistics Tab -->
            <div class="tab-pane fade show active" id="leaveStats" role="tabpanel">
                <?php if ($statistics && !empty($statistics['leaveStatistics'])): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Employee Name</th>
                                    <th class="text-center">Total Approved Leaves</th>
                                    <th class="text-center">Total Days</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistics['leaveStatistics'] as $item): ?>
                                    <?php 
                                        $statusClass = '';
                                        $statusLabel = '';
                                        if ($item['totalLeaves'] >= 5) {
                                            $statusClass = 'bg-danger';
                                            $statusLabel = 'High Risk';
                                        } elseif ($item['totalLeaves'] >= 3) {
                                            $statusClass = 'bg-warning';
                                            $statusLabel = 'Monitor';
                                        } else {
                                            $statusClass = 'bg-success';
                                            $statusLabel = 'Normal';
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($item['empNo']); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary"><?php echo $item['totalLeaves']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info"><?php echo $item['totalDays']; ?> days</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $statusClass; ?> text-white"><?php echo $statusLabel; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No leave statistics available.
                    </div>
                <?php endif; ?>
            </div>

            <!-- ETA/Locator Statistics Tab -->
            <div class="tab-pane fade" id="etaStats" role="tabpanel">
                <?php if ($statistics && !empty($statistics['etaLocatorStatistics'])): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Employee Name</th>
                                    <th class="text-center">ETA Count</th>
                                    <th class="text-center">Locator Count</th>
                                    <th class="text-center">Total Trips</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistics['etaLocatorStatistics'] as $item): ?>
                                    <?php 
                                        $statusClass = '';
                                        $statusLabel = '';
                                        if ($item['total'] >= 10) {
                                            $statusClass = 'bg-danger';
                                            $statusLabel = 'Frequent';
                                        } elseif ($item['total'] >= 5) {
                                            $statusClass = 'bg-warning';
                                            $statusLabel = 'Regular';
                                        } else {
                                            $statusClass = 'bg-success';
                                            $statusLabel = 'Occasional';
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($item['empNo']); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success"><?php echo $item['totalETA']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info"><?php echo $item['totalLocator']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary"><?php echo $item['total']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $statusClass; ?> text-white"><?php echo $statusLabel; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No ETA/Locator statistics available.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid #dee2e6;
        margin-right: 5px;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }
    .nav-tabs .nav-link:hover {
        border-color: #dee2e6;
        color: #007bff;
    }
    .badge {
        padding: 8px 12px;
        font-size: 12px;
    }
</style>
