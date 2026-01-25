$(document).ready(function () {

  console.log('leave.js loaded');

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
      console.log('Leave list API response:', rows);
      let html = '';

      // Normalize rows: accept Array, {data: Array}, or keyed object
      let items = [];
      if (Array.isArray(rows)) items = rows;
      else if (rows && Array.isArray(rows.data)) items = rows.data;
      else if (rows && typeof rows === 'object') items = Object.values(rows);

      if (!items || items.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted">No leaves filed yet.</td></tr>';
      } else {
        items.forEach(row => {
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

      // Prepare dataRows for DataTables API (ensures rows show even if DOM parsing fails)
      var dataRows = [];
      var monthSet = new Set();
      if (items && items.length) {
        items.forEach(row => {
          const statusBadge = `<span class="badge badge-${row.status_class || 'secondary'}">${row.Status || row.Remarks || 'Pending'}</span>`;
          const formatDate = (dateStr) => {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
          };
          let actionHtml = '';
          if (row.can_print) actionHtml = `<a href="../pdf_viewer_leave.php?id=${row.LeaveID}" target="_blank" class="btn btn-sm btn-primary mr-1"><i class="fas fa-print"></i></a>`;
          if (row.can_cancel) actionHtml += (row.can_print ? ' ' : '') + `<button class="btn btn-sm btn-danger cancel-btn" data-id="${row.LeaveID}"><i class="fas fa-times"></i></button>`;

          // monthKey derived from DateFrom (YYYY-MM)
          var monthKey = '';
          try {
            if (row.DateFrom) {
              var d = new Date(row.DateFrom);
              if (!isNaN(d)) {
                monthKey = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                monthSet.add(monthKey);
              }
            }
          } catch (e) { /* ignore */ }

          dataRows.push([formatDate(row.DateFiled), row.LeaveType || '', formatDate(row.DateFrom), formatDate(row.DateTo), statusBadge, actionHtml, monthKey]);
        });
      }

      // populate month filter select with available months
      try {
        var $mf = $('#monthFilter');
        if ($mf.length) {
          var existing = $mf.find('option').length > 1;
          if (!existing) {
            var months = Array.from(monthSet).sort().reverse();
            months.forEach(mk => {
              var parts = mk.split('-');
              var label = new Date(parts[0], parseInt(parts[1],10)-1).toLocaleString('en-US', { month: 'long', year: 'numeric' });
              $mf.append(`<option value="${mk}">${label}</option>`);
            });
          } else {
            // refresh options
            $mf.find('option:gt(0)').remove();
            var months = Array.from(monthSet).sort().reverse();
            months.forEach(mk => {
              var parts = mk.split('-');
              var label = new Date(parts[0], parseInt(parts[1],10)-1).toLocaleString('en-US', { month: 'long', year: 'numeric' });
              $mf.append(`<option value="${mk}">${label}</option>`);
            });
          }
        }
      } catch (e) { console.warn('monthFilter population failed', e); }

      console.log('DOM rows after html set:', $('#filedLeavesTable tbody tr').length);

      // If DataTables is available, re-draw it for better UX (safe: destroy previous)
      // Try to initialize DataTable; if DataTables assets haven't loaded yet, retry a few times
      (function initWithRetry() {
        var attempts = 0;
        var maxAttempts = 20;
        var delayMs = 250;

        function init() {
          if (window.jQuery && $.fn && ($.fn.DataTable || $.fn.dataTable)) {
              try {
                var useCapital = !!$.fn.DataTable;

                // Destroy previous instance using the available API
                if (useCapital) {
                  if ($.fn.DataTable.isDataTable('#filedLeavesTable')) {
                    $('#filedLeavesTable').DataTable().clear().destroy();
                  }
                } else {
                  // legacy/dataTable API
                  if ($.fn.dataTable.isDataTable && $.fn.dataTable.isDataTable('#filedLeavesTable')) {
                    $('#filedLeavesTable').dataTable().fnClearTable();
                    $('#filedLeavesTable').dataTable().fnDestroy();
                  } else if ($.fn.dataTable.fnIsDataTable && $.fn.dataTable.fnIsDataTable('#filedLeavesTable')) {
                    $('#filedLeavesTable').dataTable().fnClearTable();
                    $('#filedLeavesTable').dataTable().fnDestroy();
                  }
                }

                var opts = {
                  paging: true,
                  pageLength: -1,
                  lengthMenu: [[-1, 10, 25, 50, 100], ['All', 10, 25, 50, 100]],
                  ordering: true,
                  responsive: false,
                  scrollX: true,
                  autoWidth: false,
                  dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                  language: {
                    paginate: { previous: '&laquo;', next: '&raquo;' },
                    emptyTable: 'No leaves filed yet.'
                  },
                  columnDefs: [ { orderable: false, targets: 5 } ]
                };

                // If we have explicit dataRows, prefer initializing DataTable with `data` option
                if (dataRows && dataRows.length) {
                  var columns = [
                    { title: 'Date Filed' },
                    { title: 'Type' },
                    { title: 'Date From' },
                    { title: 'Date To' },
                    { title: 'Status' },
                    { title: 'Action', orderable: false },
                    { title: 'MonthKey', visible: false }
                  ];

                  var dtOpts = Object.assign({}, opts, { data: dataRows, columns: columns });

                  if (useCapital) {
                    $('#filedLeavesTable').DataTable(dtOpts);
                    console.log('filedLeavesTable DataTable initialized with data (DataTable)');
                  } else {
                    $('#filedLeavesTable').dataTable(dtOpts);
                    console.log('filedLeavesTable DataTable initialized with data (dataTable)');
                  }
                } else {
                  if (useCapital) {
                    $('#filedLeavesTable').DataTable(opts);
                    console.log('filedLeavesTable DataTable initialized (DataTable)');
                  } else {
                    $('#filedLeavesTable').dataTable(opts);
                    console.log('filedLeavesTable DataTable initialized (dataTable)');
                  }
                }

                // Log counts
                console.log('DOM rows after DataTable init:', $('#filedLeavesTable tbody tr').length);
                try {
                  var api = useCapital ? $('#filedLeavesTable').DataTable() : $('#filedLeavesTable').dataTable().api();
                  console.log('DataTable rows count (API):', api.rows().count());

                  // Setup month filtering via DataTables custom filter
                  // Reset ext.search to avoid duplicates
                  $.fn.dataTable.ext.search = [];
                  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex, rowData, counter) {
                    var selected = $('#monthFilter').val() || '';
                    if (!selected) return true;
                    // rowData has MonthKey at index 6 when using `data` option
                    var mk = '';
                    try { mk = rowData[6] || ''; } catch (e) { mk = ''; }
                    return selected === mk;
                  });

                  // redraw on filter change
                  $('#monthFilter').off('change.leave').on('change.leave', function(){
                    try { api.draw(); } catch(e){ console.warn('draw failed', e); }
                  });

                } catch (e) {
                  console.warn('Could not read DataTable API rows count', e);
                }
              } catch (e) {
                console.error('DataTable init error:', e);
              }
              return;
            }

          attempts++;
          if (attempts < maxAttempts) {
            setTimeout(init, delayMs);
          } else {
            console.warn('DataTables plugin not found after retries; table will remain plain HTML.');
          }
        }

        init();
      })();
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
