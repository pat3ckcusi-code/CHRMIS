<?php
session_start();
require_once('../includes/session_config.php');
if (isset($_SESSION[$session_id])) {

}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CHRMis</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../dist/img/mbs.jpg">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
  .logos-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;       
    flex-wrap: nowrap;        
    gap: 10px;                 
    overflow: hidden;          
  }

  .logos-wrapper img {
    height: auto;
    max-height: 80px;         
    width: auto;
    max-width: 25%;            
    object-fit: contain;
  }

  @media (max-width: 576px) {
    .logos-wrapper img {
      max-height: 60px;        
      max-width: 30%;         
    }
  }
</style>


</head>
<!-- <body class="hold-transition login-page" style="background-image:url(../dist/img/bg2.jpg); background-repeat:no-repeat; background:100%; background-size: cover; background-position: center bottom;"> -->
<body class="hold-transition login-page"
  style="background-image: url('../dist/img/city_hall1.jpg');
         background-repeat: no-repeat;
         background-size: cover;
         background-position: center bottom;">
<div class="login-box">
  <div class="login-logo">
    <!-- <img class="mb-1" src="../dist/img/mbs.jpg" alt="" height="92"> <br> -->
    <!-- <p class="text-light" style="text-shadow: 1px 1px 4px #000000; line-height:100%; font-size: 19px;"><strong>CALAPAN CITY</strong></small></p> -->
     <!-- <h2 class="mb-3 font-weight-normal text-light" 
    style="font-family: 'Montserrat', sans-serif; text-shadow: 1px 1px 4px #000000;"><strong>CGC Human Resource Management Information System<br>(HRMis)</strong></h2> -->
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg" style="font-size: 12px;">An application developed to monitor Employee Records.</p>
      <form id="formLogin" action="includes/process_login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col">
            <button type="submit" class="btn btn-primary btn-block" id="test-btn">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- <div class="social-auth-links text-center mb-3">
         <?php echo date("Y"); ?> 
        © CALAPAN CITY. All Rights Reserved. <br>
	  Developed by:  <a href="https://www.facebook.com/tyukusi" target="_blank" style="color: #000000ff; ">Patrick Cusi</a> &nbsp;&nbsp; 
      </div> -->
<div class="social-auth-links text-center mb-3" style="font-size: 0.9em; color: #000000ff;">
    <div style="font-size: 1em; font-weight: bold;">
        <?php echo date("Y"); ?> © CALAPAN CITY. All Rights Reserved.
    </div>
    <div style="margin-top: 2px; font-size: 0.75em;">
    Developed by: 
    <a href="https://www.facebook.com/tyukusi" target="_blank" style="color: #555;">Patrick D Cusi</a>
    </div>
    <div style="margin-top: 2px; font-size: 0.75em;">
        Project Manager: <a href="https://www.facebook.com/profile.php?id=61556774194695" target="_blank" style="color: #555;"> Marian Teresa Tagupa</a>
    </div>
</div>



    </div>
    <!-- /.login-card-body -->
  </div>
    <div class="login-logo logos-wrapper">
    <img class="logo-img mb-1" src="../dist/img/Calapan_City_Logo.png" alt="Calapan City Logo">
    <img class="logo-img mb-1" src="../dist/img/mbs.jpg" alt="MBS Logo">
    <img class="logo-img mb-1" src="../dist/img/chrmd1.png" alt="CHRMD Logo">
  </div>


</div>
<!-- /.login-box -->
<div id="modalContainer"></div>


<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 (official CDN, latest stable) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $('#formLogin').on('submit', function(e){
        e.preventDefault();

        const submitBtn = $(this).find('[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Logging in...');

        $.ajax({
            url: '../includes/process_login.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize()
        })
        .done(function(response) {
            console.log('Login response:', response);

            if (response.success) {

                // User has default password → load modal
                if (response.showModal) {
                    // Clear any previous modal
                    $('#modalContainer').empty();
                    
                    // Load the modal
                    $('#modalContainer').load('../partials/modals/modalpassword.php', function(){
                        // Show the modal immediately
                        var modal = new bootstrap.Modal(document.getElementById('modalPassword'));
                        modal.show();
                        
                        // Store modal instance globally so changepass.php can close it
                        window.passwordModal = modal;
                    });
                }

                // Normal login → redirect
                else if (response.redirect) {
                    Swal.fire({
                        icon: "success",
                        title: "Welcome!",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Login Failed",
                    text: response.message
                });
            }

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Login error:", textStatus, errorThrown);
            Swal.fire({
                title: "Error",
                text: "Failed to connect to server. Please try again.",
                icon: "error"
            });
        })
        .always(function() {
            submitBtn.prop('disabled', false).html(originalBtnText);
        });

    });

    // Global function to reset login page after password change
    window.resetLoginPage = function(username) {
        // Reset the form
        $('#formLogin')[0].reset();
        
        // If username was provided, pre-fill it
        if (username) {
            $('input[name="username"]').val(username);
        }
        
        // Focus on username field
        $('input[name="username"]').focus();
        
        // Show success message
        Swal.fire({
            icon: "success",
            title: "Password Changed!",
            text: "Your password has been updated successfully. Please login with your new password.",
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
    };

});
</script>
</body>
</html>
