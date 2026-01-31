<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
    </div>
    <strong>&copy; <?php echo date("Y"); ?> </strong> CALAPAN CITY
  </footer>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>

<?php
include_once('core_javascript.php');
?>

<!-- Prevent SweetAlert from using violet as a default button color -->
<style>
  .swal2-styled.swal2-confirm {
    background-color: #3085d6 !important;
    border-color: #3085d6 !important;
    color: #fff !important;
  }
  .swal2-styled.swal2-cancel {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
    color: #fff !important;
  }
</style>

<script type="text/javascript">
	
    // logout action
    $('[data-action="user_logout"]').on('click', function(){
      Swal.fire({
        title: 'Ready to Leave?',
        text: "Select 'Sign out' below if you are ready to end your current session.",
        icon: 'info', // <--- FIXED
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sign out'
      }).then((result) => {
        if(result.isConfirmed) {  // <--- SweetAlert2 syntax
          window.location.href = `../partials/logout.php`;
        }
      })
    })
</script>

