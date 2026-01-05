<!-- Change Password Modal -->
<div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalPasswordLabel">
          <i class="fas fa-lock"></i> Change Password
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <form id="changePassForm" method="post">
        <div class="modal-body">

          <div class="form-group mb-3">
            <label for="newpass">New Password</label>
            <input type="password" class="form-control" id="newpass" name="newpass" placeholder="Enter new password" required oninput="chkpass()">
          </div>

          <div class="form-group mb-3">
            <label for="confirmpass">Confirm Password</label>
            <input type="password" class="form-control" id="confirmpass" name="confirmpass" placeholder="Re-enter new password" required oninput="chkpass()">
            <small id="passError" class="text-danger" style="display:none;">❌ Passwords do not match.</small>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-success btn-lg" id="btnChange">
            <i class="fas fa-save"></i> Change
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- JS - BRUTE FORCE SOLUTION -->
<script>
function chkpass() {
    const newpass = document.getElementById('newpass').value;
    const confirmpass = document.getElementById('confirmpass').value;
    const passError = document.getElementById('passError');
    const btnChange = document.getElementById('btnChange');

    if (newpass !== confirmpass) {
        passError.style.display = 'block';
        btnChange.disabled = true;
    } else {
        passError.style.display = 'none';
        btnChange.disabled = false;
    }
}

// Submit password change - BRUTE FORCE VERSION
$('#changePassForm').submit(function(e) {
    e.preventDefault();

    const newpass = $('#newpass').val();
    const confirmpass = $('#confirmpass').val();

    // Basic validation
    if (newpass !== confirmpass) {
        Swal.fire('Error', 'Passwords do not match!', 'error');
        return;
    }

    if (newpass.length < 8) {
        Swal.fire('Error', 'Password must be at least 8 characters!', 'error');
        return;
    }

    Swal.fire({
        title: 'Change Password',
        text: "Are you sure you want to change your password?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const btnChange = $('#btnChange');
            btnChange.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Changing...');

            // Send password change request
            $.ajax({
                url: '../includes/functions/changepass.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // BRUTE FORCE: Remove ALL modal-related elements
                        $('#modalPassword').remove();
                        $('.modal-backdrop').remove();
                        $('.modal').remove();
                        
                        // Reset body
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');
                        
                        // Clear modal container
                        $('#modalContainer').html('');
                        
                        // Show success message with timer
                        Swal.fire({
                            icon: "success",
                            title: "Password Changed!",
                            text: "Your password has been updated successfully. Please login with your new password.",
                            showConfirmButton: true,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            // After user clicks OK
                            if (result.isConfirmed) {
                                // Reload the entire login page
                                location.reload();
                            }
                        });
                        
                    } else {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: response.icon,
                            confirmButtonText: 'OK'
                        });
                        btnChange.prop('disabled', false).html('<i class="fas fa-save"></i> Change');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to connect to server. Please try again.', 'error');
                    btnChange.prop('disabled', false).html('<i class="fas fa-save"></i> Change');
                }
            });
        }
    });
});
</script>
