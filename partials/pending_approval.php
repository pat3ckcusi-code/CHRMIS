<?php
session_start();
require_once('../includes/initialize.php');

// Get AO's department
$dept = $_SESSION['Dept'];

// Fetch pending Leave applications
$sqlLeave = "SELECT DISTINCT fl.*, i.Fname, i.Lname, i.Dept
             FROM filedleave fl
             INNER JOIN i ON fl.EmpNo = i.EmpNo
             WHERE i.Dept = ?
               AND fl.Status = 'For Recommendation'
             ORDER BY fl.DateFiled DESC";

$stmtLeave = $pdo->prepare($sqlLeave);
$stmtLeave->execute([$dept]);
$pendingLeaves = $stmtLeave->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending ETA applications
$sqlETA = "SELECT el.*, i.Fname, i.Lname
           FROM eta_locator el
           INNER JOIN i ON el.EmpNo = i.EmpNo
           WHERE i.Dept = ?
             AND el.status = 'Pending'
             AND el.application_type = 'ETA'
           ORDER BY el.date_filed DESC";

$stmtETA = $pdo->prepare($sqlETA);
$stmtETA->execute([$dept]);
$pendingETA = $stmtETA->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending Locator applications
$sqlLocator = "SELECT el.*, i.Fname, i.Lname
               FROM eta_locator el
               INNER JOIN i ON el.EmpNo = i.EmpNo
               WHERE i.Dept = ?
                 AND el.status = 'Pending'
                 AND el.application_type = 'Locator'
               ORDER BY el.date_filed DESC";

$stmtLocator = $pdo->prepare($sqlLocator);
$stmtLocator->execute([$dept]);
$pendingLocator = $stmtLocator->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Pending Leave Applications -->
<h5 class="mt-4"><i class="fas fa-file-contract text-primary"></i> Pending Leave Applications</h5>
<table class="table table-bordered table-hover">
  <thead class="bg-light">
    <tr>
      <th>Employee Name</th>
      <th>Leave Type</th>
      <th>Date From</th>
      <th>Date To</th>
      <th>Days</th>
      <th>Reason</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($pendingLeaves) > 0): ?>
      <?php foreach ($pendingLeaves as $app): ?>
        <?php
          $dateFrom = new DateTime($app['DateFrom']);
          $dateTo = new DateTime($app['DateTo']);
          $interval = $dateFrom->diff($dateTo);
          $numDays = $interval->days + 1;
        ?>
        <tr>
          <td><?= htmlspecialchars($app['Fname'] . ' ' . $app['Lname']) ?></td>
          <td><?= htmlspecialchars($app['LeaveTypeName'] ?: '--') ?></td>
          <td><?= htmlspecialchars(date("M d, Y", strtotime($app['DateFrom']))) ?></td>
          <td><?= htmlspecialchars(date("M d, Y", strtotime($app['DateTo']))) ?></td>
          <td><?= $numDays ?></td>
          <td><?= htmlspecialchars($app['Reason'] ?: '--') ?></td>
          <td><span class="badge badge-info">For Recommendation</span></td>
          <td>
            <button class="btn btn-success btn-sm approve-leave-btn" data-id="<?= $app['LeaveID'] ?>"><i class="fas fa-check"></i> Approve</button>
            <button class="btn btn-danger btn-sm reject-leave-btn" data-id="<?= $app['LeaveID'] ?>"><i class="fas fa-times"></i> Reject</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="8" class="text-center text-muted">No pending leave applications.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- ETA Pending Applications -->
<h5 class="mt-4"><i class="fas fa-history text-secondary"></i> Pending ETA Applications</h5>
<table class="table table-bordered table-hover text-center">
  <thead class="bg-light">
    <tr>
      <th>Employee Name</th>
      <th>Destination</th>
      <th>Travel Date</th>
      <th>Purpose</th>
      <th>Travel Details</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($pendingETA) > 0): ?>
      <?php foreach ($pendingETA as $app): ?>
        <tr>
          <td><?= htmlspecialchars($app['Fname'] . ' ' . $app['Lname']) ?></td>
          <td><?= htmlspecialchars($app['destination'] ?: '--') ?></td>
          <td><?= htmlspecialchars(date("Y-m-d", strtotime($app['travel_date']))) ?></td>
          <td>
            <?= htmlspecialchars($app['business_type']) ?>
            <?php if ($app['business_type'] === 'General Expense/Other' && !empty($app['other_purpose'])): ?>
              - <?= htmlspecialchars($app['other_purpose']) ?>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($app['travel_detail'] ?: '--') ?></td>
          <td><span class="badge badge-warning"><?= htmlspecialchars($app['status']) ?></span></td>
          <td>
            <button class="btn btn-success btn-sm approve-btn" data-id="<?= $app['id'] ?>">Approve</button>
            <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $app['id'] ?>">Reject</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7">No pending ETA applications.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- Locator Pending Applications -->
<h5 class="mt-4"><i class="fas fa-map-pin text-warning"></i> Pending Locator Applications</h5>
<table class="table table-bordered table-hover text-center">
  <thead class="bg-light">
    <tr>
      <th>Employee Name</th>
      <th>Business Type</th>
      <th>Location</th>
      <th>Departure</th>
      <th>Arrival</th>
      <th>Travel Details</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($pendingLocator) > 0): ?>
      <?php foreach ($pendingLocator as $app): ?>
        <tr>
          <td><?= htmlspecialchars($app['Fname'] . ' ' . $app['Lname']) ?></td>
          <td>
            <?php $badge = ($app['business_type'] === 'Official') ? 'success' : 'warning'; ?>
            <span class="badge badge-<?= $badge ?>"><?= htmlspecialchars($app['business_type']) ?></span>
          </td>
          <td><?= htmlspecialchars($app['destination'] ?: '--') ?></td>
          <td><?= date("g:iA", strtotime($app['intended_departure'])) ?></td>
          <td><?= date("g:iA", strtotime($app['intended_arrival'])) ?></td>
          <td><?= htmlspecialchars($app['travel_detail'] ?: '--') ?></td>
          <td><span class="badge badge-warning"><?= htmlspecialchars($app['status']) ?></span></td>
          <td>
            <button class="btn btn-success btn-sm approve-btn" data-id="<?= $app['id'] ?>">Approve</button>
            <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $app['id'] ?>">Reject</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="8">No pending Locator applications.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
  // Leave Application Handlers
  $(document).on('click', '.approve-leave-btn', function() {
    const leaveId = $(this).data('id');
    
    Swal.fire({
      title: 'Approve Leave Application?',
      text: "This will approve the leave application.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../api/api_leave.php?approve_leave',
          type: 'POST',
          data: { leave_id: leaveId },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              Swal.fire('Approved!', 'Leave application has been approved.', 'success').then(() => location.reload());
            } else {
              Swal.fire('Error!', response.message || 'Failed to approve leave.', 'error');
            }
          },
          error: function() {
            Swal.fire('Error!', 'Server error occurred.', 'error');
          }
        });
      }
    });
  });

  $(document).on('click', '.reject-leave-btn', function() {
    const leaveId = $(this).data('id');
    
    Swal.fire({
      title: 'Reject Leave Application?',
      text: "This will reject the leave application.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../api/api_leave.php?reject_leave',
          type: 'POST',
          data: { leave_id: leaveId },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              Swal.fire('Rejected!', 'Leave application has been rejected.', 'error').then(() => location.reload());
            } else {
              Swal.fire('Error!', response.message || 'Failed to reject leave.', 'error');
            }
          },
          error: function() {
            Swal.fire('Error!', 'Server error occurred.', 'error');
          }
        });
      }
    });
  });
</script>
