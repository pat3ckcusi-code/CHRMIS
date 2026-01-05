<form id="addEmpForm" action="../api/api_users.php?add_userinfo=1" method="post">
  <div id="addEmpModal" class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Add New User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <div class="col-12 text-right text-danger">
              <label><i>* Denotes Required Field</i></label>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-4">
              <label for="EmpNo">Employee ID Number<span class="h5 text-danger">*</span></label>
              <input type="text" name="EmpNo" id="EmpNo" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-4">
              <label for="Fname">First Name<span class="h5 text-danger">*</span></label>
              <input type="text" name="Fname" id="Fname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
            <div class="col-3">
              <label for="Mname">Middle Name<span class="h5 text-danger"></span></label>
              <input type="text" name="Mname" id="Mname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" >
            </div>
            <div class="col-3">
              <label for="Lname">Last Name<span class="h5 text-danger">*</span></label>
              <input type="text" name="Lname" id="Lname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
            <div class="col-1">
              <label for="Extension">Extension</label>
              <input type="text" name="Extension" id="Extension" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()">
            </div>
          </div>
          
      <div class="form-group row">
              <div class="col-4">
                  <label for="BirthDate">Birthdate<span class="h5 text-danger">*</span></label>
                  <input type="date" name="BirthDate" id="BirthDate" class="form-control" onkeyup="this.value" required>
              </div>

                <div class="col-3">
                  <label for="Gender">Sex<span class="h5 text-danger">*</span></label>
                  <select id="Gender" name="Gender" class="form-control" required>
                    <option value="">--Select Sex--</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                  </select>
                </div>
                <div class="col-5">
              <label for="Office">Office/Division<span class="h5 text-danger">*</span></label>
              <select id="Office" name="Office" class="form-control" required>
                    
              </select>
            </div>  
              </div>                 
        </div> <!--modal body-->

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</form>
