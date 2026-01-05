<div class="modal fade" id="modalAddCTO" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Compensatory Time Off Leave</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="ctoForm" action="../includes/functions/AddCTO.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="EmpNo" id="ctoEmpNo">

          <div class="form-group">
            <input class="form-control" name="reason" id="ctoReason" placeholder="Reason" required>
          </div>

          <div class="form-group text-center">
            <label class="me-2">Credit Factor:</label>
            <input type="radio" name="ctoHours" value="1" required> 1
            <input type="radio" name="ctoHours" value="1.25"> 1.25
            <input type="radio" name="ctoHours" value="1.5"> 1.50
          </div>

          <div class="form-group">
            <input class="form-control" name="numHours" id="ctoNumHours" placeholder="Total Number of Hours Worked" required>
          </div>

          <div class="form-group">
            <input class="form-control" name="HoliType" id="ctoHoliType" placeholder="Type of Holiday" readonly>
          </div>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="submit" name="btnAdd" class="btn btn-success fw-bold px-4 py-2">
            Add Leave
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
