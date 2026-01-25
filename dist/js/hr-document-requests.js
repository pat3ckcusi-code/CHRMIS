$(document).ready(function() {

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
          const docTypes = new Set();
          resp.data.forEach(r => {
            if(r.status === 'Rejected' || r.status === 'Completed') return;
            if(r.document_type) docTypes.add(r.document_type);
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
        // Populate Document Type filter
        const $docSel = $('#filterDocType');
        if ($docSel.length) {
          $docSel.empty().append($('<option>').val('').text('All types'));
          Array.from(docTypes).sort().forEach(dt => {
            $docSel.append($('<option>').val(dt).text(dt));
          });
        }
        $('[data-toggle="tooltip"]').tooltip();
      } else {
        Swal.fire('Error', resp.message, 'error');
      }
    }, 'json');
  }

  loadRequests();

  // Status filter: filter DataTable by Status column (index 6)
  $('#filterStatus').off('change.requestFilter').on('change.requestFilter', function() {
    const val = $(this).val();
    if (val) {
      const esc = $.fn.dataTable.util.escapeRegex(val);
      table.column(6).search('^' + esc + '$', true, false).draw();
    } else {
      table.column(6).search('').draw();
    }
  });

  // Document Type filter: filter DataTable by Document Type column (index 3)
  $('#filterDocType').off('change.docTypeFilter').on('change.docTypeFilter', function() {
    const val = $(this).val();
    if (val) {
      const esc = $.fn.dataTable.util.escapeRegex(val);
      table.column(3).search('^' + esc + '$', true, false).draw();
    } else {
      table.column(3).search('').draw();
    }
  });

  // Print report: generate printable HTML from currently visible rows
  $('#printReportBtn').off('click.print').on('click.print', function() {
    const title = 'Document Requests Report';
    const statusFilter = $('#filterStatus').val() || 'All status';
    const docTypeFilter = $('#filterDocType').val() || 'All types';
    const now = new Date().toLocaleString();

    // Clone table header
    const $table = $('#hrRequestsTable').clone();
    // Remove action column (last) from cloned table for printing
    $table.find('thead th:last, tbody td:last-child').remove();

    // Build rows from DataTable's current view (only visible rows after filtering/paging)
    const rows = table.rows({ search: 'applied' }).data();
    const $printTable = $('<table class="table table-bordered" style="width:100%;border-collapse:collapse;"></table>');
    // header
    const $thead = $('<thead></thead>');
    const $hdrRow = $('<tr></tr>');
    // include all header cells except the last Action column
    $('#hrRequestsTable thead th').slice(0, -1).each(function() { $hdrRow.append($('<th></th>').text($(this).text())); });
    $thead.append($hdrRow);
    $printTable.append($thead);

    const $tbody = $('<tbody></tbody>');
    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      const $r = $('<tr></tr>');
      // take all columns except the last (actions)
      for (let c = 0; c < row.length - 1; c++) {
        $r.append($('<td></td>').html(row[c]));
      }
      $tbody.append($r);
    }
    $printTable.append($tbody);

    const popup = window.open('', '_blank', 'width=1000,height=800');
    if (!popup) {
      Swal.fire('Error', 'Popup blocked. Allow popups for this site to print.', 'error');
      return;
    }

    const html = `
      <html>
      <head>
        <title>${title}</title>
        <style>
          body { font-family: Arial, Helvetica, sans-serif; margin: 20px; }
          h1 { font-size: 18px; margin-bottom: 4px; }
          .meta { margin-bottom: 12px; font-size: 12px; color:#333; }
          table { width: 100%; border-collapse: collapse; }
          table, th, td { border: 1px solid #444; }
          th, td { padding: 6px; text-align: left; font-size: 12px; }
          @media print { .no-print { display:none; } }
        </style>
      </head>
      <body>
        <h1>${title}</h1>
        <div class="meta">Status: ${statusFilter} &nbsp;&nbsp;|&nbsp;&nbsp; Document Type: ${docTypeFilter} &nbsp;&nbsp;|&nbsp;&nbsp; Generated: ${now}</div>
        ${$printTable.prop('outerHTML')}
      </body>
      </html>
    `;

    popup.document.open();
    popup.document.write(html);
    popup.document.close();
    // Wait a bit for resources to render before printing
    setTimeout(() => { popup.print(); }, 500);
  });

  function showSuccess(title, message) {
    Swal.fire({
      icon: 'success',
      title: title,
      text: message,
      timer: 1500,
      showConfirmButton: false
    });
  }

  function updateStatus(options) {
    $(document).on('click', options.selector, function () {
      const id = $(this).data('id');
      const rowNode = $(this).closest('tr');

      const swalConfig = {
        title: options.confirmTitle || 'Confirm action?',
        text: options.confirmText || '',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel'
      };

      if (options.requiresNote) {
        swalConfig.input = 'text';
        swalConfig.inputLabel = options.noteLabel || 'Reason';
        swalConfig.inputPlaceholder = options.notePlaceholder || 'Enter note...';
        swalConfig.preConfirm = (note) => {
          if (!note) Swal.showValidationMessage('Please enter a reason');
          return note;
        };
      }

      Swal.fire(swalConfig).then(result => {
        if (!result.isConfirmed) return;
        const note = options.requiresNote ? result.value : null;
        // Show loading modal immediately
        Swal.fire({
          title: '<div style="display:flex;align-items:center;justify-content:center;gap:10px;"><div class="swal2-loader" style="border:4px solid #f3f3f3;border-top:4px solid #3498db;border-radius:50%;width:32px;height:32px;animation:spin 1s linear infinite;"></div><span>Processing...</span></div>',
          html: `<div style="margin-top:10px;">${options.processingText || 'Please wait...'}</div>
          <style>
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
            .swal2-loader { display:inline-block; }
          </style>`,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
          }
        });
        const minLoadingTime = 1000; 
        const startTime = Date.now();
        setTimeout(() => {
          $.ajax({
            url: options.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
              action: 'update_status',
              id: id,
              status: options.status,
              note: note
            },
            success: function(resp) {
              const elapsed = Date.now() - startTime;
              const remaining = minLoadingTime - elapsed;
              setTimeout(() => {
                Swal.close();
                if (resp.status === 'success') {
                  if (typeof options.rowUpdateCallback === 'function') {
                    options.rowUpdateCallback(rowNode, resp);
                  } else if (typeof table !== 'undefined') {
                    table.row(rowNode).remove().draw(false);
                  }
                  showSuccess(options.successTitle || options.status + '!', resp.message);
                } else {
                  Swal.fire('Error', resp.message, 'error');
                }
              }, remaining > 0 ? remaining : 0);
            },
            error: function() {
              const elapsed = Date.now() - startTime;
              const remaining = minLoadingTime - elapsed;
              setTimeout(() => {
                Swal.close();
                Swal.fire('Error', 'Unexpected error occurred.', 'error');
              }, remaining > 0 ? remaining : 0);
            }
          });
        }, 100);
      });
    });
  }
  //Approve button
  updateStatus({
    selector: '.approve-btn',
    confirmTitle: 'Approve this request?',
    confirmText: 'This will approve the request.',
    status: 'Pending',
    ajaxUrl: '../api/document_requests_api.php',
    processingText: 'Please wait while the request is being approved...',
    rowUpdateCallback: function(rowNode, resp) {
      updateRowButtons(rowNode, 'Pending'); // Custom callback if needed
    }
  });

  //Reject button
  updateStatus({
    selector: '.reject-btn',
    confirmTitle: 'Reject this request?',
    confirmText: 'This will reject the request.',
    status: 'Rejected',
    requiresNote: true,
    noteLabel: 'Reason for rejection',
    notePlaceholder: 'Enter note for employee...',
    ajaxUrl: '../api/document_requests_api.php',
    processingText: 'Please wait while the request is being rejected...'
  });

  //Release / Complete button
  updateStatus({
    selector: '.release-btn',
    confirmTitle: 'Mark as Completed?',
    confirmText: 'This will notify the employee that the document is ready for pick-up.',
    status: 'Completed',
    ajaxUrl: '../api/document_requests_api.php',
    processingText: 'Please wait while the document status is being updated...'
  });



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
