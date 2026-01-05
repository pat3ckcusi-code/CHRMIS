<?php
// pages/eta_locator.php
require_once('../includes/db_config.php');
session_start();

$empNo = $_SESSION['EmpID'] ?? null;
if (!$empNo) {
    echo "<p class='text-danger text-center'>No active employee session found.</p>";
    exit;
}


$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$selectedYear  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$monthName     = date('F', mktime(0,0,0,$selectedMonth,10));

$today = date('Y-m-d');

// 1. Check for any ETA/Locator for today
$todayStmt = $pdo->prepare("
    SELECT *
    FROM eta_locator
    WHERE EmpNo = :empNo
      AND travel_date = :today
    ORDER BY FIELD(application_type, 'Locator', 'ETA'), date_filed DESC
    LIMIT 1
");
$todayStmt->execute(['empNo' => $empNo, 'today' => $today]);
$todayRow = $todayStmt->fetch(PDO::FETCH_ASSOC);

if ($todayRow) {
    $currentApplicationType = $todayRow['application_type'];
    $currentDestination     = $todayRow['destination'];
    $currentTravelDate      = date('M d, Y', strtotime($todayRow['travel_date']));
    $currentArrivalDate     = !empty($todayRow['arrival_date']) ? date('M d, Y', strtotime($todayRow['arrival_date'])) : '-';
    $businessType = strtolower($todayRow['business_type'])==='general expense/other'
        ? $todayRow['business_type'].' - '.$todayRow['other_purpose']
        : $todayRow['business_type'];
    $travelDetails = $todayRow['travel_detail'];
    $statusMap = ['Approved'=>'success','Pending'=>'warning','Rejected'=>'danger'];
    $currentBadge = $statusMap[$todayRow['status']] ?? 'secondary';
} else {
    // No ETA/Locator for today, show "Office"
    $currentApplicationType = 'Office';
    $currentDestination = $currentTravelDate = $currentArrivalDate = $businessType = $travelDetails = '-';
    $currentBadge = 'secondary';
}

// Monthly Summary 
// Personal count (Locator only)
$stmtPersonal = $pdo->prepare("
    SELECT COUNT(*) FROM eta_locator
    WHERE EmpNo = :empNo
      AND MONTH(date_filed) = :month
      AND YEAR(date_filed) = :year
      AND business_type = 'Personal'
      AND application_type = 'Locator'
");
$stmtPersonal->execute(['empNo'=>$empNo,'month'=>$selectedMonth,'year'=>$selectedYear]);
$personalCount = (int)$stmtPersonal->fetchColumn();

// Official Business count (Locator only)
$stmtOfficial = $pdo->prepare("
    SELECT COUNT(*) FROM eta_locator
    WHERE EmpNo = :empNo
      AND MONTH(date_filed) = :month
      AND YEAR(date_filed) = :year
      AND business_type != 'Personal'
      AND application_type = 'Locator'
");
$stmtOfficial->execute(['empNo'=>$empNo,'month'=>$selectedMonth,'year'=>$selectedYear]);
$officialCount = (int)$stmtOfficial->fetchColumn();


//  Fetch History with Month/Year Filter 
function getHistory(PDO $pdo, string $empNo, string $type, int $month, int $year): array {
    $stmt = $pdo->prepare("
        SELECT * FROM eta_locator
        WHERE EmpNo = :empNo
          AND application_type = :type
          AND MONTH(date_filed) = :month
          AND YEAR(date_filed) = :year
        ORDER BY date_filed DESC
    ");
    $stmt->execute([
        'empNo' => $empNo,
        'type'  => $type,
        'month' => $month,
        'year'  => $year
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function renderHistoryTable(array $data, string $type): void {
    $isETA = $type==='ETA';
    $columns = $isETA
        ? ['Date Filed','Travel Date','Destination','Purpose','Travel Details','Status','Action']
        : ['Date Filed','Travel Date','Departure','Arrival','Business Type','Destination','Travel Details','Status','Action'];
    $cardClass = $isETA ? 'card-secondary' : 'card-warning';
    $icon = $isETA ? 'fa-history' : 'fa-map-pin';
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
                            <tr><?php foreach($columns as $col): ?><th><?= $col ?></th><?php endforeach; ?></tr>
                        </thead>
                        <tbody>
                        <?php foreach($data as $row):
                            $status = $row['status'];
                            $badge  = $status==='Approved'?'success':($status==='Pending'?'warning':'danger');
                            $rowClass = in_array($status,['Cancelled','Rejected'])?'text-muted cancelled-row':'';
                        ?>
                            <tr class="<?= $rowClass ?>">
                                <td><?= date('Y-m-d', strtotime($row['date_filed'])) ?></td>
                                <?php if($isETA): ?>
                                    <td><?= date('Y-m-d', strtotime($row['travel_date'])) ?></td>
                                    <td><?= htmlspecialchars($row['destination']) ?></td>
                                    <td><?= strtolower($row['business_type'])==='general expense/other'?htmlspecialchars($row['business_type'].' - '.$row['other_purpose']):htmlspecialchars($row['business_type']) ?></td>
                                    <td><?= htmlspecialchars($row['travel_detail']) ?></td>
                                <?php else: ?>
                                    <td><?= date('Y-m-d', strtotime($row['travel_date'])) ?></td>
                                    <td><?= date('g:iA', strtotime($row['intended_departure'])) ?></td>
                                    <td><?= date('g:iA', strtotime($row['intended_arrival'])) ?></td>
                                    <td><?= strtolower($row['business_type'])==='general expense/other'?htmlspecialchars($row['business_type'].' - '.$row['other_purpose']):htmlspecialchars($row['business_type']) ?></td>
                                    <td><?= htmlspecialchars($row['destination']) ?></td>
                                    <td><?= htmlspecialchars($row['travel_detail']) ?></td>
                                <?php endif; ?>
                                <td><span class="badge badge-<?= $badge ?>"><?= $status ?></span></td>
                                <td>
                                    <?php if($status==='Approved'): ?>
                                        <?php
                                            $hasArrivalTime = !empty($row['Arrival_Time']) && $row['Arrival_Time'] !== '0000-00-00 00:00:00';
                                            if($hasArrivalTime):
                                        ?>
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Cannot print after arrival time recorded">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                        <?php else: ?>
                                            <a href="../pdf_viewer_<?= strtolower($type) ?>.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</a>
                                        <?php endif; ?>
                                    <?php elseif($status==='Pending'): ?>
                                        <button type="button" class="btn btn-sm btn-danger cancel-eta-btn" data-id="<?= $row['id'] ?>"><i class="fas fa-times"></i> Cancel</button>
                                    <?php else: ?> — <?php endif; ?>
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

//  Render 
?>

<div class="row">
    <!-- Current Status Card -->
    <div class="col-md-4">
        <div class="card card-outline <?= ($currentApplicationType==='Office')?'card-secondary':(($currentApplicationType==='ETA')?'card-primary':'card-warning'); ?>">
            <div class="card-header">
                <h3 class="card-title">
                    <?= ($currentApplicationType==='Office')?'<i class="fas fa-building"></i> Current Status':(($currentApplicationType==='ETA')?'<i class="fas fa-plane-departure"></i> Current ETA':'<i class="fas fa-map-marked-alt"></i> Current Locator'); ?>
                </h3>
            </div>
            <div class="card-body text-center">
                <?php if($currentApplicationType==='Office'): ?>
                    <i class="fas fa-user-check fa-3x text-secondary"></i>
                    <h4 class="mt-3">In the Office</h4>
                <?php else: ?>
                    <i class="<?= ($currentApplicationType==='ETA')?'fas fa-suitcase-rolling text-primary':'fas fa-map-marker-alt text-warning'; ?> fa-3x"></i>
                    <h4 class="mt-3"><?= ($currentApplicationType==='ETA')?"Destination: $currentDestination":"Location: $currentDestination"; ?></h4>
                    <p class="text-muted"><?= $currentTravelDate ?></p>
                    <h5><span class="badge badge-<?= $currentBadge ?>"><?= $currentApplicationType ?></span></h5>
                <?php endif; ?>
                <a href="#" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#etaLocatorModal">
                    <i class="fas fa-plus-circle"></i> File New ETA / Locator
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="col-md-8">
        <div class="card card-info card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-map-marked-alt"></i> Locator Summary</h3>
                <div>
                  <a href="#" class="btn btn-sm btn-secondary month-nav" data-month="<?= $selectedMonth-1===0?12:$selectedMonth-1 ?>" data-year="<?= $selectedMonth-1===0?$selectedYear-1:$selectedYear ?>">&lt;</a>
                  <span class="mx-2"><?= $monthName ?> <?= $selectedYear ?></span>
                  <a href="#" class="btn btn-sm btn-secondary month-nav" data-month="<?= $selectedMonth+1===13?1:$selectedMonth+1 ?>" data-year="<?= $selectedMonth+1===13?$selectedYear+1:$selectedYear ?>">&gt;</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <i class="fas fa-user fa-2x text-warning"></i>
                        <h6 class="mt-2">Personal</h6>
                        <span class="badge badge-warning"><?= $personalCount ?> Filed</span>
                    </div>
                    <div class="col-6">
                        <i class="fas fa-briefcase fa-2x text-success"></i>
                        <h6 class="mt-2">Official Business</h6>
                        <span class="badge badge-success"><?= $officialCount ?> Filed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Render History Tables
renderHistoryTable(getHistory($pdo,$empNo,'ETA',$selectedMonth,$selectedYear),'ETA');
renderHistoryTable(getHistory($pdo,$empNo,'Locator',$selectedMonth,$selectedYear),'Locator');

include __DIR__ . '/../partials/modals/modal_eta_locator.php';
?>

<script>
 $(document).on('click', '.cancel-eta-btn', function() {
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

//  AJAX Month Navigation 
$(document).on('click', '.month-nav', function(e){
    e.preventDefault();
    const month = $(this).data('month');
    const year  = $(this).data('year');
    const activeTab = $('#dashboardTabs .nav-link.active').data('file');

    // Reload current tab with new month/year
    $('#tabContent').html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $.get(activeTab, { month: month, year: year }, function(data){
        $('#tabContent').html(data);
    });
});
</script>
