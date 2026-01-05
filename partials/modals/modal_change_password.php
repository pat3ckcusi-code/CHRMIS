<form id="PWForm" action="../api/api_user_password.php" method="post">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $currentuserID; ?>" />
  <div id="PWModal" class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Update Password </h4>
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
            <div class="col-12">
              <label for="new_password">New Password* <small class="text-muted">(Minimum of 5 characters)</small></label>
              <input type="password" name="new_password" id="new_password" class="form-control" minlength="5" required>
            </div>
            <div class="col-12">
              <label for="confirm_password">Confirm New Password*</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="5" required>
            </div>
          </div>

          <input type="hidden" name="password" id="password" class="form-control">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</form>
