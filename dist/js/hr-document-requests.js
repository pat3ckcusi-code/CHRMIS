$(document).ready(function() {

  // Initialize DataTable
  const table = $('#hrRequestsTable').DataTable({
    pageLength: 10,
    lengthChange: false,
    order: [[3, 'desc']], // Requested On column
    columnDefs: [{ orderable: false, targets: 6 }],
    language: { emptyTable: "No actionable document requests found" }
  });


  // Load all requests
  function loadRequests() {
    $.get('../api/document_requests_api.php', { action: 'list' }, function(resp) {
      if(resp.status === 'success') {
        table.clear();

        resp.data.forEach(r => {

          // Skip Rejected or Completed requests
          if(r.status === 'Rejected' || r.status === 'Completed') return;

          const requestedOn = new Date(r.requested_on).toLocaleString('en-US', { hour12: true });
          const fullName = r.Fname
          + (r.Mname ? ' ' + r.Mname : '')
          + ' ' + r.Lname
          + (r.Extension ? ' ' + r.Extension : '');
          let actions = '';

          if(r.status === 'Requested') {
            actions = `
              <button class="btn btn-success btn-sm approve-btn" data-id="${r.id}" data-toggle="tooltip" title="Approve request"><i class="fas fa-check"></i></button>
              <button class="btn btn-danger btn-sm reject-btn" data-id="${r.id}" data-toggle="tooltip" title="Reject request"><i class="fas fa-times"></i></button>
            `;
          } else if(r.status === 'Pending') {
            actions = `
              <button class="btn btn-primary btn-sm release-btn" data-id="${r.id}" data-toggle="tooltip" title="Mark as completed"><i class="fas fa-box-open"></i></button>
            `;
          }

          const rowNode = table.row.add([
            r.EmpNo,
            fullName,
            r.Dept,
            r.document_type,
            r.purpose || '—',
            requestedOn,
            r.status,
            r.remarks || '—',
            actions
          ]).draw(false).node();

          $(rowNode).attr('data-status', r.status);
          $(rowNode).attr('data-id', r.id);
        });
        $('[data-toggle="tooltip"]').tooltip();
      } else {
        Swal.fire('Error', resp.message, 'error');
      }
    }, 'json');
  }

  loadRequests();


  // Approve button
    $(document).on('click', '.approve-btn', function() {
    const id = $(this).data('id');
    const rowNode = $(this).closest('tr');

    Swal.fire({
      title: 'Approve this request?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, approve',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if(result.isConfirmed){
        $.post('../api/document_requests_api.php', { 
          action: 'update_status', 
          id, 
          status: 'Pending' 
        }, function(resp) {
          if(resp.status === 'success'){
            updateRowButtons(rowNode, 'Pending');
            Swal.fire('Success', resp.message, 'success');
          } else {
            // Email failed or other error
            Swal.fire('Error', resp.message, 'error');
          }
        }, 'json').fail(function() {
          Swal.fire('Error', 'Unexpected error occurred.', 'error');
        });
      }
    });
  });

  // Reject button with HR notes
    $(document).on('click', '.reject-btn', function() {
    const id = $(this).data('id');
    const rowNode = $(this).closest('tr');

    Swal.fire({
      title: 'Reject this request?',
      input: 'text',
      inputLabel: 'Reason for rejection',
      inputPlaceholder: 'Enter note for employee...',
      showCancelButton: true,
      confirmButtonText: 'Reject',
      cancelButtonText: 'Cancel',
      preConfirm: (note) => {
        if(!note) Swal.showValidationMessage('Please enter a reason for rejection');
        return note;
      }
    }).then(result => {
      if(result.isConfirmed){
        const note = result.value;

        $.post('../api/document_requests_api.php', {
          action: 'update_status',
          id: id,
          status: 'Rejected',
          note: note  
        }, function(resp) {
          if(resp.status === 'success'){
            table.row(rowNode).remove().draw(false);
            Swal.fire('Rejected!', resp.message, 'success');
          } else {
            Swal.fire('Error', resp.message, 'error');
          }
        }, 'json').fail(function() {
          Swal.fire('Error', 'Unexpected error occurred.', 'error');
        });
      }
    });
  });

  // Release / Complete button
    $(document).on('click', '.release-btn', function() {
    const id = $(this).data('id');
    const rowNode = $(this).closest('tr');

    Swal.fire({
      title: 'Mark as Completed?',
      text: "This will notify the employee that the document is ready for pick-up.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, complete',
      cancelButtonText: 'Cancel'
    }).then(result => {
      if(result.isConfirmed){
        $.post('../api/document_requests_api.php', { 
          action: 'update_status', 
          id, 
          status: 'Completed' 
        }, function(resp) {
          if(resp.status === 'success'){
            table.row(rowNode).remove().draw(false);
            Swal.fire('Completed!', resp.message, 'success');
          } else {
            // Email failed or other error
            Swal.fire('Error', resp.message, 'error');
          }
        }, 'json').fail(function() {
          Swal.fire('Error', 'Unexpected error occurred.', 'error');
        });
      }
    });
  });

  // Update row buttons for Pending
  function updateRowButtons(rowNode, status) {
    const $row = $(rowNode);
    $row.attr('data-status', status);

    if(status === 'Pending') {
      const actions = `<button class="btn btn-primary btn-sm release-btn" data-id="${$row.data('id')}" data-toggle="tooltip" title="Mark as completed"><i class="fas fa-box-open"></i></button>`;
      $row.find('td:last').html(actions);
      $('[data-toggle="tooltip"]').tooltip();
    }
  }
});
