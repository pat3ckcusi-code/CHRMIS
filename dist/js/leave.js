$(document).ready(function () {

  loadBalances();
  loadLeaves();

  function loadBalances() {
    $.getJSON('/CHRMIS/api/leave/balance.php', function (data) {
      $('#vacation').text(data.vacation + ' Days');
      $('#sick').text(data.sick + ' Days');
      $('#cto').text(data.cto + ' Days');
      $('#special').text(data.special + ' Days');

      $('#vlBalance').text(data.vacation + ' Days');
      $('#asOfDate').text(new Date().toLocaleDateString());
    });
  }

  function loadLeaves() {
    $.getJSON('/CHRMIS/api/leave/index.php', function (rows) {
      let html = '';

      if (!rows || rows.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted">No leaves filed yet.</td></tr>';
      } else {
        rows.forEach(row => {
          const statusBadge = `<span class="badge badge-${row.status_class || 'secondary'}">${row.Status || row.Remarks || 'Pending'}</span>`;
          
          const formatDate = (dateStr) => {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
          };
          
          let actionHtml = '';
          if (row.can_print) {
            actionHtml = `<a href="/CHRMIS/pdf_viewer_leave.php?id=${row.LeaveID}" target="_blank" class="btn btn-sm btn-primary mr-1"><i class="fas fa-print"></i></a>`;
          }
          if (row.can_cancel) {
            actionHtml += (row.can_print ? ' ' : '') + `<button class="btn btn-sm btn-danger cancel-btn" data-id="${row.LeaveID}"><i class="fas fa-times"></i></button>`;
          }

          html += `<tr><td>${formatDate(row.DateFiled)}</td><td>${row.LeaveType || ''}</td><td>${formatDate(row.DateFrom)}</td><td>${formatDate(row.DateTo)}</td><td>${statusBadge}</td><td class="text-center">${actionHtml}</td></tr>`;
        });
      }

      $('#filedLeavesTable tbody').html(html);

      // If DataTables is available, re-draw it for better UX (safe: destroy previous)
      if ($.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#filedLeavesTable')) {
          $('#filedLeavesTable').DataTable().destroy();
        }
        $('#filedLeavesTable').DataTable({
          pageLength: 10,
          ordering: true,
          columnDefs: [ { orderable: false, targets: 5 } ]
        });
      }
    });
  }

  $(document).on('click', '#filedLeavesTable .cancel-btn', function () {
    const leaveId = $(this).data('id');

    Swal.fire({
      title: 'Are you sure?',
      text: "This will cancel your leave application.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post('/CHRMIS/api/leave/cancel.php', {
          leave_id: leaveId
        }, function(response) {
          Swal.fire('Cancelled!', 'Your leave application has been cancelled.', 'success').then(() => loadLeaves());
        }).fail(function() {
          Swal.fire('Error!', 'Failed to cancel leave application.', 'error');
        });
      }
    });
  });

});
