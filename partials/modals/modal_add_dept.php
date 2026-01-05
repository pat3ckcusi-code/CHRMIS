<!-- Add User Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" role="dialog" aria-labelledby="addDeptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-large" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addDeptModal">
          <i class="fas fa-user-plus"></i> Add Department
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <form id="addDeptForm" action="../api/api_department.php" method="post">
        <div class="modal-body">

        <div class="form-group">
            <label for="txtDeptCode">Department Code</label>
            <input type="text" class="form-control" id="txtDeptName" name="txtDeptCode" placeholder="Ex: CCC" required>
          </div>

          <div class="form-group">
            <label for="txtDeptName">Department Name</label>
            <input type="text" class="form-control" id="txtDeptName" name="txtDeptName" placeholder="Ex: City College of Calapan" required>
          </div>

          <div class="form-group">
            <label for="txtDeptHead">Department Head</label>
            <input type="text" class="form-control" id="txtDeptHead" name="txtDeptHead" placeholder="Department Head" required>
          </div>
          <div class="form-group">
            <label for="txtDesignation">Designation</label>
            <input type="text" class="form-control" id="txtDesignation" name="txtDesignation" placeholder="Ex: College Administrator" required>
          </div>          

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-save"></i> Save
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


<script type="text/javascript"></script>
<script>
      //Add dept Modal
      $('[data-action="addDeptForm"]').click(function() {
        document.getElementById('addUserForm').reset();
      });

      // Add dept
      $('#addDeptForm').submit(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Information',
          text: "Are you sure you want to add this?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '../api/api_department.php?add_deptinfo',
              type: 'post',
              processData: false,
              contentType: false,
              data: new FormData(this)
            }).then(function(response) {
              if(response.success){
                Swal.fire('Success!', response.message, 'success')
                location.reload();
              }else{
                Swal.fire('Warning!', response.message, 'warning')
              }

            })
          }
        })
      });
</script>
