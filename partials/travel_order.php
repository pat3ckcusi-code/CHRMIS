<?php
// Travel Order partial
session_start();
$dept = $_SESSION['Dept'] ?? '';
?>
<div class="card">
  <div class="card-header"><h3 class="card-title"><i class="fas fa-plane"></i> Travel Order</h3></div>
  <div class="card-body">
    <form id="travelOrderForm">
      <div class="form-group">
        <label>Select Employees (choose one or more)</label>
        <div class="mb-2"><input type="checkbox" id="selectAllEmployees"> <label for="selectAllEmployees">Select All</label></div>
        <div id="employeesList" style="max-height:220px; overflow:auto; border:1px solid #eee; padding:8px;">
          <div class="text-muted">Loading employees...</div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="departure_date">Date of Departure</label>
          <input type="date" class="form-control" id="departure_date" name="departure_date" required>
        </div>
        <div class="form-group col-md-6">
          <label for="return_date">Date of Return</label>
          <input type="date" class="form-control" id="return_date" name="return_date" required>
        </div>
      </div>

      <div class="form-group">
        <label for="destination">Destination</label>
        <input type="text" class="form-control" id="destination" name="destination" required>
      </div>

      <div class="form-group">
        <label for="purpose">Purpose of Travel</label>
        <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="per_diem">Per Diem / Expenses Allowed</label>
          <input type="text" class="form-control" id="per_diem" name="per_diem">
        </div>
        <div class="form-group col-md-6">
          <label for="appropriation">Appropriation (Charge to)</label>
          <input type="text" class="form-control" id="appropriation" name="appropriation">
        </div>
      </div>

      <div class="form-group">
        <label for="remarks">Remarks / Special Instructions</label>
        <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
      </div>

      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" id="submitTravelOrder">Submit Travel Order</button>
      </div>
    </form>
  </div>
</div>

<script>
;(function(){
  const dept = "<?php echo addslashes($dept); ?>";

  function renderEmployees(list){
    if(!Array.isArray(list) || list.length===0){
      $('#employeesList').html('<div class="text-muted">No employees found for this department.</div>');
      return;
    }
    const html = list.map(emp => {
      const label = emp.FullName + (emp.Position ? (' — ' + emp.Position) : '');
      return `<div class="form-check"><input class="form-check-input employee-checkbox" type="checkbox" value="${emp.EmpNo}" id="emp_${emp.EmpNo}" name="employees[]"><label class="form-check-label" for="emp_${emp.EmpNo}">${label}</label></div>`;
    }).join('');
    $('#employeesList').html(html);
  }

  function fetchEmployees(){
    $('#employeesList').html('<div class="text-muted">Loading employees...</div>');
    $.getJSON('../api/get_department_employees.php', {dept: dept})
      .done(function(data){ renderEmployees(data); })
      .fail(function(){ $('#employeesList').html('<div class="text-danger">Failed to load employees.</div>'); });
  }

  // Select all handler
  $(document).on('change', '#selectAllEmployees', function(){
    const checked = $(this).is(':checked');
    $('#employeesList').find('.employee-checkbox').prop('checked', checked);
  });

  // Form submit with confirmation
  $(document).on('submit', '#travelOrderForm', function(e){
    e.preventDefault();
    const employees = [];
    $('#employeesList').find('.employee-checkbox:checked').each(function(){ employees.push($(this).val()); });
    if(employees.length === 0){
      Swal.fire('Validation', 'Please select at least one employee.', 'warning');
      return;
    }

    const payload = {
      employees: employees,
      departure_date: $('#departure_date').val(),
      return_date: $('#return_date').val(),
      destination: $('#destination').val(),
      purpose: $('#purpose').val(),
      per_diem: $('#per_diem').val(),
      appropriation: $('#appropriation').val(),
      remarks: $('#remarks').val()
    };

    // Basic validation
    if(!payload.departure_date || !payload.return_date || !payload.destination || !payload.purpose){
      Swal.fire('Validation', 'Please fill required fields: dates, destination and purpose.', 'warning');
      return;
    }

    // Confirmation
    function formatDateDisplay(d) {
      if (!d) return '';
      const dt = new Date(d);
      if (isNaN(dt)) return d;
      return dt.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
    }

    const summaryHtml = `
      <div style="text-align:left">
        <div><strong>Employees:</strong> ${employees.length}</div>
        <div><strong>Destination:</strong> ${$('<div>').text(payload.destination).html()}</div>
        <div><strong>Departure:</strong> ${formatDateDisplay(payload.departure_date)}</div>
        <div><strong>Return:</strong> ${formatDateDisplay(payload.return_date)}</div>
        <div><strong>Purpose:</strong> ${$('<div>').text(payload.purpose).html()}</div>
      </div>`;

    Swal.fire({
      title: 'Confirm Travel Order Submission',
      html: summaryHtml,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit',
      cancelButtonText: 'Cancel',
      reverseButtons: true
    }).then((result) => {
      if (!result.isConfirmed) return;

      // Show loading while saving and sending email
      Swal.fire({
        title: 'Submitting and sending notification...',
        html: '<div class="text-center"><i class="fas fa-envelope-open-text fa-2x fa-spin" style="margin-bottom:8px"></i><div>Please wait while we save the travel order and notify the Department Head.</div></div>',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      $.ajax({
        url: '../api/save_travel_order.php',
        method: 'POST',
        data: JSON.stringify(payload),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json'
      }).done(function(resp){
        Swal.close();
        if(resp && resp.success){
          if (resp.deptEmailSent) {
            Swal.fire('Saved', 'Travel order submitted and Department Head notified by email.', 'success');
          } else {
            Swal.fire('Saved', 'Travel order saved. Notification email could not be sent to the Department Head.', 'warning');
          }
          // reset form
          $('#travelOrderForm')[0].reset();
          $('#employeesList').find('.employee-checkbox').prop('checked', false);
        } else {
          Swal.fire('Error', (resp && resp.message) ? resp.message : 'Failed to submit travel order.', 'error');
        }
      }).fail(function(xhr){
        Swal.close();
        Swal.fire('Error', 'Server error occurred while submitting.', 'error');
      });
    });
  });

  // Initial fetch
  fetchEmployees();
})();
</script>
