<!-- /.navbar -->
 
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
       
    <?php 
      if($_SESSION['Access'] == 'Admin')
					{
					if($_SESSION['Dept'] == 'City Human Resource Management Department' || $_SESSION['Status'] == 'FOR APPROVAL')
					{
    ?>
            <!-- Sidebar Menu -->
              <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  
                  <!-- Add New Employee -->
                  <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#addUserModal">
                      <i class="nav-icon fa fa-edit"></i>
                      <p>Add New Employee</p>
                    </a>
                  </li>
                  <!-- List of Employee -->
                  <li class="nav-item">
                    <a href="EmpList.php" class="nav-link">
                      <i class="fa fa-address-card"></i>
                      <p>List of Employee</p>
                    </a>
                  </li>
                  <!-- Leave Administration (Collapsible) -->
                  <!-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fa fa-table"></i>
                      <p>
                        Leave Administration
                        <i class="right fa fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">

                      <li class="nav-item">
                        <a href="LeaveApp.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Leave Application</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a href="ForRecom.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>For Recommendation</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a href="ApprovedLeave.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Approved Leave</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a href="DisapprovedLeave.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Disapproved Leave</p>
                        </a>
                      </li>

                        <li class="nav-item">
                        <a href="EmpLeave.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Employee Leave Credits</p>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a href="#modalLogsheet" class="nav-link" data-toggle="modal">
                          <i class="fa fa-table nav-icon"></i>
                          <p>Leave Monitoring Logsheet</p>
                        </a>
                      </li>

                    </ul>
                  </li> -->
                  <!-- Settings -->
                  <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="fa fa-wrench"></i>
                      <p>Settings</p>
                        <i class="right fa fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">

                      <li class="nav-item">
                        <a href="Department.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Department</p>
                        </a>
                      </li>  
                       <li class="nav-item">
                        <a href="Recom.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Recommending Personnel</p>
                        </a>
                      </li>                                

                    </ul>
                  </li>                 
                </ul>
              </nav>
          <?php 
          }else if($_SESSION['Access'] == 'AO')
          {
            ?>
             <!-- Sidebar Menu -->
              <!-- <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  
                  <li class="nav-item">
                    <a href="LeaveApp.php" class="nav-link">
                      <i class="fa fa-circle nav-icon"></i>
                      <p>Leave Application</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="ApprovedLeave.php" class="nav-link">
                      <i class="fa fa-circle nav-icon"></i>
                      <p>Approved Leave</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="DisapprovedLeave.php" class="nav-link">
                      <i class="fa fa-circle nav-icon"></i>
                      <p>Disapproved Leave</p>
                    </a>
                  </li>
                </ul>
              </nav> -->
            <?php
          }
                }
                else if($_SESSION['Access'] == 'User')
					{
          ?>
          <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Leave Administration (Collapsible) -->
                  <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fa fa-table"></i>
                      <p>PERSONAL DATA SHEET
                        <i class="right fa fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="Page1.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Personal Information</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="Page2.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Eligibility / Experience</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="3andLast.php" class="nav-link">
                          <i class="fa fa-circle nav-icon"></i>
                          <p>Other Related info</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="../includes/PDS.php" class="nav-link">
                          <i class="fa fa-print nav-icon"></i>
                          <p>PRINT</p>
                        </a>
                      </li>
                    </ul>
                  </li>  
                  <!-- <li class="nav-item">
                    <a href="Leave.php" class="nav-link">
                      <i class="nav-file fa fa-edit"></i>
                      <p>File Leave</p>
                    </a>
                  </li>                 
                  <li class="nav-item">
                    <a href="FiledLeave.php" class="nav-link">
                      <i class="fa fa-folder"></i>
                      <p>Filed Leave</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="ServiceRecord.php" class="nav-link">
                      <i class="fa fa-archive"></i>
                      <p>Service Record</p>
                    </a>
                  </li>
                   <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#modalPassword">
                      <i class="nav-icon fa fa-key"></i>
                      <p>Change Password</p>
                    </a>
                  </li> -->
                </ul>
              </nav>
          <?php
					}
          //for encoding mula dito
          else if($_SESSION['Status'] == 'FOR Encoder')
					{
				?>
        <!-- Sidebar Menu -->
              <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  
                  <!-- Add New Employee -->
                  <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#addUserModal">
                      <i class="nav-icon fa fa-edit"></i>
                      <p>Add New Employee</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="UpdateDateHired.php" class="nav-link">
                      <i class="fa fa-address-card"></i>
                      <p>Update Employee Date Hired</p>
                    </a>
                  </li>   
                </ul>
              </nav>
          <?php 
          } //hanggang dito lang
            ?>
              
  </aside>


