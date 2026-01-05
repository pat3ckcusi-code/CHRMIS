<?php
require_once __DIR__ . '/../includes/db_config.php';
session_start();

try {
    // determine admin department(s)
    $adminDept = null;
    if (!empty($_SESSION['EmpID'])) {
        $stmt = $pdo->prepare("SELECT Dept FROM adminusers WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([ (string)$_SESSION['EmpID'] ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Dept'])) {
            $adminDept = $row['Dept'];
        }
    }

    // fallback to all admin depts
    if ($adminDept === null) {
        $stmt = $pdo->query("SELECT DISTINCT Dept FROM adminusers WHERE Dept IS NOT NULL AND TRIM(Dept) <> ''");
        $adminDepts = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } else {
        $adminDepts = [$adminDept];
    }

    if (empty($adminDepts)) {
        $employees = [];
    } else {
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
                        MAX(v.Position) AS position
                    FROM i
                    LEFT JOIN v ON v.EmpNo = i.EmpNo
                    WHERE i.Dept IN ($placeholders)
                    GROUP BY i.EmpNo, name
                    ORDER BY i.Lname, i.Fname";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($adminDepts);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch today's ETA / Locator status per employee
        $statusToday = [];
        if (!empty($employees)) {
            $empNos = array_column($employees, 'EmpNo');
            $p = implode(',', array_fill(0, count($empNos), '?'));
            $today = date('Y-m-d');

            $sql = "SELECT *
                    FROM eta_locator
                    WHERE travel_date = ?
                    AND status = 'Approved'
                    AND EmpNo IN ($p)";
            $params = array_merge([$today], $empNos);

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $statusToday[$row['EmpNo']] = [
                    'type' => $row['application_type'],
                    'arrival' => $row['Arrival_Time']
                ];
            }
        }
    // calculate in-office count
       $inOfficeCount = 0;

foreach ($employees as $emp) {
    $empNo = $emp['EmpNo'];

    // No ETA/Locator record → In Office
    if (!isset($statusToday[$empNo])) {
        $inOfficeCount++;
        continue;
    }

    $rec = $statusToday[$empNo];
    $type = $rec['type'];
    $arrival = $rec['arrival'];

    // Out (ETA)
    if ($type === 'ETA') {
        continue;
    }

    // Out (Locator) if arrival is NULL/empty/zero date
    if ($type === 'Locator' && (empty($arrival) || $arrival === '0000-00-00 00:00:00')) {
        continue;
    }

    // Otherwise → In Office
    $inOfficeCount++;
}


    // if ?json=1 is passed, return JSON
    if (!empty($_GET['json'])) {
        header('Content-Type: application/json');
        echo json_encode(['count' => $inOfficeCount]);
        exit;
    }

} catch (Throwable $e) {
    $employees = [];
    $onSiteSet = [];
    $inOfficeCount = 0;

    if (!empty($_GET['json'])) {
        header('Content-Type: application/json');
        echo json_encode(['count' => 0]);
        exit;
    }
}

?>
<!-- HTML Table -->
<table class="table table-bordered table-hover">
  <thead class="thead-light">
    <tr>
      <th>Employee Number</th>
      <th>Employee Name</th>
      <th>Position</th>
      <th class="text-center">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($employees)): ?>
      <tr>
        <td colspan="4" class="text-muted text-center">No employees found for your administrative department.</td>
      </tr>
    <?php else: ?>
      <?php foreach ($employees as $r): ?>
        <?php
          $empNo = htmlspecialchars($r['EmpNo']);
          $name  = htmlspecialchars($r['name']);
          $pos   = htmlspecialchars($r['position']);

          $statusLabel = 'In Office';
            $badge = 'success';

            if (isset($statusToday[$r['EmpNo']])) {
                $rec = $statusToday[$r['EmpNo']];
                $type = $rec['type'];
                $arrival = trim((string)$rec['arrival']);

                if ($type === 'ETA') {
                    $statusLabel = 'Out (ETA)';
                    $badge = 'danger';
                } elseif ($type === 'Locator' && ($arrival === '' || $arrival === '0' || $arrival === '0000-00-00 00:00:00')) {
                    $statusLabel = 'Out (Locator)';
                    $badge = 'warning';
                }
            }
        ?>
        <tr>
          <td><?= $empNo ?></td>
          <td><?= $name ?></td>
          <td><?= $pos ?></td>
          <td class="text-center"><span class="badge badge-<?= $badge ?>"><?= $statusLabel ?></span></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
