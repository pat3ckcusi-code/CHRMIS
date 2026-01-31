<?php
require_once __DIR__ . '/../includes/initialize.php';
?>

<div class="mb-3">
  <h5 class="m-0">File Leave Without Pay (LWOP)</h5>
  <p class="text-muted small">Select an employee, choose date range (weekdays only), add a reason and submit.</p>
</div>

<div class="row">
  <div class="col-md-4">
    <label>Department</label>
    <select id="lwopDept" class="form-control">
      <option value="">All</option>
    </select>
  </div>
  <div class="col-md-8">
    <label>Employee</label>
    <select id="lwopEmployee" class="form-control">
      <option value="">Select an employee</option>
    </select>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-4">
    <label>Date From</label>
    <input type="date" id="lwopFrom" class="form-control">
  </div>
  <div class="col-md-4">
    <label>Date To</label>
    <input type="date" id="lwopTo" class="form-control">
  </div>
  <div class="col-md-4">
    <label>Preview</label>
    <div id="lwopPreview" class="form-control bg-light">0 weekdays</div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <label>Reason</label>
    <textarea id="lwopReason" class="form-control" rows="3"></textarea>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12 text-right">
    <button id="lwopSubmitBtn" class="btn btn-sm btn-primary">Submit LWOP</button>
  </div>
</div>

<script>
$(function(){
  function loadDepts() {
    // Build department list from employees endpoint
    $.getJSON('../api/get_department_employees.php', function(rows){
      const depts = {};
      rows.forEach(r => { if (r.Dept) depts[r.Dept] = true; });
      const opts = Object.keys(depts).sort();
      // Append options safely using jQuery to avoid double-encoding issues
      opts.forEach(function(d){
        $('#lwopDept').append($('<option>').val(d).text(d));
      });
    }).always(()=> loadEmployees(''));
  }

  function loadEmployees(dept) {
    const url = '../api/get_department_employees.php' + (dept ? '?dept=' + encodeURIComponent(dept) : '');
    $.getJSON(url, function(rows){
      let html = '<option value="">Select an employee</option>';
      rows.forEach(r => html += `<option value="${r.EmpNo}">${r.FullName} — ${r.Position || ''}</option>`);
      $('#lwopEmployee').html(html);
    });
  }

  $('#lwopDept').on('change', function(){ loadEmployees($(this).val()); });

  function countWeekdays(from, to) {
    const a = new Date(from); const b = new Date(to);
    if (isNaN(a) || isNaN(b) || b < a) return 0;
    let count = 0;
    for (let d = new Date(a); d <= b; d.setDate(d.getDate()+1)) {
      const w = d.getDay(); if (w !== 0 && w !== 6) count++;
    }
    return count;
  }

  function updatePreview() {
    const f = $('#lwopFrom').val(); const t = $('#lwopTo').val();
    if (!f || !t) {
      $('#lwopPreview').text('0 weekdays');
      $('#lwopSubmitBtn').prop('disabled', true);
      return;
    }
    const a = new Date(f); const b = new Date(t);
    if (isNaN(a) || isNaN(b) || b < a) {
      $('#lwopPreview').text('Invalid date range');
      $('#lwopSubmitBtn').prop('disabled', true);
      return;
    }
    const c = countWeekdays(f,t);
    $('#lwopPreview').text(c + ' weekdays');
    // enable submit only when employee is selected and days > 0
    const empSelected = !!$('#lwopEmployee').val();
    $('#lwopSubmitBtn').prop('disabled', !(empSelected && c > 0));
  }

  // update preview on date or employee changes
  $('#lwopFrom, #lwopTo').on('change', updatePreview);
  $('#lwopEmployee').on('change', updatePreview);

  $('#lwopSubmitBtn').on('click', function(){
    const emp = $('#lwopEmployee').val();
    const from = $('#lwopFrom').val(); const to = $('#lwopTo').val();
    const reason = $('#lwopReason').val();
    if (!emp) { Swal.fire('Error','Please select an employee','error'); return; }
    if (!from || !to) { Swal.fire('Error','Please select date range','error'); return; }
    const days = countWeekdays(from,to);
    if (new Date(to) < new Date(from)) { Swal.fire('Error','Date To cannot be before Date From','error'); return; }
    if (days <= 0) { Swal.fire('Error','Date range must include at least one weekday','error'); return; }

    Swal.fire({
      title: 'Confirm LWOP',
      html: `File LWOP for <strong>${$('#lwopEmployee option:selected').text()}</strong> for <strong>${days}</strong> days?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit'
    }).then(res => {
      if (!res.isConfirmed) return;
      $.post('../api/leave/mark_lwop.php', { empNo: emp, date_from: from, date_to: to, reason: reason }, function(resp){
        if (resp && resp.success) {
          Swal.fire('Filed','LWOP successfully filed','success');
          if (typeof window.loadCard === 'function') window.loadCard('../partials/manage_leave_credits.php');
        } else {
          Swal.fire('Error', resp && resp.error ? resp.error : 'Failed to file LWOP', 'error');
        }
      }, 'json').fail(()=> Swal.fire('Error','Server error filing LWOP','error'));
    });
  });

  loadDepts();
});
</script>
