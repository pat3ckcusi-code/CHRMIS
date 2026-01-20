<div class="modal fade" id="applyLeaveModal" tabindex="-1" role="dialog" aria-labelledby="applyLeaveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="applyLeaveModalLabel">
          <i class="fas fa-umbrella-beach"></i> File Leave Application (CS Form No. 6)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <!-- FORM -->
      <form id="applyLeaveForm" method="POST" action="../api/leave/apply.php" enctype="multipart/form-data">
        <div class="modal-body">

          <!-- LEAVE TYPE -->
          <div class="form-group">
            <label><strong>Type of Leave</strong></label>
            <select class="form-control" name="leave_type" id="leaveType" required>
              <option value="">-- Select Leave Type --</option>
              <option value="Vacation">Vacation Leave (VL)</option>
              <option value="Sick">Sick Leave (SL)</option>
              <option value="Maternity">Maternity Leave</option>
              <option value="Paternity">Paternity Leave</option>
              <option value="Adoption">Adoption Leave</option>
              <option value="Solo Parent">Solo Parent Leave</option>
              <option value="VAWC">VAWC Leave</option>
              <option value="Gynecological">Special Leave (Gynecological)</option>
              <option value="Emergency">Special Emergency (Calamity) Leave</option>
              <option value="Special Privilege">Special Leave Privilege</option>
              <option value="Study">Study / Examination Leave</option>
            </select>
            <small class="text-muted" id="leaveHint"></small>
          </div>

          <!-- MULTI-DATE SELECTION -->
          <div class="form-row">
            <div class="form-group col-md-8">
              <label>Select Dates</label>
              <div class="input-group">
                <input type="date" class="form-control" id="singleDate">
                <div class="input-group-append">
                  <button type="button" class="btn btn-outline-secondary" id="addDateBtn">Add</button>
                </div>
              </div>
              <small class="form-text text-muted">Select multiple non-consecutive weekdays. Weekends and holidays are excluded.</small>
            </div>
            <div class="form-group col-md-4">
              <label>Selected Dates</label>
              <div id="selectedDatesList" class="border rounded p-2" style="min-height:46px"></div>
            </div>
          </div>

          <!-- REASON -->
          <div class="form-group">
            <label>Reason / Purpose</label>
            <textarea class="form-control" name="reason" rows="2" required></textarea>
          </div>

          <!-- DOCUMENT UPLOAD -->
          <div class="form-group d-none" id="docUpload">
            <label>Supporting Document</label>
            <input type="file" class="form-control-file" name="document">
            <small class="text-muted" id="docHint"></small>
          </div>

          <!-- SPECIAL NOTES -->
          <div class="alert alert-info d-none" id="specialNote"></div>
          <div class="alert alert-warning d-none" id="slToVlWarning"></div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit Application
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
/**
 * UI RULES ONLY — backend must still validate
 * Based on CSC / CS Form No. 6 (Revised 2020)
 */
$('#leaveType').on('change', function () {
  const type = $(this).val();

  $('#docUpload').addClass('d-none');
  $('#leaveHint').text('');
  $('#docHint').text('');
  $('#specialNote').addClass('d-none').text('');
  $('#slToVlWarning').addClass('d-none').text('');

  switch (type) {
    case 'Vacation':
      $('#leaveHint').text('File at least 1 week in advance. Subject to available VL credits.');
      break;

    case 'Sick':
      $('#leaveHint').text('Medical certificate required for hospitalization or prolonged illness.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Medical certificate / hospital record');
      // Check sick leave balance
      $.getJSON('../api/leave/balance.php', function(bal) {
        if (bal && (bal.sick === 0 || bal.sick === "0")) {
          $('#slToVlWarning').removeClass('d-none').text('You have no available Sick Leave credits. Filing Sick Leave will be deducted from your Vacation Leave balance.');
        }
      });
      break;

    case 'Maternity':
      $('#leaveHint').text('105 days with full pay (RA 11210). Continuous leave.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Medical certificate, birth/maternity documents');
      $('#specialNote').removeClass('d-none')
        .text('Solo parents may be entitled to an additional 15 days upon submission of Solo Parent ID.');
      break;

    case 'Paternity':
      $('#leaveHint').text('7 working days for the first four deliveries.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Marriage contract and birth certificate / medical certificate');
      break;

    case 'Adoption':
      $('#leaveHint').text('60 days with full pay for qualified adoptive parents.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Adoption decree / placement authority');
      break;

    case 'Solo Parent':
      $('#leaveHint').text('7 working days per year for qualified solo parents.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Valid Solo Parent ID / certification');
      break;

    case 'VAWC':
      $('#leaveHint').text('Up to 10 days for victim-survivors of VAWC.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('BPO, PPO, police or medical certification');
      break;

    case 'Gynecological':
      $('#leaveHint').text('Up to 2 months with full pay after gynecological surgery.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Medical certificate and proof of surgery');
      break;

    case 'Emergency':
      $('#leaveHint').text('Up to 5 days for employees affected by calamity.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Proof of being affected by calamity');
      break;

    case 'Special Privilege':
      $('#leaveHint').text('Maximum of 3 days per year (non-cumulative).');
      break;

    case 'Study':
      $('#leaveHint').text('Subject to agency approval and CSC guidelines.');
      $('#docUpload').removeClass('d-none');
      $('#docHint').text('Enrollment / exam / review proof');
      break;

    case 'LWOP':
      $('#leaveHint').text('Subject to approval; used when no leave credits remain.');
      break;
  }
});
</script>

<script>
(function ($) {
  const $single = $('#singleDate');
  const $addBtn = $('#addDateBtn');
  const $list = $('#selectedDatesList');
  const $form = $('#applyLeaveForm');

  function todayStr() {
    return new Date().toISOString().split('T')[0];
  }

  function isWeekend(dateStr) {
    const d = new Date(dateStr);
    const day = d.getDay();
    return day === 0 || day === 6;
  }

  function showError($el, $errEl, msg) {
    $errEl.text(msg).removeClass('d-none');
    $el.addClass('is-invalid');
  }

  function clearError($el, $errEl) {
    $errEl.text('').addClass('d-none');
    $el.removeClass('is-invalid');
  }


  // Helper: manage selected dates list and hidden inputs
  function clearSelected() {
    $list.empty();
  }

  function addSelectedDate(dateStr) {
    const id = 'd_' + dateStr.replace(/-/g, '_');
    if ($list.find('#' + id).length) return false;
    const $pill = $(
      `<div id="${id}" class="d-flex align-items-center justify-content-between mb-1" data-date="${dateStr}">\n         <span>${dateStr}</span>\n         <div>\n           <button type="button" class="btn btn-sm btn-danger remove-date-btn">&times;</button>\n           <input type="hidden" name="dates[]" value="${dateStr}">\n         </div>\n       </div>`
    );
    $list.append($pill);
    return true;
  }

  function removeSelectedDate(el) {
    $(el).closest('div[data-date]').remove();
  }

  $addBtn.on('click', function () {
    const v = $single.val();
    const t = todayStr();
    if (!v) return;
    if (isWeekend(v)) {
      Swal.fire('Invalid date', 'Weekends are not allowed. Please choose a weekday.', 'warning');
      return;
    }
    const leaveType = $('#leaveType').val();
    if (leaveType === 'Vacation' && v < t) {
      Swal.fire('Invalid date', 'Vacation leave dates cannot be in the past.', 'warning');
      return;
    }
    const ok = addSelectedDate(v);
    if (!ok) {
      Swal.fire('Duplicate', 'This date is already selected.', 'info');
      return;
    }
    $single.val('');
  });

  $list.on('click', '.remove-date-btn', function () {
    removeSelectedDate(this);
  });

  $form.on('submit', function (e) {
    if ($list.find('input[name="dates[]"]').length === 0) {
      e.preventDefault();
      Swal.fire('No dates', 'Please add at least one date to file leave.', 'warning');
      return false;
    }
    // server will validate weekdays and holidays
  });
})(jQuery);
</script>

<script>
// Intercept submit, show confirm (SweetAlert) and POST via AJAX to show a friendly response
$('#applyLeaveForm').on('submit', function (e) {
  e.preventDefault();
  const form = this;

  Swal.fire({
    title: 'Confirm filing',
    text: 'Do you want to file this leave application?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, file it',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (!result.isConfirmed) return;

    const fd = new FormData(form);

    Swal.fire({
      title: 'Submitting',
      text: 'Please wait…',
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false
    });

    $.ajax({
      url: $(form).attr('action'),
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json'
    }).done((resp) => {
      if (resp && resp.success) {
        // Force-close modal (hide + remove backdrop), reset form, then refresh table
        try {
          $('#applyLeaveModal').modal('hide');
          $('.modal-backdrop').remove();
        } catch (e) {}
        try {
          // reset the form so subsequent opens are clean
          form.reset();
        } catch (e) {}

        // trigger refresh event and call global refresh if available; fallback to reload
        try { $(document).trigger('leave:refresh'); } catch (e) {}
        try {
          if (typeof window.leaveRefresh === 'function') {
            window.leaveRefresh();
          }
        } catch (e) {}

        Swal.fire('Success', 'Your leave application has been filed.', 'success').then(() => {
          // if refresh didn't run, reload as last resort
          setTimeout(function () {
            if (!window.leaveRefresh) location.reload();
          }, 600);
        });
      } else {
        const msg = resp?.error || 'Failed to create leave.';
        Swal.fire('Error', msg, 'error');
      }
    }).fail((xhr) => {
      let msg = 'Failed to create leave.';
      try {
        const j = JSON.parse(xhr.responseText);
        msg = j.error || msg;
      } catch (err) {}
      Swal.fire('Error', msg, 'error');
    });
  });
});
</script>
