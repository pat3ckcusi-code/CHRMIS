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
      <form id="applyLeaveForm" method="POST" action="/CHRMIS/api/leave/apply.php" enctype="multipart/form-data">
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
              <option value="LWOP">Leave Without Pay</option>
            </select>
            <small class="text-muted" id="leaveHint"></small>
          </div>

          <!-- DATE RANGE -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Date From</label>
              <input type="date" class="form-control" name="date_from" id="dateFrom" required>
              <small class="text-danger d-none" id="dateFromError"></small>
            </div>
            <div class="form-group col-md-6">
              <label>Date To</label>
              <input type="date" class="form-control" name="date_to" id="dateTo" required>
              <small class="text-danger d-none" id="dateToError"></small>
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
      $.getJSON('/CHRMIS/api/leave/balance.php', function(bal) {
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
  const $from = $('#dateFrom');
  const $to = $('#dateTo');
  const $fromErr = $('#dateFromError');
  const $toErr = $('#dateToError');
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


  // Helper to update min attributes based on leave type
  function updateDateMins() {
    const leaveType = $('#leaveType').val();
    const t = todayStr();
    if (leaveType === 'Vacation') {
      $from.attr('min', t); 
    } else {
      $from.removeAttr('min'); 
    }
    if ($from.val()) {
      $to.attr('min', $from.val());
    } else {
      if (leaveType === 'Vacation') {
        $to.attr('min', t);
      } else {
        $to.removeAttr('min');
      }
    }
  }

  // Initialize mins when modal opens
  $('#applyLeaveModal').on('show.bs.modal', function () {
    updateDateMins();
    clearError($from, $fromErr);
    clearError($to, $toErr);
  });

  // Update mins when leave type changes
  $('#leaveType').on('change', function () {
    updateDateMins();
  });

  $from.on('change', function () {
    clearError($from, $fromErr);
    const v = $(this).val();
    if (!v) {
      $to.attr('min', todayStr());
      return;
    }
    if (isWeekend(v)) {
      showError($from, $fromErr, 'Weekends are not allowed. Please choose a weekday.');
      $(this).val('');
      $to.attr('min', todayStr());
      return;
    }
    $to.attr('min', v);
    if ($to.val() && $to.val() < v) {
      showError($to, $toErr, 'Date To cannot be before Date From.');
    } else {
      clearError($to, $toErr);
    }
  });

  $to.on('change', function () {
    clearError($to, $toErr);
    const v = $(this).val();
    if (!v) return;
    if (isWeekend(v)) {
      showError($to, $toErr, 'Weekends are not allowed. Please choose a weekday.');
      $(this).val('');
      return;
    }
    const fromVal = $from.val() || todayStr();
    if (v < fromVal) {
      showError($to, $toErr, 'Date To cannot be before Date From.');
      $(this).val('');
    }
  });

  $form.on('submit', function (e) {
    clearError($from, $fromErr);
    clearError($to, $toErr);
    let valid = true;
    const fromVal = $from.val();
    const toVal = $to.val();
    const t = todayStr();
    if (!fromVal) {
      showError($from, $fromErr, 'Please select Date From.');
      valid = false;
    } else if (isWeekend(fromVal)) {
      showError($from, $fromErr, 'Weekends are not allowed. Please choose a weekday.');
      valid = false;
    } else if ($('#leaveType').val() === 'Vacation' && fromVal < t) {
      showError($from, $fromErr, 'Date From cannot be in the past for Vacation Leave.');
      valid = false;
    }
    if (!toVal) {
      showError($to, $toErr, 'Please select Date To.');
      valid = false;
    } else if (isWeekend(toVal)) {
      showError($to, $toErr, 'Weekends are not allowed. Please choose a weekday.');
      valid = false;
    } else if (toVal < (fromVal || t)) {
      showError($to, $toErr, 'Date To cannot be before Date From.');
      valid = false;
    }
    if (!valid) {
      e.preventDefault();
      return false;
    }
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
        Swal.fire('Success', 'Your leave application has been filed.', 'success').then(() => {
          $('#applyLeaveModal').modal('hide');
          location.reload();
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
