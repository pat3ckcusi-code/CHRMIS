<?php
session_start();
// Partial: Travel Orders list for the department
$dept = $_SESSION['Dept'] ?? '';
?>
<div class="card">
  <div class="card-header"><h3 class="card-title"><i class="fas fa-list-alt"></i> Filed Travel Orders</h3></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="travelOrdersTable">
        <thead>
          <tr>
            <th>#</th>
            <th>TO Number</th>
            <th>Destination</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Employees</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="9" class="text-center text-muted">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
;(function(){
  const isDeptHead = <?php echo json_encode((($_SESSION['access_level'] ?? '') === 'Department Head')); ?>;
  const isMayor = <?php echo json_encode((($_SESSION['access_level'] ?? '') === 'Mayor')); ?>;
  function formatDateDisplay(d) {
    if (!d) return '';
    const dt = new Date(d);
    if (isNaN(dt)) return d;
    return dt.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
  }

  function loadOrders(){
    $('#travelOrdersTable tbody').html('<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
    $.getJSON('../api/get_travel_orders.php')
      .done(function(rows){
        if(!Array.isArray(rows) || rows.length===0){
          $('#travelOrdersTable tbody').html('<tr><td colspan="9" class="text-center text-muted">No travel orders found.</td></tr>');
          return;
        }
        const html = rows.map((r,i) => {
          const employees = (r.employees_names && r.employees_names.length) ? r.employees_names.join(', ') : (r.employees_count || 0);
          // Actions depend on status and role
          let actions = '<span class="text-muted">-</span>';
          if (r.status === 'Pending Recommendation') {
            if (isDeptHead) {
              actions = `
                <button class="btn btn-sm btn-success mr-1 approve-order" data-id="${r.id}">Recommend</button>
                <button class="btn btn-sm btn-danger reject-order" data-id="${r.id}">Reject</button>
              `;
            } else {
              actions = `
                <button class="btn btn-sm btn-primary mr-1 update-order" data-id="${r.id}">Update</button>
                <button class="btn btn-sm btn-danger cancel-order" data-id="${r.id}">Cancel</button>
              `;
            }
          } else if (r.status === 'Pending Approval') {
            if (isMayor) {
              actions = `
                <button class="btn btn-sm btn-success mr-1 mayor-approve-order" data-id="${r.id}">Approve</button>
                <button class="btn btn-sm btn-danger mayor-reject-order" data-id="${r.id}">Reject</button>
              `;
            } else {
              actions = '<span class="text-muted">Awaiting Mayor</span>';
            }
          } else if (r.status === 'Approved') {
            // show print button for approved travel orders
            actions = `<button class="btn btn-sm btn-primary print-order" data-id="${r.id}">Print</button>`;
          }

          return `<tr>
            <td>${i+1}</td>
            <td>${r.travel_order_num || ''}</td>
            <td>${$('<div>').text(r.destination||'').html()}</td>
            <td>${formatDateDisplay(r.start_date)}</td>
            <td>${formatDateDisplay(r.end_date)}</td>
            <td>${$('<div>').text(employees).html()}</td>
            <td>${$('<div>').text(r.status||'').html()}</td>
            <td>${formatDateDisplay(r.created_at)}</td>
            <td>${actions}</td>
          </tr>`;
        }).join('');
        $('#travelOrdersTable tbody').html(html);
      })
      .fail(function(){
        $('#travelOrdersTable tbody').html('<tr><td colspan="9" class="text-danger text-center">Failed to load travel orders.</td></tr>');
      });
  }

  // initial load
  loadOrders();

  // Cancel handler
  $(document).on('click', '.cancel-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Cancel Travel Order',
      text: 'Are you sure you want to cancel this travel order? This will move it back to Draft.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, cancel it',
      cancelButtonText: 'No'
    }).then((res) => {
      if (!res.isConfirmed) return;
      Swal.fire({title: 'Processing...', allowOutsideClick:false, didOpen: () => Swal.showLoading()});
      $.post('../api/update_travel_order_status.php', { id: id, status: 'Draft' })
        .done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){} if(resp && resp.success){ Swal.fire('Canceled','Travel order canceled.','success'); loadOrders(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to cancel.','error'); } })
        .fail(function(){ Swal.close(); Swal.fire('Error','Server error while canceling.','error'); });
    });
  });

  // Update handler - open edit form in SweetAlert
  $(document).on('click', '.update-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Loading travel order...',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading(); }
    });
    $.getJSON('../api/get_travel_order.php', { id: id })
      .done(function(data){ Swal.close(); if(!data || !data.id){ Swal.fire('Error','Travel order not found.','error'); return; }
        const tpl = `
          <div style="text-align:left">
            <div class="form-group">
              <label>Destination</label>
              <input id="sw_dest" class="sw-input form-control" value="${$('<div>').text(data.destination||'').html()}">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6"><label>Departure</label><input id="sw_dep" type="date" class="form-control" value="${data.start_date||''}"></div>
              <div class="form-group col-md-6"><label>Return</label><input id="sw_ret" type="date" class="form-control" value="${data.end_date||''}"></div>
            </div>
            <div class="form-group"><label>Purpose</label><textarea id="sw_purp" class="form-control">${$('<div>').text(data.purpose||'').html()}</textarea></div>
            <div class="form-group"><label>Per Diem / Expenses</label><input id="sw_per" class="form-control" value="${$('<div>').text(data.per_diem||'').html()}"></div>
            <div class="form-group"><label>Appropriation</label><input id="sw_app" class="form-control" value="${$('<div>').text(data.appropriation||'').html()}"></div>
            <div class="form-group"><label>Employees</label>
              <select id="sw_employees" class="form-control" multiple style="width:100%"></select>
              <small class="form-text text-muted">Select one or more employees for this travel order.</small>
            </div>
          </div>`;

        Swal.fire({
          title: 'Edit Travel Order',
          html: tpl,
          width: 760,
          showCancelButton: true,
          confirmButtonText: 'Save changes',
          didOpen: () => {
            // load Select2 assets (CDN) if not already present
            if (typeof $.fn.select2 === 'undefined') {
              $('head').append('<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />');
              $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', function(){ initEmployeeSelect(); });
            } else { initEmployeeSelect(); }

            function initEmployeeSelect(){
              const dept = "<?php echo addslashes($dept); ?>";
              const $sel = $('#sw_employees');
              // load department employees
              $.getJSON('../api/get_department_employees.php', { dept: dept })
                .done(function(list){
                  $sel.empty();
                  list.forEach(function(emp){
                    const selected = (data.employees || []).indexOf(String(emp.EmpNo)) !== -1 ? 'selected' : '';
                    $sel.append('<option value="'+emp.EmpNo+'" '+selected+'>'+ (emp.FullName + (emp.Position ? (' — ' + emp.Position) : '')) +'</option>');
                  });
                  $sel.select2({ width: '100%' });
                })
                .fail(function(){ $sel.append('<option disabled>No employees</option>'); });
            }
          },
          preConfirm: () => {
            // Validate employees selection - must not be empty
            const selected = $('#sw_employees').val() || [];
            if (!Array.isArray(selected) || selected.length === 0) {
              Swal.showValidationMessage('Please select at least one employee.');
              return false;
            }
            return {
              id: data.id,
              destination: $('#sw_dest').val(),
              start_date: $('#sw_dep').val(),
              end_date: $('#sw_ret').val(),
              purpose: $('#sw_purp').val(),
              per_diem: $('#sw_per').val(),
              appropriation: $('#sw_app').val(),
              employees: selected
            };
          }
        }).then((res) => {
          if (!res.isConfirmed) return;
          const payload = res.value;
          // Ask final confirmation before saving
          Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to save the changes to this travel order?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save',
            cancelButtonText: 'Cancel'
          }).then((confirmRes) => {
            if (!confirmRes.isConfirmed) return;
            Swal.fire({title:'Saving...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()});
            $.ajax({ url: '../api/update_travel_order.php', method: 'POST', data: JSON.stringify(payload), contentType:'application/json', dataType:'json' })
              .done(function(r){ Swal.close(); if(r && r.success){ Swal.fire('Saved','Travel order updated.','success'); loadOrders(); } else { Swal.fire('Error',(r && r.message)?r.message:'Failed to save.','error'); } })
              .fail(function(){ Swal.close(); Swal.fire('Error','Server error while saving.','error'); });
          });
        });

      })
      .fail(function(){ Swal.close(); Swal.fire('Error','Failed to load travel order.','error'); });
  });

  // Approve handler (Department Head -> recommend / move to Pending Approval)
  $(document).on('click', '.approve-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Recommend Travel Order',
      text: 'Are you sure you want to recommend (send for approval) this travel order?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, recommend',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;
      Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()});
      $.post('../api/update_travel_order_status.php', { id: id, status: 'Pending Approval' })
        .done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){} if(resp && resp.success){ Swal.fire('Recommended','Travel order recommended for approval.','success'); loadOrders(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to update.','error'); } })
        .fail(function(){ Swal.close(); Swal.fire('Error','Server error while updating status.','error'); });
    });
  });

  // Reject handler (Department Head rejects -> move back to Draft)
  $(document).on('click', '.reject-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Reject Travel Order',
      text: 'Are you sure you want to reject this travel order? This will move it back to Draft.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, reject',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;
      Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()});
      $.post('../api/update_travel_order_status.php', { id: id, status: 'Draft' })
        .done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){} if(resp && resp.success){ Swal.fire('Rejected','Travel order rejected.','success'); loadOrders(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to update.','error'); } })
        .fail(function(){ Swal.close(); Swal.fire('Error','Server error while updating status.','error'); });
    });
  });

  // Mayor approval (final approve)
  $(document).on('click', '.mayor-approve-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Approve Travel Order',
      text: 'This will approve the travel order. Continue?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, approve',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;
      Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()});
      $.post('../api/mayor_update_travel_order_status.php', { id: id, status: 'Approved' })
        .done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){} if(resp && resp.success){ Swal.fire('Approved','Travel order approved by Mayor.','success'); loadOrders(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to update.','error'); } })
        .fail(function(){ Swal.close(); Swal.fire('Error','Server error while updating status.','error'); });
    });
  });

  // Mayor rejection (require reason)
  $(document).on('click', '.mayor-reject-order', function(){
    const id = $(this).data('id');
    Swal.fire({
      title: 'Reject Travel Order',
      input: 'text',
      inputLabel: 'Reason for rejection',
      inputPlaceholder: 'Enter rejection reason...',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, reject',
      preConfirm: (note) => {
        if (!note) Swal.showValidationMessage('Please enter a reason');
        return note;
      }
    }).then((result) => {
      if (!result.isConfirmed) return;
      Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()});
      $.post('../api/mayor_update_travel_order_status.php', { id: id, status: 'Rejected', rejection_note: result.value })
        .done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e){} if(resp && resp.success){ Swal.fire('Rejected','Travel order has been rejected.','success'); loadOrders(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to reject.','error'); } })
        .fail(function(){ Swal.close(); Swal.fire('Error','Server error while rejecting.','error'); });
    });
  });

  // Print handler for approved travel orders
  $(document).on('click', '.print-order', function(){
    const id = $(this).data('id');
    const url = '../pdf_viewer_travel_order.php?id=' + encodeURIComponent(id);
    window.open(url, '_blank');
  });

})();
</script>
