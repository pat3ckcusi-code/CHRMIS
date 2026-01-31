<?php
session_start();
require_once('../includes/initialize.php');

if (!isset($_SESSION['Access']) == 'Admin') {
    echo "<p class='text-muted'>Not authorized to view mayor approvals.</p>";
    return;
}
$sql = "SELECT LeaveID, DateFiled, EmployeeName, Position, Office, LeaveTypeName, Reason, DateFrom, DateTo, TotalDays
        FROM filedleave
        WHERE Status = 'Recommended'
        ORDER BY DateFiled DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pendingCount = count($rows);

// Basic statistics and analysis datasets
$countsSql = "SELECT
    SUM(CASE WHEN Status = 'Recommended' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN Status = 'APPROVED' THEN 1 ELSE 0 END) AS approved,
    SUM(CASE WHEN Status = 'DISAPPROVED' THEN 1 ELSE 0 END) AS disapproved
  FROM filedleave";
$counts = $pdo->query($countsSql)->fetch(PDO::FETCH_ASSOC);
?>

<div>
  <ul class="nav nav-tabs" id="mayorTabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="stats-tab-btn" data-toggle="tab" href="#stats-tab" role="tab" aria-controls="stats-tab" aria-selected="true">Statistics</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="approvals-tab-btn" data-toggle="tab" href="#approvals-tab" role="tab" aria-controls="approvals-tab" aria-selected="false">
        Approvals <span id="approvalsBadge" class="badge badge-danger ml-1"><?= $pendingCount ?></span>
      </a>
    </li>
  </ul>

  <div class="tab-content mt-3" id="mayorTabsContent">
    <div class="tab-pane fade show active" id="stats-tab" role="tabpanel" aria-labelledby="stats-tab-btn">
      <div class="table-responsive">
        <h5>Habitual Leave Usage by Employee</h5>
        <table class="table table-bordered table-hover mt-3">
          <thead class="bg-light">
            <tr>
              <th>#</th>
              <th>Employee Name</th>
              <th>Position</th>
              <th>Office/Department</th>
              <th class="text-center">Applications</th>
              <th class="text-center">Total Leave Days</th>
              <th class="text-center">Avg Days per Application</th>
              <th class="text-center">Leaves on Mondays</th>
              <th class="text-center">Leaves on Fridays</th>
            </tr>
          </thead>
          <tbody id="habitualLeaveTableBody"></tbody>
        </table>
        <div id="habitualLeaveInfo" class="alert alert-info small" style="display:none;"></div>
        <!-- Modal for leave details -->
        <div class="modal fade" id="leaveDetailsModal" tabindex="-1" role="dialog" aria-labelledby="leaveDetailsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="leaveDetailsModalLabel">Leave Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="leaveDetailsModalBody">
                <!-- Details will be injected here -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>

        // For now, use static data. To upgrade to dynamic, replace this block with AJAX as before.
        // Static leave details for demonstration
        const staticLeaveDetails = {
          'Juan Dela Cruz': [
            { dateFiled: '2025-11-01', type: 'Sick Leave', from: '2025-11-02', to: '2025-11-03', days: 2, reason: 'Fever', status: 'Approved' },
            { dateFiled: '2025-10-10', type: 'Vacation Leave', from: '2025-10-15', to: '2025-10-18', days: 4, reason: 'Family trip', status: 'Approved' },
            { dateFiled: '2025-09-05', type: 'Sick Leave', from: '2025-09-07', to: '2025-09-07', days: 1, reason: 'Headache', status: 'Approved' },
            { dateFiled: '2025-08-20', type: 'Vacation Leave', from: '2025-08-25', to: '2025-08-26', days: 2, reason: 'Personal', status: 'Approved' },
            { dateFiled: '2025-07-01', type: 'Sick Leave', from: '2025-07-04', to: '2025-07-04', days: 1, reason: 'Checkup', status: 'Approved' },
            { dateFiled: '2025-06-10', type: 'Vacation Leave', from: '2025-06-12', to: '2025-06-13', days: 2, reason: 'Errands', status: 'Approved' },
            { dateFiled: '2025-05-15', type: 'Sick Leave', from: '2025-05-18', to: '2025-05-18', days: 1, reason: 'Flu', status: 'Approved' },
            { dateFiled: '2025-04-01', type: 'Vacation Leave', from: '2025-04-03', to: '2025-04-03', days: 1, reason: 'Personal', status: 'Approved' }
          ],
          'Maria Santos': [
            { dateFiled: '2025-11-05', type: 'Vacation Leave', from: '2025-11-10', to: '2025-11-12', days: 3, reason: 'Travel', status: 'Approved' },
            { dateFiled: '2025-10-01', type: 'Sick Leave', from: '2025-10-02', to: '2025-10-02', days: 1, reason: 'Migraine', status: 'Approved' },
            { dateFiled: '2025-09-15', type: 'Vacation Leave', from: '2025-09-20', to: '2025-09-21', days: 2, reason: 'Family', status: 'Approved' },
            { dateFiled: '2025-08-10', type: 'Sick Leave', from: '2025-08-11', to: '2025-08-11', days: 1, reason: 'Checkup', status: 'Approved' },
            { dateFiled: '2025-07-20', type: 'Vacation Leave', from: '2025-07-22', to: '2025-07-23', days: 2, reason: 'Personal', status: 'Approved' },
            { dateFiled: '2025-06-05', type: 'Sick Leave', from: '2025-06-06', to: '2025-06-06', days: 1, reason: 'Flu', status: 'Approved' },
            { dateFiled: '2025-05-10', type: 'Vacation Leave', from: '2025-05-12', to: '2025-05-12', days: 1, reason: 'Errands', status: 'Approved' }
          ]
          // Add more as needed
        };

        function renderHabitualLeaveTable(data) {
          const tbody = document.getElementById('habitualLeaveTableBody');
          if (!tbody) return;
          tbody.innerHTML = '';
          if (data.length === 0) {
            document.getElementById('habitualLeaveInfo').textContent = 'No habitual leave usage detected in the selected period.';
            document.getElementById('habitualLeaveInfo').style.display = '';
            return;
          } else {
            document.getElementById('habitualLeaveInfo').style.display = 'none';
          }
          data.forEach((e, idx) => {
            const avg = e.count > 0 ? (e.days / e.count).toFixed(2) : '0.00';
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${idx+1}</td><td><a href="#" class="leave-details-link" data-emp="${encodeURIComponent(e.name)}">${e.name}</a></td><td>${e.position}</td><td>${e.office}</td><td class="text-center">${e.count}</td><td class="text-center">${e.days}</td><td class="text-center">${avg}</td><td class="text-center">${e.mondays}</td><td class="text-center">${e.fridays}</td>`;
            tbody.appendChild(tr);
          });
        }

        function fetchHabitualLeaveData() {
          // Example static data with total leave days and leave patterns
          const staticData = [
            { name: 'Juan Dela Cruz', position: 'Admin Aide', office: 'HR Department', count: 8, days: 14, mondays: 3, fridays: 2 },
            { name: 'Maria Santos', position: 'Accountant', office: 'Accounting', count: 7, days: 12, mondays: 2, fridays: 2 },
            { name: 'Jose Rizal', position: 'Engineer II', office: 'Engineering', count: 6, days: 15, mondays: 1, fridays: 3 },
            { name: 'Ana Reyes', position: 'Nurse', office: 'Health Office', count: 5, days: 10, mondays: 1, fridays: 1 },
            { name: 'Pedro Gomez', position: 'Driver', office: 'Motorpool', count: 4, days: 8, mondays: 0, fridays: 2 },
            { name: 'Liza Soberano', position: 'Clerk', office: 'Treasury', count: 3, days: 6, mondays: 1, fridays: 0 },
            { name: 'Carlos Tan', position: 'Utility Worker', office: 'General Services', count: 2, days: 4, mondays: 0, fridays: 1 },
            { name: 'Grace Lee', position: 'Librarian', office: 'Library', count: 1, days: 2, mondays: 0, fridays: 0 }
          ];
          renderHabitualLeaveTable(staticData);
          // Attach click handler for modal
          $(document).off('click', '.leave-details-link').on('click', '.leave-details-link', function(e) {
            e.preventDefault();
            const emp = decodeURIComponent($(this).data('emp'));
            const details = staticLeaveDetails[emp];
            let html = '';
            if (details && details.length > 0) {
              html += `<h6>${emp} - Leave Applications</h6>`;
              html += '<div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr><th>Date Filed</th><th>Type</th><th>Date From</th><th>Date To</th><th>Days</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
              details.forEach(lv => {
                html += `<tr><td>${lv.dateFiled}</td><td>${lv.type}</td><td>${lv.from}</td><td>${lv.to}</td><td class="text-center">${lv.days}</td><td>${lv.reason}</td><td>${lv.status}</td></tr>`;
              });
              html += '</tbody></table></div>';
            } else {
              html = `<div class="alert alert-info">No leave details available for ${emp}.</div>`;
            }
            $('#leaveDetailsModalBody').html(html);
            $('#leaveDetailsModal').modal('show');
          });
        }

        $(document).ready(function() {
          fetchHabitualLeaveData();
        });
      </script>
    </div>

    <div class="tab-pane fade" id="approvals-tab" role="tabpanel" aria-labelledby="approvals-tab-btn">
      <div id="pendingAlert">
      <?php if ($pendingCount > 0): ?>
        <div class="alert alert-info">There are <strong id="pendingAlertCount"><?= $pendingCount ?></strong> application(s) awaiting mayor approval.</div>
      <?php endif; ?>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="bg-light">
            <tr>
              <th>Date Filed</th>
              <th>Employee Name</th>
              <th>Position</th>
              <th>Office</th>
              <th>Leave Type</th>
              <th>Date From</th>
              <th>Date To</th>
              <th class="text-center">Total Days</th>
              <th>Reason</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($pendingCount === 0): ?>
              <tr><td colspan="10" class="text-center text-muted">No applications awaiting mayor approval.</td></tr>
            <?php else: ?>
              <?php foreach ($rows as $r):
                $dateFrom = !empty($r['DateFrom']) ? date('M d, Y', strtotime($r['DateFrom'])) : '--';
                $dateTo = !empty($r['DateTo']) ? date('M d, Y', strtotime($r['DateTo'])) : '--';
                $dateFiled = !empty($r['DateFiled']) ? date('M d, Y', strtotime($r['DateFiled'])) : '--';
              ?>
                <tr id="leave-row-<?= intval($r['LeaveID']) ?>">
                  <td><?= htmlspecialchars($dateFiled) ?></td>
                  <td><?= htmlspecialchars($r['EmployeeName'] ?? '--') ?></td>
                  <td><?= htmlspecialchars($r['Position'] ?? '--') ?></td>
                  <td><?= htmlspecialchars($r['Office'] ?? '--') ?></td>
                  <td><?= htmlspecialchars($r['LeaveTypeName'] ?? '--') ?></td>
                  <td><?= htmlspecialchars($dateFrom) ?></td>
                  <td><?= htmlspecialchars($dateTo) ?></td>
                  <td class="text-center"><?= htmlspecialchars($r['TotalDays'] ?? 0) ?></td>
                  <td><?= htmlspecialchars($r['Reason'] ?: '--') ?></td>
                  <td class="text-center">
                    <button class="btn btn-success btn-sm approve-mayor-btn" data-id="<?= intval($r['LeaveID']) ?>"><i class="fas fa-check"></i></button>
                    <button class="btn btn-danger btn-sm reject-mayor-btn" data-id="<?= intval($r['LeaveID']) ?>"><i class="fas fa-times"></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    // initial data will be fetched from API to ensure up-to-date and consistent rendering
    let utilization = [];
    let utilLabels = [];
    let utilData = [];
    let heatmap = {};
    let risk = {};
    let top5 = [];

    const COLORS = {
      primary: '#007bff',
      success: '#28a745',
      warning: '#ffc107',
      danger: '#dc3545'
    };

    function fetchAnalysis(cb){
      $.getJSON('../api/leave_analysis.php', function(res){
        if (res.error) {
          console.error(res.error);
          const container = document.getElementById('mayorHeatmap');
          const statsArea = document.getElementById('stats-tab');
          if (container) container.innerHTML = '<div class="alert alert-warning">Statistics unavailable: ' + (res.message || res.error) + '</div>';
          // clear counts and badge
          $('#pendingCountSpan').text('0');
          $('#approvedCountSpan').text('0');
          $('#disapprovedCountSpan').text('0');
          $('#approvalsBadge').text('0');
          if (typeof window.updatePendingLeavesCount === 'function') {
            try { window.updatePendingLeavesCount(); } catch(e){ console.error('updatePendingLeavesCount error', e); }
          }
          if (typeof cb === 'function') cb();
          return;
        }
        utilization = res.utilization || [];
        utilLabels = utilization.map(r=>r.office);
        utilData = utilization.map(r=>r.utilization_pct);
        heatmap = res.heatmap || {dates:[],offices:{}};
        risk = res.risk || {};
        top5 = res.top5 || [];

        // update counts and badge
        if (res.counts) {
          $('#pendingCountSpan').text(res.counts.pending || 0);
          $('#approvedCountSpan').text(res.counts.approved || 0);
          $('#disapprovedCountSpan').text(res.counts.disapproved || 0);
        }
        $('#approvalsBadge').text(res.pendingCount || 0);
        if (res.pendingCount && res.pendingCount > 0) {
          $('#pendingAlert').html(`<div class="alert alert-info">There are <strong id="pendingAlertCount">${res.pendingCount}</strong> application(s) awaiting mayor approval.</div>`);
        } else {
          $('#pendingAlert').empty();
        }

        if (typeof window.updatePendingLeavesCount === 'function') {
          try { window.updatePendingLeavesCount(); } catch(e){ console.error('updatePendingLeavesCount error', e); }
        }

        if (typeof cb === 'function') cb();
      }).fail(()=>console.error('Failed to fetch analysis'));
    }

    let mayorUtilChartInstance = null;
    function renderUtilizationChart(){
      if (typeof Chart === 'undefined') return;
      const ctx = document.getElementById('mayorUtilChart');
      if (!ctx) return;
      if (mayorUtilChartInstance) mayorUtilChartInstance.destroy();
      mayorUtilChartInstance = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: { labels: utilLabels, datasets: [{ label: '% Utilization', data: utilData, backgroundColor: COLORS.primary, borderRadius: 4 }] },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
          scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }, x: { ticks: { autoSkip: false } } }
        }
      });
    }

    function renderTop5(){
      const list = document.getElementById('mayorTop5');
      if(!list) return;
      list.innerHTML = '';
      top5.forEach((r,idx)=>{
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `<span>${idx+1}. ${r.office}</span><span class="badge bg-primary">${r.utilization_pct}%</span>`;
        list.appendChild(li);
      });
    }

    function renderRiskList(){
      const container = document.getElementById('mayorRiskList');
      if(!container) return;
      container.innerHTML = '';
      Object.keys(risk).forEach(office => {
        const item = document.createElement('div');
        const s = risk[office];
        const color = s.status === 'green' ? 'bg-success' : (s.status === 'yellow' ? 'bg-warning' : 'bg-danger');
        item.className = 'mb-1 d-flex align-items-center';
        item.innerHTML = `<span class="badge ${color} mr-2" style="width:14px;height:14px;border-radius:3px;margin-right:8px;"></span><strong class="mr-2">${office}</strong><small class="text-muted">(peak ${s.peak_pct}%)</small>`;
        container.appendChild(item);
      });
    }

    function renderHeatmap(){
      const container = document.getElementById('mayorHeatmap');
      if(!container) return;
      const dates = heatmap.dates;
      const offices = Object.keys(heatmap.offices || {});
      const table = document.createElement('table');
      table.className = 'table table-sm table-bordered';
      const thead = document.createElement('thead');
      const headRow = document.createElement('tr');
      headRow.appendChild(document.createElement('th'));
      dates.forEach(d => { const th = document.createElement('th'); th.style.whiteSpace='nowrap'; th.innerText = d; headRow.appendChild(th); });
      thead.appendChild(headRow); table.appendChild(thead);
      const tbody = document.createElement('tbody');
      offices.forEach(off => {
        const tr = document.createElement('tr');
        const td0 = document.createElement('td'); td0.innerText = off; tr.appendChild(td0);
        dates.forEach(d => {
          const cell = document.createElement('td');
          const v = (heatmap.offices[off] && heatmap.offices[off][d]) ? heatmap.offices[off][d] : {count:0,pct:0};
          cell.innerText = v.count || '';
          // color by pct
          const p = v.pct;
          if (p >= 30) cell.style.backgroundColor = '#dc3545';
          else if (p >= 10) cell.style.backgroundColor = '#ffc107';
          else if (p > 0) cell.style.backgroundColor = '#28a745';
          tr.appendChild(cell);
        });
        tbody.appendChild(tr);
      });
      table.appendChild(tbody);
      container.innerHTML = ''; container.appendChild(table);
    }

    $(document).ready(function(){
      fetchAnalysis(function(){
        renderUtilizationChart();
        renderTop5();
        renderRiskList();
        renderHeatmap();
      });

      // wire approve/reject with processing feedback
      function refreshApprovalsTable() {
        const $tbody = $('table.table tbody');
        if ($tbody.find('tr[id^="leave-row-"]').length === 0) {
          $tbody.html('<tr><td colspan="10" class="text-center text-muted">No applications awaiting mayor approval.</td></tr>');
        }
      }

      // Update pending counts and badge in the UI immediately after approve/reject
      function updatePendingUI(decrementBy) {
        decrementBy = parseInt(decrementBy) || 1;
        // approvals badge
        const $badge = $('#approvalsBadge');
        let badgeVal = parseInt($badge.text()) || 0;
        badgeVal = Math.max(0, badgeVal - decrementBy);
        $badge.text(badgeVal);

        // pending alert count inside the alert box
        const $pendingAlertCount = $('#pendingAlertCount');
        if ($pendingAlertCount.length) {
          let pVal = parseInt($pendingAlertCount.text()) || 0;
          pVal = Math.max(0, pVal - decrementBy);
          if (pVal <= 0) {
            // remove alert entirely
            $('#pendingAlert').empty();
          } else {
            $pendingAlertCount.text(pVal);
          }
        }

        // any other span counters used by analysis can be adjusted conservatively
        const $pendingSpan = $('#pendingCountSpan');
        if ($pendingSpan.length) {
          let ps = parseInt($pendingSpan.text()) || 0;
          ps = Math.max(0, ps - decrementBy);
          $pendingSpan.text(ps);
        }

        // also update the small-box card on the main page (Leave Management card)
        const $mainCard = $('#pendingLeavesCount');
        if ($mainCard.length) {
          let mv = parseInt($mainCard.text()) || 0;
          mv = Math.max(0, mv - decrementBy);
          $mainCard.text(mv);
        }
      }

      $(document).on('click', '.approve-mayor-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Approve Leave?',
          text: 'This will approve the leave application.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
          if (!result.isConfirmed) return;
          Swal.fire({
            title: 'Processing...',
            text: 'Please wait while the leave application is being approved.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          $.ajax({
            url: '../api/api_leave.php?approve_leave',
            type: 'POST',
            dataType: 'json',
            data: { leave_id: id },
            success: function (resp) {
              Swal.close();
              if (resp && resp.success) {
                Swal.fire('Approved!', 'Leave application has been approved.', 'success').then(() => {
                  $('#leave-row-' + id).remove();
                  // immediately update UI counters so user sees change
                  try { updatePendingUI(1); } catch(e){ console.error('updatePendingUI error', e); }
                  fetchAnalysis(function () {
                    renderUtilizationChart();
                    renderTop5();
                    renderRiskList();
                    renderHeatmap();
                    refreshApprovalsTable();
                    if (typeof window.updatePendingLeavesCount === 'function') {
                      try { window.updatePendingLeavesCount(); } catch(e){ console.error('updatePendingLeavesCount error', e); }
                    }
                  });
                });
              } else {
                Swal.fire('Error!', resp.message || 'Failed to approve leave.', 'error');
              }
            },
            error: function () {
              Swal.close();
              Swal.fire('Error!', 'Server error occurred.', 'error');
            }
          });
        });
      });

      $(document).on('click', '.reject-mayor-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
          title: 'Reject Leave?',
          input: 'text',
          inputLabel: 'Reason for rejection',
          inputPlaceholder: 'Enter note for employee...',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, reject it!',
          preConfirm: (note) => {
            if (!note) Swal.showValidationMessage('Please enter a reason');
            return note;
          }
        }).then((result) => {
          if (!result.isConfirmed) return;
          Swal.fire({
            title: 'Processing...',
            text: 'Please wait while the leave application is being rejected.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          $.ajax({
            url: '../api/api_leave.php?reject_leave',
            type: 'POST',
            dataType: 'json',
            data: { leave_id: id, rejection_note: result.value },
            success: function (resp) {
              Swal.close();
              if (resp && resp.success) {
                Swal.fire('Rejected!', 'Leave application has been rejected.', 'success').then(() => {
                  $('#leave-row-' + id).remove();
                  try { updatePendingUI(1); } catch(e){ console.error('updatePendingUI error', e); }
                  fetchAnalysis(function () {
                    renderUtilizationChart();
                    renderTop5();
                    renderRiskList();
                    renderHeatmap();
                    refreshApprovalsTable();
                    if (typeof window.updatePendingLeavesCount === 'function') {
                      try { window.updatePendingLeavesCount(); } catch(e){ console.error('updatePendingLeavesCount error', e); }
                    }
                  });
                });
              } else {
                Swal.fire('Error!', resp.message || 'Failed to reject leave.', 'error');
              }
            },
            error: function () {
              Swal.close();
              Swal.fire('Error!', 'Server error occurred.', 'error');
            }
          });
        });
      });
    });
  })();
</script>
