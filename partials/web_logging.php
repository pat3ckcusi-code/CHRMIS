<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');
date_default_timezone_set('Asia/Manila');

$currentDate = date('Y-m-d');
$currentTime = date('H:i');

$access = isset($_SESSION['access_level']) ? $_SESSION['access_level'] : '';

?>

<div class="card">
  <div class="card-header"><h3 class="card-title">Web-Based Logging (Manual Entry)</h3></div>
  <div class="card-body">
    <form id="webLogForm">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Department</label>
          <input type="text" class="form-control" value="<?php echo isset($_SESSION['Dept'])?htmlspecialchars($_SESSION['Dept']):''; ?>" readonly>
        </div>
        <div class="form-group col-md-4">
          <label>Employee</label>
          <div style="position:relative">
            <input type="text" id="employee_search" class="form-control" placeholder="Type name or EmpNo to search" autocomplete="off" required>
            <input type="hidden" id="employee_id" name="employee_id">
            <div id="employee_suggestions" class="list-group" style="position:absolute;z-index:1050;top:100%;left:0;right:0;display:none;
              max-height:240px;overflow:auto"></div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label>Date</label>
          <input type="date" name="date" id="log_date" class="form-control" value="<?php echo $currentDate; ?>" required>
        </div>
        <div class="form-group col-md-4">
          <label>Remarks</label>
          <input type="text" name="remarks" id="remarks" class="form-control" placeholder="Optional">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-3">
          <label>AM Time In</label>
          <input type="time" name="time_am_in" id="time_am_in" class="form-control" value="<?php echo $currentTime; ?>">
        </div>
        <div class="form-group col-md-3">
          <label>AM Time Out</label>
          <input type="time" name="time_am_out" id="time_am_out" class="form-control" value="">
        </div>
        <div class="form-group col-md-3">
          <label>PM Time In</label>
          <input type="time" name="time_pm_in" id="time_pm_in" class="form-control" value="">
        </div>
        <div class="form-group col-md-3">
          <label>PM Time Out</label>
          <input type="time" name="time_pm_out" id="time_pm_out" class="form-control" value="">
        </div>
        <div class="form-group col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-success" id="saveLogBtn">Save Entry</button>
          <button type="button" class="btn btn-secondary ml-2" id="clearLogBtn">Clear</button>
        </div>
      </div>
    </form>

    <hr>

    <h5>Recent Entries</h5>
    <div id="webLogList">Loading...</div>
  </div>
</div>

<script>
  $(function(){
  // Typeahead search for employees within department
  var dept = <?php echo json_encode(isset($_SESSION['Dept'])?$_SESSION['Dept']:''); ?>;
  var sessionEmp = <?php echo json_encode(isset($_SESSION['EmpID'])?$_SESSION['EmpID']:''); ?>;
  var typingTimer = null;
  var selectedIndex = -1;

  // small style for active suggestion
  $('<style>.emp-suggestion.active{background-color:#007bff;color:#fff;}</style>').appendTo('head');

  function renderSuggestions(rows){
    var container = $('#employee_suggestions');
    selectedIndex = -1;
    if(!rows || rows.length === 0){ container.html('<div class="list-group-item">No results</div>').show(); return; }
    var html = '';
    rows.forEach(function(r){
      var title = r.FullName + (r.Position ? ' — '+r.Position : '') + ' ('+r.EmpNo+')';
      html += '<a href="#" class="list-group-item list-group-item-action emp-suggestion" data-empno="'+r.EmpNo+'" data-fullname="'+$('<div>').text(r.FullName).html()+'">'+title+'</a>';
    });
    container.html(html).show();
  }

  function searchEmployees(q){
    $.getJSON('../api/employee_search.php', { q: q, dept: dept })
      .done(function(rows){ renderSuggestions(rows); })
      .fail(function(){ $('#employee_suggestions').html('<div class="list-group-item text-danger">Search failed</div>').show(); });
  }

  $('#employee_search').on('input', function(){
    clearTimeout(typingTimer);
    var q = $(this).val().trim();
    $('#employee_id').val('');
    if(q.length < 2){ $('#employee_suggestions').hide(); return; }
    typingTimer = setTimeout(function(){ searchEmployees(q); }, 250);
  });

  // keyboard navigation for suggestions
  $('#employee_search').on('keydown', function(e){
    var container = $('#employee_suggestions');
    var items = container.find('.emp-suggestion');
    if(!items.length) return;
    if(e.key === 'ArrowDown'){
      e.preventDefault();
      selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
      items.removeClass('active');
      $(items[selectedIndex]).addClass('active');
      // ensure visible
      $(items[selectedIndex])[0].scrollIntoView({ block: 'nearest' });
    } else if(e.key === 'ArrowUp'){
      e.preventDefault();
      selectedIndex = Math.max(selectedIndex - 1, 0);
      items.removeClass('active');
      $(items[selectedIndex]).addClass('active');
      $(items[selectedIndex])[0].scrollIntoView({ block: 'nearest' });
    } else if(e.key === 'Enter'){
      if(selectedIndex >= 0){ e.preventDefault(); $(items[selectedIndex]).trigger('click'); }
    } else if(e.key === 'Escape'){
      container.hide();
    }
  });

  $(document).on('click', '.emp-suggestion', function(e){
    e.preventDefault();
    var empno = $(this).data('empno');
    var fullname = $(this).text();
    $('#employee_id').val(empno);
    $('#employee_search').val(fullname);
    $('#employee_suggestions').hide();
    loadLogs();
  });

  // click outside to hide suggestions
  $(document).on('click', function(e){ if(!$(e.target).closest('#employee_suggestions, #employee_search').length) $('#employee_suggestions').hide(); });

  // if session Emp present, fetch and set
  if(sessionEmp){
    $.getJSON('../api/employee_search.php', { q: sessionEmp, dept: dept }).done(function(rows){ if(rows && rows[0]){ $('#employee_id').val(rows[0].EmpNo); $('#employee_search').val(rows[0].FullName + (rows[0].Position ? ' — '+rows[0].Position : '') + ' ('+rows[0].EmpNo+')'); } });
  }

  // Edit modal (create modal markup)
  var editModalHtml = '\n<div class="modal fade" id="editLogModal" tabindex="-1" role="dialog">\n  <div class="modal-dialog modal-lg" role="document">\n    <div class="modal-content">\n      <div class="modal-header">\n        <h5 class="modal-title">Edit Log Entry</h5>\n        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n          <span aria-hidden="true">&times;</span>\n        </button>\n      </div>\n      <div class="modal-body">\n        <form id="editLogForm">\n          <input type="hidden" id="edit_id">\n          <div class="form-row">\n            <div class="form-group col-md-6">\n              <label>Employee</label>\n              <input type="text" id="edit_employee_display" class="form-control" readonly>\n              <input type="hidden" id="edit_employee_id" name="employee_id">\n            </div>\n            <div class="form-group col-md-6">\n              <label>Date</label>\n              <input type="date" id="edit_date" name="date" class="form-control">\n            </div>\n          </div>\n          <div class="form-row">\n            <div class="form-group col-md-3"><label>AM In</label><input type="time" id="edit_am_in" name="time_am_in" class="form-control"></div>\n            <div class="form-group col-md-3"><label>AM Out</label><input type="time" id="edit_am_out" name="time_am_out" class="form-control"></div>\n            <div class="form-group col-md-3"><label>PM In</label><input type="time" id="edit_pm_in" name="time_pm_in" class="form-control"></div>\n            <div class="form-group col-md-3"><label>PM Out</label><input type="time" id="edit_pm_out" name="time_pm_out" class="form-control"></div>\n          </div>\n          <div class="form-group">\n            <label>Remarks</label>\n            <textarea id="edit_remarks" name="remarks" class="form-control" rows="3"></textarea>\n          </div>\n        </form>\n      </div>\n      <div class="modal-footer">\n        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\n        <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>\n      </div>\n    </div>\n  </div>\n</div>\n';
  $(editModalHtml).appendTo('body');

  // open modal and populate
  $(document).on('click', '.edit-log', function(){
    var btn = $(this);
    var id = btn.data('id');
    $('#edit_id').val(id);
    $('#edit_employee_id').val(btn.data('employee'));
    $('#edit_employee_display').val(btn.data('employee'));
    $('#edit_date').val(btn.data('date'));
    $('#edit_am_in').val(btn.data('am-in'));
    $('#edit_am_out').val(btn.data('am-out'));
    $('#edit_pm_in').val(btn.data('pm-in'));
    $('#edit_pm_out').val(btn.data('pm-out'));
    $('#edit_remarks').val(btn.data('remarks'));
    $('#editLogModal').modal('show');
  });

  // save edits
  $('#saveEditBtn').on('click', function(){
    var id = $('#edit_id').val();
    var payload = {
      id: id,
      employee_id: $('#edit_employee_id').val(),
      date: $('#edit_date').val(),
      time_am_in: $('#edit_am_in').val(),
      time_am_out: $('#edit_am_out').val(),
      time_pm_in: $('#edit_pm_in').val(),
      time_pm_out: $('#edit_pm_out').val(),
      remarks: $('#edit_remarks').val()
    };
    // basic validation
    if(!payload.employee_id || !payload.date){ Swal.fire('Missing fields','Employee and Date are required','warning'); return; }
    if(!((payload.time_am_in && payload.time_am_out) || (payload.time_pm_in && payload.time_pm_out))){ Swal.fire('Missing times','Provide at least AM or PM pair','warning'); return; }
    if(payload.time_am_in && payload.time_am_out && payload.time_am_out <= payload.time_am_in){ Swal.fire('Invalid AM','AM Out must be later than AM In','warning'); return; }
    if(payload.time_pm_in && payload.time_pm_out && payload.time_pm_out <= payload.time_pm_in){ Swal.fire('Invalid PM','PM Out must be later than PM In','warning'); return; }
    if(payload.time_am_in && payload.time_am_out && payload.time_pm_in && payload.time_pm_out && payload.time_am_out > payload.time_pm_in){ Swal.fire('Invalid times','AM Out must be earlier than or equal to PM In','warning'); return; }

    $('#saveEditBtn').prop('disabled',true).text('Saving...');
    $.post('../api/web_logging_update.php', payload)
      .done(function(res){ try{ res = typeof res === 'object' ? res : JSON.parse(res); }catch(e){}
        if(res.success){ Swal.fire('Updated','Entry updated','success'); $('#editLogModal').modal('hide'); loadLogs(); }
        else { Swal.fire('Error', res.message || 'Failed to update','error'); }
      })
      .fail(function(){ Swal.fire('Error','Server error','error'); })
      .always(function(){ $('#saveEditBtn').prop('disabled',false).text('Save changes'); });
  });
  function loadLogs() {
    $.getJSON('../api/web_logging_list.php', { employee_id: $('#employee_id').val(), date: $('#log_date').val() })
      .done(function(data){
        if(!data || !data.rows) { $('#webLogList').html('<p class="text-muted">No entries found.</p>'); return; }
        var rows = data.rows;
        var html = '<table class="table table-sm"><thead><tr><th>#</th><th>Employee</th><th>Date</th><th>AM In</th><th>AM Out</th><th>PM In</th><th>PM Out</th><th>Remarks</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
        function fmt(t){ if(!t) return ''; var parts = t.split(':'); if(parts.length<2) return t; var h = parseInt(parts[0],10); var m = parts[1]; var suffix = h >= 12 ? 'PM' : 'AM'; var hh = h % 12; if(hh === 0) hh = 12; return hh + ':' + m + ' ' + suffix; }
        rows.forEach(function(r,i){
          var amInRaw = r.time_am_in || '';
          var amOutRaw = r.time_am_out || '';
          var pmInRaw = r.time_pm_in || '';
          var pmOutRaw = r.time_pm_out || '';
          var amIn = amInRaw ? fmt(amInRaw) : '<span class="badge badge-secondary">—</span>';
          var amOut = amOutRaw ? fmt(amOutRaw) : '<span class="badge badge-warning">Missing</span>';
          var pmIn = pmInRaw ? fmt(pmInRaw) : '<span class="badge badge-warning">Missing</span>';
          var pmOut = pmOutRaw ? fmt(pmOutRaw) : '<span class="badge badge-secondary">—</span>';
          html += '<tr>'+
            '<td>'+(i+1)+'</td>'+
            '<td>'+r.employee_id+'</td>'+
            '<td>'+r.date+'</td>'+
            '<td>'+amIn+'</td>'+
            '<td>'+amOut+'</td>'+
            '<td>'+pmIn+'</td>'+
            '<td>'+pmOut+'</td>'+
            '<td>'+ (r.remarks || '') +'</td>'+
            '<td>'+r.status+'</td>'+
            '<td>';
          if((<?php echo json_encode($access); ?>).match(/Supervisor|HR|Department/i) && r.status === 'Pending'){
            html += '<button class="btn btn-sm btn-primary approve-log" data-id="'+r.id+'">Approve</button> ';
          }
          var canEdit = (<?php echo json_encode($access); ?>.match(/Supervisor|HR|Department|Admin/i) || (r.created_by && r.created_by === <?php echo json_encode(isset($_SESSION['username'])?$_SESSION['username']:''); ?>));
          html += '</td>';
          html += '<td>';
          if(canEdit){
            html += '<button class="btn btn-sm btn-outline-secondary edit-log" data-id="'+r.id+'" data-employee="'+r.employee_id+'" data-date="'+r.date+'" data-am-in="'+(r.time_am_in||'')+'" data-am-out="'+(r.time_am_out||'')+'" data-pm-in="'+(r.time_pm_in||'')+'" data-pm-out="'+(r.time_pm_out||'')+'" data-remarks="'+(r.remarks? $('<div>').text(r.remarks).html() : '')+'">Edit</button> ';
          }
          if((<?php echo json_encode($access); ?>).match(/Supervisor|HR|Department/i) && r.status === 'Pending'){
            html += '<button class="btn btn-sm btn-primary approve-log" data-id="'+r.id+'">Approve</button> ';
          }
          html += '</td></tr>';
        });
        html += '</tbody></table>';
        // add legend
        html += '<div class="mt-2"><span class="badge badge-warning">Missing</span> Missing time in/out; <span class="badge badge-secondary">—</span> Not applicable</div>';
        $('#webLogList').html(html);
      })
      .fail(function(){ $('#webLogList').html('<p class="text-danger">Failed to load entries.</p>'); });
  }

  $('#clearLogBtn').click(function(){ $('#webLogForm')[0].reset(); $('#log_date').val('<?php echo $currentDate; ?>'); $('#time_in').val('<?php echo $currentTime; ?>'); });

  // no bulk load; typeahead will fetch as user types

    $('#webLogForm').submit(function(e){
    e.preventDefault();
    var emp = $('#employee_id').val().trim();
    var date = $('#log_date').val();
    var am_in = $('#time_am_in').val();
    var am_out = $('#time_am_out').val();
    var pm_in = $('#time_pm_in').val();
    var pm_out = $('#time_pm_out').val();

    if(!emp || !date){ Swal.fire('Missing fields','Please select employee and date','warning'); return; }
    // At least one pair must be provided
    var amPair = am_in && am_out;
    var pmPair = pm_in && pm_out;
    if(!amPair && !pmPair){ Swal.fire('Missing times','Please provide at least AM or PM Time In/Out pair','warning'); return; }
    if(amPair && am_out <= am_in){ Swal.fire('Invalid AM times','AM Time Out must be later than AM Time In','warning'); return; }
    if(pmPair && pm_out <= pm_in){ Swal.fire('Invalid PM times','PM Time Out must be later than PM Time In','warning'); return; }
    if(amPair && pmPair && am_out > pm_in){ Swal.fire('Invalid times','AM Time Out must be earlier than or equal to PM Time In','warning'); return; }

    $('#saveLogBtn').prop('disabled',true).text('Saving...');
    $.post('../api/web_logging_save.php', { employee_id: emp, date: date, time_am_in: am_in, time_am_out: am_out, time_pm_in: pm_in, time_pm_out: pm_out, remarks: $('#remarks').val() })
      .done(function(res){
        try{ var j = typeof res === 'object' ? res : JSON.parse(res); }
        catch(e){ Swal.fire('Error','Unexpected response from server','error'); $('#saveLogBtn').prop('disabled',false).text('Save Entry'); return; }
        if(j.success){ Swal.fire('Saved','Entry saved successfully','success'); $('#webLogForm')[0].reset(); $('#log_date').val('<?php echo $currentDate; ?>'); $('#time_am_in').val('<?php echo $currentTime; ?>'); loadLogs(); }
        else { Swal.fire('Error', j.message || 'Failed to save entry','error'); }
      })
      .fail(function(){ Swal.fire('Error','Server error','error'); })
      .always(function(){ $('#saveLogBtn').prop('disabled',false).text('Save Entry'); });
  });

  $(document).on('click', '.approve-log', function(){
    var id = $(this).data('id');
    Swal.fire({ title:'Approve entry?', showCancelButton:true }).then((r)=>{ if(r.isConfirmed){ $.post('../api/web_logging_approve.php',{id:id}).done(function(d){ try{ d = typeof d === 'object' ? d : JSON.parse(d); }catch(e){} if(d.success){ Swal.fire('Approved','Entry approved','success'); loadLogs(); } else Swal.fire('Error', d.message || 'Failed','error'); }); } });
  });

  // reload logs when date or employee changes
  $('#log_date, #employee_id').change(loadLogs);

  loadLogs();
});
</script>
