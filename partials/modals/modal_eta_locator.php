<?php
// pages/modal_eta_locator.php
?>


<div class="modal fade" id="etaLocatorModal" tabindex="-1" role="dialog" aria-labelledby="etaLocatorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="etaLocatorModalLabel"><i class="fas fa-plane-departure"></i> File New ETA / Locator</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="submit_eta_locator.php" method="POST" id="etaLocatorForm">
          <div class="modal-body">

            <div class="row mb-3">
              <!-- Application Type -->
              <div class="col-md-6">
                <label for="applicationType" class="form-label">Application Type</label>
                <select class="form-control" id="applicationType" name="applicationType" required>
                  <option value="" selected disabled>Select Type</option>
                  <option value="ETA">ETA (Employee Travel Authorization)</option>
                  <option value="Locator">Locator</option>
                </select>
              </div>

              <!-- ETA Destination -->
              <div class="col-md-6 eta-only" style="display:none;">
                <label for="etaDestination" class="form-label">Destination</label>
                <input type="text" class="form-control" id="etaDestination" name="etaDestination" placeholder="Enter destination">
              </div>

              <!-- Locator Location -->
              <div class="col-md-6 locator-only" style="display:none;">
                <label for="locatorLocation" class="form-label">Location</label>
                <input type="text" class="form-control" id="locatorLocation" name="locatorLocation" placeholder="Enter location">
              </div>
              <!-- Departure Date (ETA only) -->
              <div class="col-md-6 eta-only" style="display:none;">
                <label for="travelDate" class="form-label">Departure Date</label>
                <input 
                  type="date" 
                  class="form-control" 
                  id="travelDate" 
                  name="travelDate" 
                  min="<?= date('Y-m-d') ?>" 
                  value="<?= date('Y-m-d') ?>" 
                  required
                >
              </div>

              <!-- Arrival Date (ETA only) -->
              <div class="col-md-6 eta-only" style="display:none;">
                <label for="arrivalDate" class="form-label">Arrival Date</label>
                <input 
                  type="date" 
                  class="form-control" 
                  id="arrivalDate" 
                  name="arrivalDate" 
                  min="<?= date('Y-m-d') ?>" 
                  value="<?= date('Y-m-d') ?>" 
                  required
                >
              </div>

            <!-- ETA Business Purpose -->
              <div class="col-md-6 eta-only" style="display:none;">
                <label for="businessPurposeETA" class="form-label">Business Purpose</label>
                <select class="form-control" id="businessPurposeETA">
                  <option value="" selected disabled>Select Purpose</option>
                  <option value="Audit-Inspection-Licensing">Audit-Inspection-Licensing</option>
                  <option value="Client Support">Client Support</option>
                  <option value="Conference">Conference</option>
                  <option value="Construction Repair Maintenance">Construction Repair Maintenance</option>
                  <option value="Economic Development">Economic Development</option>
                  <option value="Legal-Law Enforcement">Legal-Law Enforcement</option>
                  <option value="Legislator">Legislator</option>
                  <option value="Meeting">Meeting</option>
                  <option value="Training">Training</option>
                  <option value="Seminar">Seminar</option>
                  <option value="General Expense/Other">General Expense/Other</option>
                </select>
              </div>

              <!-- Locator Business Purpose -->
              <div class="col-md-6 locator-only" style="display:none;">
                <label for="businessPurposeLocator" class="form-label">Business Purpose</label>
                <select class="form-control" id="businessPurposeLocator">
                  <option value="" selected disabled>Select Purpose</option>
                  <option value="Official">Official</option>
                  <option value="Personal">Personal</option>
                </select>
              </div>

<!-- ✅ Single hidden field actually submitted -->
<input type="hidden" id="businessPurpose" name="businessPurpose">

              <!-- Locator Departure & Arrival Times -->
              <div class="col-md-6 locator-only" style="display:none;">
                <label for="departureTime" class="form-label">Intended Time of Departure</label>
                <input type="time" class="form-control" id="departureTime" name="intended_departure">
              </div>

              <div class="col-md-6 locator-only" style="display:none;">
                <label for="arrivalTime" class="form-label">Intended Time of Arrival</label>
                <input type="time" class="form-control" id="arrivalTime" name="intended_arrival">
              </div>

              <!-- Other Purpose (ETA only) -->
              <div class="col-md-6 mt-2" id="otherPurposeDiv" style="display:none;">
                <label for="otherPurpose" class="form-label">Specify Travel Type</label>
                <input type="text" class="form-control" id="otherPurpose" name="otherPurpose" placeholder="Specify type of travel">
              </div>

              <!-- Travel Detail -->
              <div class="col-md-12 mt-2">
                <label for="travelDetail" class="form-label">Detail of Travel / Purpose of Travel</label>
                <textarea class="form-control" id="travelDetail" name="travelDetail" rows="3" placeholder="Describe travel details" required></textarea>
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Application</button>
          </div>
        </form>


    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<!-- Bootstrap Datepicker CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<script>
$(document).ready(function() {
  const etaFields = $('.eta-only');
  const locatorFields = $('.locator-only');

  // Toggle fields based on application type
  $('#applicationType').on('change', function() {
    const type = this.value;

    if(type === 'ETA') {
      etaFields.show();
      locatorFields.hide();
      $('#businessPurposeETA').prop('required', true);
      $('#departureTime, #arrivalTime, #businessPurposeLocator').prop('required', false).val('');
    } else if(type === 'Locator') {
      etaFields.hide();
      locatorFields.show();
      $('#departureTime, #arrivalTime, #businessPurposeLocator').prop('required', true);
      $('#businessPurposeETA, #otherPurpose').prop('required', false).val('');
    } else {
      etaFields.hide();
      locatorFields.hide();
      $('#etaDestination, #locatorLocation, #departureTime, #arrivalTime, #businessPurposeETA, #businessPurposeLocator, #otherPurpose').prop('required', false).val('');
    }
  });

  // ETA "Other Purpose" toggle
  $('#businessPurposeETA').on('change', function() {
    if ($(this).val() === 'General Expense/Other') {
      $('#otherPurposeDiv').slideDown();
      $('#otherPurpose').prop('required', true);
    } else {
      $('#otherPurposeDiv').slideUp();
      $('#otherPurpose').prop('required', false).val('');
    }
  });

  // ETA Date validation (Departure vs Arrival)
  const travelDate = $('#travelDate');
  const arrivalDate = $('#arrivalDate');

  // Prevent choosing past dates
  const today = new Date().toISOString().split('T')[0];
  travelDate.attr('min', today);
  arrivalDate.attr('min', today);

  // When departure date changes
  travelDate.on('change', function() {
    arrivalDate.attr('min', this.value); // Arrival cannot be earlier
    if(arrivalDate.val() && arrivalDate.val() < this.value){
      Swal.fire('Warning!', 'Arrival date cannot be earlier than departure date.', 'warning');
      arrivalDate.val('');
    }
  });

  // When arrival date changes
  arrivalDate.on('change', function() {
    if(this.value < travelDate.val()){
      Swal.fire('Warning!', 'Arrival date must be later than or equal to departure date.', 'warning');
      $(this).val('');
    }
  });

  // Form submit
  $('#etaLocatorForm').submit(function(e) {
    e.preventDefault();

    const appType = $('#applicationType').val();
    const travelDetail = $('#travelDetail').val().trim();

    const businessPurpose = appType === 'ETA' 
      ? $('#businessPurposeETA').val() 
      : $('#businessPurposeLocator').val();

    $('#businessPurpose').val(businessPurpose);

    const etaDest = $('#etaDestination').val().trim();
    const departureTime = $('#departureTime').val();
    const arrivalTime = $('#arrivalTime').val();
    const otherPurpose = $('#otherPurpose').val().trim();

    if(!appType || !businessPurpose || !travelDetail){
      Swal.fire('Warning!', 'Please fill in all required fields', 'warning');
      return;
    }

    if(appType === 'ETA' && !etaDest){
      Swal.fire('Warning!', 'Please enter ETA Destination', 'warning');
      return;
    }

    if(appType === 'Locator'){
       if(!departureTime || !arrivalTime){
        Swal.fire('Warning!', 'Please fill in all Locator fields', 'warning');
         return;
       }
      if(!departureTime){
        Swal.fire('Warning!', 'Please fill in all Locator fields', 'warning');
        return;
      }

      const dep = new Date('1970-01-01T'+departureTime+':00');
      const arr = new Date('1970-01-01T'+arrivalTime+':00');

      if(arr <= dep){
        Swal.fire('Warning!', 'Arrival time must be after departure time', 'warning');
        return;
      }

      const diffHours = (arr - dep) / 1000 / 3600;
      if((businessPurpose === 'Personal' && diffHours > 2) || (businessPurpose === 'Official' && diffHours > 3)){
        Swal.fire('Warning!', 'Time exceeded maximum allowed for selected business purpose', 'warning');
        return;
      }
    }

    Swal.fire({
      title: 'Confirm Submission',
      text: "Are you sure you want to file this ETA / Locator?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
          url: '../api/api_eta_travel.php?add_eta_locator',
          type: 'POST',
          processData: false,
          contentType: false,
          dataType: 'json',
          data: new FormData(this),
          success: function(response){
            if(response.success){
              Swal.fire('Success!', response.message, 'success').then(()=>location.reload());
            } else {
              Swal.fire('Warning!', response.message, 'warning');
            }
          },
          error: function(xhr, status, error){
            Swal.fire('Error!', 'Server error: '+error, 'error');
          }
        });
      }
    });
  });
});
</script>
