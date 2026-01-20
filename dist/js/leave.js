$(document).ready(function () {

  loadBalances();
  loadLeaves();

  function loadBalances() {
    $.getJSON('../api/leave/balance.php', function (data) {
      console.log('Leave balance API response:', data);
      // Defensive: fallback to 0 if missing or not a number
      var vacation = (typeof data.vacation === 'number' && !isNaN(data.vacation)) ? data.vacation : 0;
      var sick     = (typeof data.sick === 'number' && !isNaN(data.sick)) ? data.sick : 0;
      var cto      = (typeof data.cto === 'number' && !isNaN(data.cto)) ? data.cto : 0;
      var special  = (typeof data.special === 'number' && !isNaN(data.special)) ? data.special : 0;

      $('#vacation').text(vacation + ' Days');
      $('#sick').text(sick + ' Days');
      $('#cto').text(cto + ' Days');
      $('#special').text(special + ' Days');

      $('#vlBalance').text(vacation + ' Days');
      $('#asOfDate').text(new Date().toLocaleDateString());
    }).fail(function(jqXHR, textStatus, errorThrown) {
      // If API fails, show 0 Days and log error
      console.error('Failed to fetch leave balances:', textStatus, errorThrown);
      $('#vacation').text('0 Days');
      $('#sick').text('0 Days');
      $('#cto').text('0 Days');
      $('#special').text('0 Days');
      $('#vlBalance').text('0 Days');
      $('#asOfDate').text(new Date().toLocaleDateString());
    });
  }

  function loadLeaves() {
    $.getJSON('../api/leave/index.php', function (rows) {
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
            actionHtml = `<a href="../pdf_viewer_leave.php?id=${row.LeaveID}" target="_blank" class="btn btn-sm btn-primary mr-1"><i class="fas fa-print"></i></a>`;
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

  // Allow other parts of the app to request a refresh after actions (file, cancel, approve)
  // expose a global function so other scripts can call it directly
  window.leaveRefresh = function () {
    try { loadBalances(); } catch (e) { console.warn('leaveRefresh: loadBalances failed', e); }
    try { loadLeaves(); } catch (e) { console.warn('leaveRefresh: loadLeaves failed', e); }
  };

  $(document).on('leave:refresh', function () {
    window.leaveRefresh();
  });

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
        $.post('../api/leave/cancel.php', {
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
