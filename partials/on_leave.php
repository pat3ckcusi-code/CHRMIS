<?php
require_once __DIR__ . '/../includes/initialize.php';

// Fetch employees with their leave credits (left join)
try {
    $stmt = $pdo->query("SELECT
        i.EmpNo,
        CONCAT(i.Lname, ', ', i.Fname, ' ', i.Mname,
          CASE WHEN i.Extension = 'N/A' THEN '' ELSE CONCAT(' ', i.Extension) END) AS FullName,
        (SELECT v.Position FROM v WHERE v.EmpNo = i.EmpNo AND v.IndateTo = '0000-00-00' LIMIT 1) AS Position,
        i.Dept,
        COALESCE(lc.VL,0) AS VL,
        COALESCE(lc.SL,0) AS SL,
        COALESCE(lc.CL,0) AS CL,
        COALESCE(lc.SPL,0) AS SPL,
        COALESCE(lc.CTO,0) AS CTO
      FROM i
      LEFT JOIN leavecredits lc ON lc.EmpNo = i.EmpNo
      ORDER BY i.Lname, i.Fname");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $rows = [];
}
?>

<div class="mb-3 d-flex justify-content-between align-items-center">
  <!-- <h5 class="m-0">Manage Leave Credits</h5> -->
  <small class="text-muted">Enter tardiness/undertime (minutes) then calculate deduction (480 min = 1 day).</small>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-hover" id="leaveCreditsTable">
    <thead class="bg-light text-center">
      <tr>
        <th style="min-width:240px">Employee</th>
        <th>VL</th>
        <th>SL</th>
        <th>CL</th>
        <th>SPL</th>
        <th>CTO</th>
        <th>Tardiness (min)</th>
        <th>Undertime (min)</th>
        <th>Deduct From</th>
        <th>Preview</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr><td colspan="11" class="text-center text-muted">No employees found.</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr data-emp="<?= htmlspecialchars($r['EmpNo']) ?>">
            <td>
              <?= htmlspecialchars($r['FullName']) ?>
              <div class="text-muted small"><?= htmlspecialchars(($r['Position'] ?? '') . ' — ' . ($r['Dept'] ?? '')) ?></div>
            </td>
            <td class="text-center current-vl"><?= htmlspecialchars((float)$r['VL']) ?></td>
            <td class="text-center current-sl"><?= htmlspecialchars((float)$r['SL']) ?></td>
            <td class="text-center current-cl"><?= htmlspecialchars((float)$r['CL']) ?></td>
            <td class="text-center current-spl"><?= htmlspecialchars((float)$r['SPL']) ?></td>
            <td class="text-center current-cto"><?= htmlspecialchars((float)$r['CTO']) ?></td>
            <td class="text-center"><input type="number" min="0" step="1" class="form-control form-control-sm tardy" value="0"></td>
            <td class="text-center"><input type="number" min="0" step="1" class="form-control form-control-sm undertime" value="0"></td>
            <td class="text-center">
              <select class="form-control form-control-sm deduct-from">
                <option value="VL">VL</option>
                <option value="SL">SL</option>
                <option value="CL">CL</option>
                <option value="SPL">SPL</option>
              </select>
            </td>
            <td class="text-center preview">0.000 days</td>
            <td class="text-center">
              <button class="btn btn-sm btn-info calc-btn">Calc</button>
              <button class="btn btn-sm btn-success deduct-btn">Deduct</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
$(function(){
  // Calculate preview: total minutes -> days (480 minutes = 1 day)
  $(document).on('click', '.calc-btn', function(){
    const $tr = $(this).closest('tr');
    const t = Number($tr.find('.tardy').val()||0);
    const u = Number($tr.find('.undertime').val()||0);
    const totalMin = t + u;
    const days = totalMin / 480; // 8 hours = 1 day
    $tr.find('.preview').text(days.toFixed(3) + ' days');
  });

  // Perform deduction
  $(document).on('click', '.deduct-btn', function(){
    const $tr = $(this).closest('tr');
    const empNo = $tr.data('emp');
    const t = Number($tr.find('.tardy').val()||0);
    const u = Number($tr.find('.undertime').val()||0);
    const totalMin = t + u;
    if (totalMin <= 0) {
      Swal.fire('Info','Please enter tardiness or undertime minutes to deduct.','info');
      return;
    }
    const days = Number((totalMin / 480).toFixed(4));
    const leaveType = $tr.find('.deduct-from').val() || 'VL';

    Swal.fire({
      title: 'Confirm Deduction',
      html: `Deduct <strong>${days}</strong> days from <strong>${leaveType}</strong> for <strong>${$tr.find('td:first').text()}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, deduct',
    }).then((res)=>{
      if (!res.isConfirmed) return;
      const payload = { empNo: empNo, leaveType: leaveType, deduction: days, tardiness: t, undertime: u };
      $.post('../api/manage_leave_credits.php', payload, function(resp){
        if (resp && resp.success) {
          Swal.fire('Done','Leave credits updated.','success');
          // update UI
          const col = 'current-' + leaveType.toLowerCase();
          if (typeof resp.new_balance !== 'undefined') $tr.find('.' + col).text(resp.new_balance);
          $tr.find('.preview').text('0.000 days');
          $tr.find('.tardy, .undertime').val(0);
          // If DataTable is active, invalidate the row and redraw current page
          try {
            if (window.leaveCreditsDT) {
              window.leaveCreditsDT.row($tr.get(0)).invalidate().draw(false);
            }
          } catch (e) { console.warn('DataTable redraw failed', e); }
        } else {
          Swal.fire('Error', (resp && resp.error) ? resp.error : 'Failed to update credits', 'error');
        }
      }, 'json').fail(function(xhr){
        Swal.fire('Error','Server error while updating credits.','error');
      });
    });
  });

  // Initialize DataTable with pagination (10 rows per page)
  try {
    if ($.fn.DataTable) {
      window.leaveCreditsDT = $('#leaveCreditsTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        autoWidth: false,
        columnDefs: [ { orderable: false, targets: [6,7,8,9,10] } ]
      });
    }
  } catch (e) {
    console.warn('Failed to init DataTable for leaveCreditsTable', e);
  }

});
</script>
