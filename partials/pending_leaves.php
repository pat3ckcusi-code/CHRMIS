<?php
require_once __DIR__ . '/../includes/db_config.php';
session_start();

try {
    $adminDept = null;
    if (!empty($_SESSION['EmpID'])) {
        $stmt = $pdo->prepare("SELECT Dept FROM adminusers WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([ (string)$_SESSION['EmpID'] ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Dept'])) {
            $adminDept = $row['Dept'];
        }
    }

    // If user is HR (`Status` === 'leave'), show all departments from employees
    if (isset($_SESSION['Status']) && $_SESSION['Status'] === 'leave') {
      $stmt = $pdo->query("SELECT DISTINCT Dept FROM i WHERE Dept IS NOT NULL AND TRIM(Dept) <> '' ORDER BY Dept");
      $adminDepts = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } elseif ($adminDept === null) {
      $stmt = $pdo->query("SELECT DISTINCT Dept FROM adminusers WHERE Dept IS NOT NULL AND TRIM(Dept) <> ''");
      $adminDepts = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    } else {
      $adminDepts = [$adminDept];
    }

    if (empty($adminDepts)) {
      $pendingLeaves = [];
      $deptList = [];
    } else {
      $placeholders = implode(',', array_fill(0, count($adminDepts), '?'));

      // dept list for filter
      $stmtDept = $pdo->prepare("SELECT DISTINCT i.Dept FROM i WHERE i.Dept IN ($placeholders) ORDER BY i.Dept");
      $stmtDept->execute($adminDepts);
      $deptList = $stmtDept->fetchAll(PDO::FETCH_COLUMN, 0);

      // filters: dept, month (YYYY-MM), search q
      $filterDept = isset($_GET['dept']) ? trim($_GET['dept']) : '';
      $filterMonth = isset($_GET['month']) ? trim($_GET['month']) : '';
      $searchQ = isset($_GET['q']) ? trim($_GET['q']) : '';

      // Build base SQL with aggregation from leave_dates (include RawDates in SELECT)
      $baseSql = "SELECT fl.*, i.Fname, i.Lname, i.Dept,
             MIN(ld.LeaveDate) AS RealDateFrom,
             MAX(ld.LeaveDate) AS RealDateTo,
             COUNT(ld.LeaveDate) AS RealTotalDays,
             GROUP_CONCAT(DISTINCT DATE_FORMAT(ld.LeaveDate, '%b %d, %Y') ORDER BY ld.LeaveDate SEPARATOR ', ') AS DatesList,
             GROUP_CONCAT(DISTINCT CONCAT(ld.LeaveDate, ':', IF(ld.IsCancelled IS NULL,0,ld.IsCancelled)) ORDER BY ld.LeaveDate SEPARATOR ',') AS RawDates
        FROM filedleave fl
        INNER JOIN i ON fl.EmpNo = i.EmpNo
        LEFT JOIN leave_dates ld ON ld.LeaveID = fl.LeaveID AND (ld.IsCancelled = 0 OR ld.IsCancelled IS NULL)
        WHERE fl.Status IN ('For Recommendation','Recommended')";

      $params = [];

      // department filtering
      if ($filterDept !== '' && $filterDept !== 'ALL' && in_array($filterDept, $deptList)) {
        $baseSql .= " AND i.Dept = ?";
        $params[] = $filterDept;
      } else {
        $baseSql .= " AND i.Dept IN ($placeholders)";
        foreach ($adminDepts as $d) $params[] = $d;
      }

      // month filter: expect YYYY-MM; filter if any leave_dates in that month belong to the LeaveID
      if ($filterMonth !== '' && $filterMonth !== 'ALL') {
        // validate format YYYY-MM
        if (preg_match('/^(\d{4})-(\d{2})$/', $filterMonth, $m)) {
          $y = $m[1]; $mo = $m[2];
          $baseSql .= " AND EXISTS (SELECT 1 FROM leave_dates ld2 WHERE ld2.LeaveID = fl.LeaveID AND YEAR(ld2.LeaveDate) = ? AND MONTH(ld2.LeaveDate) = ? AND (ld2.IsCancelled = 0 OR ld2.IsCancelled IS NULL))";
          $params[] = $y; $params[] = (int)$mo;
        }
      }

      // search query: match employee name or leave type or leave id
      if ($searchQ !== '') {
        $qLike = '%' . $searchQ . '%';
        $baseSql .= " AND (CONCAT(i.Fname, ' ', i.Lname) LIKE ? OR fl.LeaveTypeName LIKE ? OR fl.LeaveID = ? )";
        $params[] = $qLike; $params[] = $qLike; $params[] = is_numeric($searchQ) ? (int)$searchQ : 0;
      }

      // finalize SQL with grouping and ordering
      $finalSql = $baseSql . " GROUP BY fl.LeaveID ORDER BY RealDateFrom DESC, RealDateTo DESC";
      $stmt = $pdo->prepare($finalSql);
      $stmt->execute($params);
      $pendingLeaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Throwable $e) {
    $pendingLeaves = [];
}

?>

<div class="d-flex justify-content-between align-items-center mb-2">
  <h5 class="mb-0"><i class="fas fa-hourglass-half text-secondary"></i> Pending Leave Requests (View Only)</h5>
  <div class="d-flex">
    <select id="pendingDeptFilter" class="form-control form-control-sm mr-2">
      <option value="ALL">All Departments</option>
      <?php foreach (($deptList ?? []) as $d): ?>
        <option value="<?= htmlspecialchars($d) ?>" <?= (isset($_GET['dept']) && $_GET['dept']=== $d) ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
      <?php endforeach; ?>
    </select>

    <select id="pendingMonthFilter" class="form-control form-control-sm mr-2">
      <option value="ALL">All Months</option>
      <?php
        $now = new DateTime();
        for ($i = 0; $i < 12; $i++) {
          $dt = (clone $now)->modify("-{$i} months");
          $val = $dt->format('Y-m');
          $label = $dt->format('M Y');
          $sel = (isset($_GET['month']) && $_GET['month'] === $val) ? 'selected' : '';
          echo "<option value=\"{$val}\" {$sel}>{$label}</option>";
        }
      ?>
    </select>

    <input id="pendingSearch" type="search" class="form-control form-control-sm" placeholder="Search name, type, or LeaveID" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
  </div>
</div>
<div class="table-responsive">
<table class="table table-bordered table-hover">
  <thead class="bg-light">
    <tr>
      <th>Employee</th>
      <th>Department</th>
      <th>Leave Type</th>
      <th>Date From</th>
      <th>Date To</th>
      <th>Days</th>
      <th>Status</th>
      <th>Date Filed</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($pendingLeaves)): ?>
      <tr><td colspan="8" class="text-center text-muted">No pending leave requests.</td></tr>
    <?php else: ?>
      <?php foreach ($pendingLeaves as $app): ?>
          <?php
            if (isset($app['RealTotalDays']) && is_numeric($app['RealTotalDays'])) {
                $numDays = (int)$app['RealTotalDays'];
            } elseif (isset($app['TotalDays']) && is_numeric($app['TotalDays'])) {
                $numDays = (int)$app['TotalDays'];
            } else {
                $from = !empty($app['RealDateFrom']) ? $app['RealDateFrom'] : ($app['DateFrom'] ?? null);
                $to = !empty($app['RealDateTo']) ? $app['RealDateTo'] : ($app['DateTo'] ?? null);
                if ($from && $to) {
                    $dateFrom = new DateTime($from);
                    $dateTo = new DateTime($to);
                    $interval = $dateFrom->diff($dateTo);
                    $numDays = $interval->days + 1;
                } else {
                    $numDays = 0;
                }
            }
          ?>
          <tr>
            <td><?= htmlspecialchars($app['Fname'] . ' ' . $app['Lname']) ?></td>
            <td><?= htmlspecialchars($app['Dept']) ?></td>
            <td><?= htmlspecialchars($app['LeaveTypeName'] ?? '--') ?></td>
            <td><?= htmlspecialchars(!empty($app['RealDateFrom']) ? date('M d, Y', strtotime($app['RealDateFrom'])) : (isset($app['DateFrom']) ? date('M d, Y', strtotime($app['DateFrom'])) : '--')) ?></td>
            <td><?= htmlspecialchars(!empty($app['RealDateTo']) ? date('M d, Y', strtotime($app['RealDateTo'])) : (isset($app['DateTo']) ? date('M d, Y', strtotime($app['DateTo'])) : '--')) ?></td>
            <td>
              <?php if (!empty($app['RawDates'])): ?>
                <?php
                  $pairs = explode(',', $app['RawDates']);
                  $out = [];
                  foreach ($pairs as $pair) {
                    $pair = trim($pair);
                    if ($pair === '') continue;
                    [$d, $c] = array_pad(explode(':', $pair, 2), 2, '0');
                    $label = htmlspecialchars(date('M d, Y', strtotime($d)));
                    if (intval($c) === 1) {
                      $out[] = "<span class=\"text-muted\">{$label} (cancelled)</span>";
                    } else {
                      $out[] = $label;
                    }
                  }
                  echo implode(', ', $out);
                ?>
              <?php else: ?>
                --
              <?php endif; ?>
            </td>
            <td><?= $numDays ?></td>
            <td><span class="badge badge-info"><?= htmlspecialchars($app['Status']) ?></span></td>
            <td><?= htmlspecialchars(date('M d, Y', strtotime($app['DateFiled'] ?? ($app['RealDateFrom'] ?? ($app['DateFrom'] ?? 'now'))))) ?></td>
          </tr>
        <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
</div>

  <script>
    (function() {
      const parentLoad = (window.parent && typeof window.parent.loadCard === 'function') ? window.parent.loadCard : null;
      function reload(dept, month, q) {
        const params = new URLSearchParams();
        if (dept) params.set('dept', dept);
        if (month) params.set('month', month);
        if (q) params.set('q', q);
        const url = '../partials/pending_leaves.php?' + params.toString();
        if (parentLoad) parentLoad(url); else window.location.href = url;
      }

      const deptSel = document.getElementById('pendingDeptFilter');
      const monthSel = document.getElementById('pendingMonthFilter');
      const searchEl = document.getElementById('pendingSearch');

      if (deptSel) deptSel.addEventListener('change', function(){ reload(this.value || 'ALL', monthSel ? monthSel.value : '', searchEl ? searchEl.value.trim() : ''); });
      if (monthSel) monthSel.addEventListener('change', function(){ reload(deptSel ? deptSel.value : 'ALL', this.value || 'ALL', searchEl ? searchEl.value.trim() : ''); });

      // debounce search
      let to = null;
      if (searchEl) searchEl.addEventListener('input', function(){
        clearTimeout(to);
        to = setTimeout(()=> reload(deptSel ? deptSel.value : 'ALL', monthSel ? monthSel.value : '', this.value.trim()), 400);
      });
    })();
  </script>
