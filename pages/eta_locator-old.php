<?php
// pages/eta_locator.php
require_once('../includes/db_config.php');
session_start();

$empNo = $_SESSION['EmpID'] ?? null;

// ================= Get Current Status =================
$currentStmt = $pdo->prepare("
    SELECT application_type, destination, travel_date, status
    FROM eta_locator 
    WHERE EmpNo = :empNo
    ORDER BY travel_date DESC, date_filed DESC
    LIMIT 1
");
$currentStmt->execute(['empNo' => $empNo]);
$currentRow = $currentStmt->fetch(PDO::FETCH_ASSOC);

if ($currentRow) {
    $currentStatus      = $currentRow['application_type'];
    $currentDestination = $currentRow['destination'];
    $currentDates       = date('M d, Y', strtotime($currentRow['travel_date']));
    $statusMap          = ['Approved' => 'success', 'Pending' => 'warning', 'Rejected' => 'danger'];
    $currentBadge       = $statusMap[$currentRow['status']] ?? 'secondary';
} else {
    $currentStatus = 'Office';
    $currentDestination = $currentDates = '';
    $currentBadge = 'secondary';
}
?>

<div class="row">
  <!-- Current Status Card -->
  <div class="col-md-4">
    <div class="card card-outline 
        <?= ($currentStatus === 'Office') ? 'card-secondary' : (($currentStatus === 'ETA') ? 'card-primary' : 'card-warning'); ?>">
      <div class="card-header">
        <h3 class="card-title">
          <?= ($currentStatus === 'Office') 
                ? '<i class="fas fa-building"></i> Current Status'
                : (($currentStatus === 'ETA') 
                    ? '<i class="fas fa-plane-departure"></i> Current ETA'
                    : '<i class="fas fa-map-marked-alt"></i> Current Locator'); ?>
        </h3>
      </div>
      <div class="card-body text-center">
        <?php if ($currentStatus === 'Office'): ?>
          <i class="fas fa-user-check fa-3x text-secondary"></i>
          <h4 class="mt-3">In the Office</h4>
        <?php else: ?>
          <i class="<?= ($currentStatus === 'ETA') ? 'fas fa-suitcase-rolling text-primary' : 'fas fa-map-marker-alt text-warning'; ?> fa-3x"></i>
          <h4 class="mt-3">
            <?= ($currentStatus === 'ETA') ? "Destination: $currentDestination" : "Location: $currentDestination"; ?>
          </h4>
          <p class="text-muted"><?= $currentDates ?></p>
          <h5><span class="badge badge-<?= $currentBadge ?>"><?= $currentStatus ?></span></h5>
        <?php endif; ?>

        <a href="#" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#etaLocatorModal">
          <i class="fas fa-plus-circle"></i> File New ETA / Locator
        </a>
      </div>
    </div>
  </div>

  <!-- Locator Summary (static for now) -->
  <div class="col-md-8">
    <div class="card card-info card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-map-marked-alt"></i> Locator Summary</h3>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <i class="fas fa-user fa-2x text-warning"></i>
            <h6 class="mt-2">Personal</h6>
            <span class="badge badge-warning">8 Filed</span>
          </div>
          <div class="col-6">
            <i class="fas fa-briefcase fa-2x text-success"></i>
            <h6 class="mt-2">Official Business</h6>
            <span class="badge badge-success">15 Filed</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// ================= Functions =================
function getHistory(PDO $pdo, string $empNo, string $type): array {
    $stmt = $pdo->prepare("SELECT * FROM eta_locator WHERE EmpNo = :empNo AND application_type = :type ORDER BY date_filed DESC");
    $stmt->execute(['empNo' => $empNo, 'type' => $type]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function renderHistoryTable(array $data, string $type): void {
    $isETA      = ($type === 'ETA');
    $columns    = $isETA
        ? ['Date Filed', 'Travel Date', 'Destination', 'Purpose', 'Status', 'Action']
        : ['Date Filed', 'Travel Date', 'Departure', 'Arrival', 'Type', 'Location', 'Status', 'Action'];
    $printFile  = $isETA ? 'print_eta.php' : 'print_locator.php';
    $cardClass  = $isETA ? 'card-secondary' : 'card-warning';
    $icon       = $isETA ? 'fa-history' : 'fa-map-pin';
    ?>
    <div class="row mt-3">
      <div class="col-12">
        <div class="card card-outline <?= $cardClass ?>">
          <div class="card-header">
            <h3 class="card-title"><i class="fas <?= $icon ?>"></i> <?= $type ?> History</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <?php foreach ($columns as $col): ?>
                    <th><?= $col ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data as $row): ?>
                <?php
                  $status = $row['status'];
                  $badge  = $status === 'Approved' ? 'success' : ($status === 'Pending' ? 'warning' : 'danger');
                  $rowClass = in_array($status, ['Cancelled', 'Rejected']) ? 'text-muted cancelled-row' : '';
                ?>
                <tr class="<?= $rowClass ?>">
                  <td><?= date('Y-m-d', strtotime($row['date_filed'])) ?></td>

                  <?php if ($isETA): ?>
                    <td><?= date('Y-m-d', strtotime($row['travel_date'])) ?></td>
                    <td><?= htmlspecialchars($row['destination']) ?></td>
                    <td>
                      <?= htmlspecialchars($row['business_type']) ?>
                      <?php if ($row['business_type'] === 'General Expense/Other' && !empty($row['other_purpose'])): ?>
                        - <?= htmlspecialchars($row['other_purpose']) ?>
                      <?php endif; ?>
                    </td>
                  <?php else: ?>
                    <td><?= date('Y-m-d', strtotime($row['travel_date'])) ?></td>
                    <td><?= date('g:iA', strtotime($row['intended_departure'])) ?></td>
                    <td><?= date('g:iA', strtotime($row['intended_arrival'])) ?></td>
                    <td>
                      <?php $badgeType = ($row['business_type'] === 'Official') ? 'success' : 'warning'; ?>
                      <span class="badge badge-<?= $badgeType ?>"><?= htmlspecialchars($row['business_type']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($row['destination']) ?></td>
                  <?php endif; ?>

                  <td><span class="badge badge-<?= $badge ?>"><?= $status ?></span></td>

                  <td>
                    <?php if ($status === 'Approved'): ?>
                      <?php if ($isETA): ?>
                        <a href="/CHRMIS/pdf_viewer_eta.php?id=<?= $row['id'] ?>" target="_blank" 
                          class="btn btn-sm btn-primary print-eta">
                            <i class="fas fa-print"></i> Print ETA
                        </a>
                      <?php else: ?>
                        <a href="/CHRMIS/pdf_viewer_locator.php?id=<?= $row['id'] ?>" target="_blank" 
                          class="btn btn-sm btn-primary print-locator">
                            <i class="fas fa-print"></i> Print Locator
                        </a>
                      <?php endif; ?>
                    <?php elseif ($status === 'Pending'): ?>
                      <button type="button" class="btn btn-sm btn-danger cancel-btn" data-id="<?= $row['id'] ?>">
                        <i class="fas fa-times"></i> Cancel
                      </button>
                    <?php else: ?>
                      —
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
    <?php
}


// ================= Fetch + Render =================
renderHistoryTable(getHistory($pdo, $empNo, 'ETA'), 'ETA');
renderHistoryTable(getHistory($pdo, $empNo, 'Locator'), 'Locator');

include __DIR__ . '/../partials/modals/modal_eta_locator.php';
?>

<script>
  $(document).on('click', '.cancel-btn', function() {
  const id = $(this).data('id');

  Swal.fire({
    title: 'Are you sure?',
    text: "This will cancel your application.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, cancel it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../api/api_eta_travel.php?cancel_eta_locator',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire('Cancelled!', response.message, 'success').then(()=>location.reload());
          } else {
            Swal.fire('Error!', response.message, 'error');
          }
        },
        error: function(xhr, status, error) {
          Swal.fire('Error!', 'Server error: ' + error, 'error');
        }
      });
    }
  });
});
</script>