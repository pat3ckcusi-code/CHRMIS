<?php
session_start();
require_once('../includes/initialize.php');

if (($_SESSION['access_level'] ?? '') !== 'Mayor') {
  echo "<p class='text-muted'>Not authorized to view travel orders.</p>";
  return;
}
?>
<div class="card">
  <div class="card-header"><h3 class="card-title"><i class="fas fa-suitcase-rolling"></i> Travel Orders - Pending Approval</h3></div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="mayorTravelOrdersTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Created At</th>
            <th>Employees</th>
            <th>Destination</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Purpose</th>
            <th>Remarks</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
;(function(){
  function formatDate(d){ if(!d) return ''; const dt = new Date(d); if(isNaN(dt)) return d; return dt.toLocaleDateString('en-US',{ weekday:'short', month:'short', day:'numeric', year:'numeric'}); }

  function loadTO(){
    $('#mayorTravelOrdersTable tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
    $.getJSON('../api/get_travel_orders_mayor.php')
      .done(function(rows){
        if(!Array.isArray(rows) || rows.length===0){ $('#mayorTravelOrdersTable tbody').html('<tr><td colspan="9" class="text-center text-muted">No travel orders pending approval.</td></tr>'); $('#pendingTOCount').text(0); return; }
        $('#pendingTOCount').text(rows.length);
        const html = rows.map((r,i)=>{
          const employees = (r.employees_names && r.employees_names.length) ? r.employees_names.join(', ') : (r.employees_count||0);
          const actions = `
            <button class="btn btn-sm btn-success mayor-approve-order" data-id="${r.id}">Approve</button>
            <button class="btn btn-sm btn-danger mayor-reject-order" data-id="${r.id}">Reject</button>
          `;
          return `<tr>
            <td>${i+1}</td>
            <td>${formatDate(r.created_at)}</td>
            <td>${$('<div>').text(employees).html()}</td>
            <td>${$('<div>').text(r.destination||'').html()}</td>
            <td>${formatDate(r.start_date)}</td>
            <td>${formatDate(r.end_date)}</td>
            <td>${$('<div>').text(r.purpose||'').html()}</td>
            <td>${$('<div>').text(r.remarks||'').html()}</td>
            <td>${actions}</td>
          </tr>`;
        }).join('');
        $('#mayorTravelOrdersTable tbody').html(html);
      })
      .fail(function(){ $('#mayorTravelOrdersTable tbody').html('<tr><td colspan="8" class="text-danger text-center">Failed to load travel orders.</td></tr>'); });
  }

  // wire approve/reject to existing mayor API
  $(document).on('click', '.mayor-approve-order', function(){
    const id = $(this).data('id');
    Swal.fire({ title:'Approve Travel Order', text:'Approve this travel order?', icon:'question', showCancelButton:true, confirmButtonText:'Yes, approve' })
      .then(res=>{ if(!res.isConfirmed) return; Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()}); $.post('../api/mayor_update_travel_order_status.php',{ id:id, status:'Approved' }).done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; }catch(e){} if(resp && resp.success){ Swal.fire('Approved','Travel order approved.','success'); loadTO(); } else { Swal.fire('Error', (resp && resp.message)?resp.message:'Failed to approve.','error'); } }).fail(function(){ Swal.close(); Swal.fire('Error','Server error','error'); }); });
  });

  $(document).on('click', '.mayor-reject-order', function(){
    const id = $(this).data('id');
    Swal.fire({ title:'Reject Travel Order', input:'text', inputLabel:'Reason for rejection', inputPlaceholder:'Enter reason...', icon:'warning', showCancelButton:true, preConfirm: (note)=>{ if(!note) Swal.showValidationMessage('Please enter a reason'); return note; } }).then(result=>{ if(!result.isConfirmed) return; Swal.fire({title:'Processing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading()}); $.post('../api/mayor_update_travel_order_status.php',{ id:id, status:'Rejected', rejection_note: result.value }).done(function(resp){ Swal.close(); try{ resp = typeof resp === 'string' ? JSON.parse(resp) : resp; }catch(e){} if(resp && resp.success){ Swal.fire('Rejected','Travel order rejected.','success'); loadTO(); } else { Swal.fire('Error',(resp && resp.message)?resp.message:'Failed to reject.','error'); } }).fail(function(){ Swal.close(); Swal.fire('Error','Server error','error'); }); });
  });

  // initial load
  $(document).ready(function(){ loadTO(); });
})();
</script>
