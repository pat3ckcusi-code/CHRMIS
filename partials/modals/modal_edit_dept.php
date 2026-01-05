<div class="modal fade" id="ModalDept" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Department</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="DeptEditForm">
          <input type="hidden" id="deptId" name="deptId">
          <div class="form-group">
            <label>Department Code</label>
            <input type="text" id="txtDeptCode" name="txtDeptName" class="form-control">
          </div>
          <div class="form-group">
            <label>Department Name</label>
            <input type="text" id="txtDeptName" name="txtDeptName" class="form-control">
          </div>
          <div class="form-group">
            <label>Department Head</label>
            <input type="text" id="txtDeptHead" name="txtDeptHead" class="form-control">
          </div>
          <div class="form-group">
            <label>Designation</label>
            <input type="text" id="txtDesignation" name="txtDesignation" class="form-control">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="saveDeptChanges" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>
   $('#saveDeptChanges').on('click', function() {
    $.ajax({
        url: '../api/api_department.php?Edit_dept',
        type: 'POST',
        data: $('#DeptEditForm').serialize() + '&update_dept=true',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire("Success", response.message, "success");
                $('#ModalDept').modal('hide');
                empTable.ajax.reload(null, false); // reload table without resetting pagination
            } else {
                Swal.fire("Error", response.message || "Unknown error", "error");
            }
        },
        error: function(xhr, status, error) {
            console.log("AJAX error:", error, xhr.responseText);
        }
    });
});


</script>