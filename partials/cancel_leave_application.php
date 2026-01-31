<?php
require_once __DIR__ . '/../includes/db_config.php';
session_start();

try {
    // admin dept(s)
    $adminDept = null;
    if (!empty($_SESSION['EmpID'])) {
        $stmt = $pdo->prepare("SELECT Dept FROM adminusers WHERE EmpNo = ? LIMIT 1");
        $stmt->execute([ (string)$_SESSION['EmpID'] ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Dept'])) $adminDept = $row['Dept'];
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
        $rows = [];
    } else {
        $placeholders = implode(',', array_fill(0, count($adminDepts), '?'));
        // list approved leaves with leave_dates
        $sql = "SELECT fl.LeaveID, fl.EmpNo, fl.EmployeeName, fl.LeaveTypeName, i.Dept,
                 MIN(ld.LeaveDate) AS RealDateFrom,
                 MAX(ld.LeaveDate) AS RealDateTo,
                 GROUP_CONCAT(DISTINCT DATE_FORMAT(ld.LeaveDate, '%b %d, %Y') ORDER BY ld.LeaveDate SEPARATOR ', ') AS DatesList,
                 -- RawDates contains date:isCancelled (0/1) pairs so UI can mark cancelled dates
                 GROUP_CONCAT(DISTINCT CONCAT(ld.LeaveDate, ':', IF(ld.IsCancelled IS NULL,0,ld.IsCancelled)) ORDER BY ld.LeaveDate SEPARATOR ',') AS RawDates
                FROM filedleave fl
                INNER JOIN i ON fl.EmpNo = i.EmpNo
                LEFT JOIN leave_dates ld ON ld.LeaveID = fl.LeaveID
                WHERE fl.Status = 'Approved' AND i.Dept IN ($placeholders)
                GROUP BY fl.LeaveID
                ORDER BY RealDateFrom DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($adminDepts);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    $rows = [];
}
?>

<h5 class="mt-2"><i class="fas fa-ban text-danger"></i> Cancel Leave Application</h5>
<div class="table-responsive">
<table class="table table-bordered table-hover">
  <thead class="bg-light">
    <tr>
      <th>LeaveID</th>
      <th>Employee</th>
      <th>Department</th>
      <th>Leave Type</th>
      <th>Dates</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="6" class="text-center text-muted">No approved leaves found.</td></tr>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['LeaveID']) ?></td>
          <td><?= htmlspecialchars($r['EmployeeName']) ?></td>
          <td><?= htmlspecialchars($r['Dept']) ?></td>
          <td><?= htmlspecialchars($r['LeaveTypeName'] ?? '--') ?></td>
          <td>
            <?php if (!empty($r['DatesList'])): ?>
                <?php
                // show each date as a button for cancelling; RawDates contains date:isCancelled
                $raw = explode(',', $r['RawDates']);
                foreach ($raw as $entry) {
                  $entry = trim($entry);
                  if ($entry === '') continue;
                  $parts = explode(':', $entry, 2);
                  $dateVal = $parts[0] ?? '';
                  $isCancelled = isset($parts[1]) && intval($parts[1]) === 1;
                  $label = date('M d, Y', strtotime($dateVal));
                  if ($isCancelled) {
                    echo "<button type=\"button\" class=\"btn btn-sm btn-secondary mr-1\" disabled title=\"Already cancelled\">{$label}</button>";
                  } else {
                    echo "<button type=\"button\" class=\"btn btn-sm btn-outline-danger mr-1 cancel-date-btn\" data-id=\"{$r['LeaveID']}\" data-date=\"{$dateVal}\">{$label}</button>";
                  }
                }
              ?>
            <?php else: ?>
              --
            <?php endif; ?>
          </td>
          <td>
            <small class="text-muted">Click a date to cancel. This refunds 1 day to leave credits.</small>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
</div>

<script>
  console.log('cancel_leave_application partial script loaded');
  $(document).off('click', '.cancel-date-btn');
  $(document).on('click', '.cancel-date-btn', function(){
    console.log('cancel-date-btn clicked', this, { id: $(this).data('id'), date: $(this).data('date') });
    const leaveId = $(this).data('id');
    const date = $(this).data('date');
    console.log('Swal available?', typeof Swal !== 'undefined', Swal && typeof Swal.fire === 'function');
    const swalPromise = (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') ? Swal.fire({
      title: 'Cancel this date? ',
      html: `Cancel <strong>${date}</strong> from Leave #${leaveId}. This will refund 1 day to the employee's credits. <br/><br/>Provide a reason (required):`,
      input: 'text',
      inputPlaceholder: 'Reason (required)',
      inputAttributes: { autocapitalize: 'sentences' },
      showCancelButton: true,
      confirmButtonText: 'Yes, cancel date',
      confirmButtonColor: '#d33',
      allowOutsideClick: false,
      // ensure a non-empty reason is provided
      inputValidator: (value) => {
        if (!value || !value.trim()) return 'Please provide a reason for the cancellation.';
        return null;
      }
    }) : null;

    function doCancelRequest(reason) {
      const pathParts = window.location.pathname.split('/').filter(Boolean);
      const appBase = pathParts.length ? '/' + pathParts[0] : '';
      const url = window.location.origin + appBase + '/api/leave/cancel_date.php';
      console.log('Cancel request', { url, leaveId, date, reason, appBase, pathname: window.location.pathname });
      $.ajax({
        url: url,
        method: 'POST',
        data: { leave_id: leaveId, date: date, reason: reason },
        dataType: 'json',
        timeout: 10000
      }).done(function(resp){
        console.log('Cancel response', resp);
        if (resp && resp.success) {
          if (Swal && Swal.fire) Swal.fire('Cancelled', 'Date cancelled and credits refunded.', 'success');
          // reload partial
          if (window.parent && typeof window.parent.loadCard === 'function') {
            window.parent.loadCard('../partials/cancel_leave_application.php');
          } else location.reload();
        } else {
          const errMsg = resp && resp.error ? resp.error : 'Failed to cancel date.';
          if (Swal && Swal.fire) Swal.fire('Error', errMsg,'error'); else alert(errMsg);
        }
      }).fail(function(xhr, status, err){
        console.error('Cancel failed', status, err, xhr && xhr.responseText);
        let msg = 'Failed to cancel date. See console for details.';
        if (xhr && xhr.status === 401) msg = 'Unauthorized. Please log in.';
        if (xhr && xhr.responseText) {
          try {
            const j = JSON.parse(xhr.responseText);
            if (j && j.error) msg = j.error;
          } catch(e) {
            // not JSON
          }
        }
        if (Swal && Swal.fire) Swal.fire('Error', msg,'error'); else alert(msg);
      });
    }

    if (swalPromise && typeof swalPromise.then === 'function') {
      swalPromise.then(function(res){
        console.log('swal resolved', res);
        if (!res) return;
        // Some Swal integrations return only {value: ...} without isConfirmed
        const confirmed = (typeof res.isConfirmed === 'boolean') ? res.isConfirmed : (res.value !== undefined);
        if (!confirmed) return;
        const reason = res.value || '';
        doCancelRequest(reason);
      }).catch(function(e){
        console.error('Swal promise error', e);
        if (Swal && Swal.fire) Swal.fire('Error', 'An error occurred with the dialog. See console.', 'error');
      });
    } else {
      console.error('SweetAlert not available; cannot show modal');
      if (Swal && Swal.fire) Swal.fire('Error', 'SweetAlert not available', 'error');
    }
    
    });
</script>
