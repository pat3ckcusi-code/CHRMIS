
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

?>
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


    <!-- start of body -->
        <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">                            
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- <form action="../includes/functions/2ndinfo.php" method="post">                     -->
                        <form id = "savePage2" method="post"> 
                            <input type="hidden" name="Save" value="1">

                        <!-- paste dito -->
            <!--  wrapper -->
            <div id="wrapper">        
                <!--  page-wrapper -->
                <div id="page-wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Personal Data Sheet</h1>
            <!-- Table IV -->
            <div class="card">
                <div class="card-header text-white" style="background-color: #006666;">
                    <h3 class="card-title">IV. CIVIL SERVICE ELIGIBILITY</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTablesIV">
                            <thead>
                                <tr>
                                    <th style="width:35%; text-align:center;">Career Service / RA 1080 (Board/Bar) Under Special Laws / CES / CSEE / Barangay Eligibility / Driver's License</th>
                                    <th style="text-align:center;">Rating <br><small>(if applicable)</small></th>
                                    <th style="text-align:center;">Date of Examination / Conferment</th>
                                    <th style="text-align:center;">Place of Examination / Conferment</th>
                                    <th style="text-align:center;">License Number</th>
                                    <th style="text-align:center;">Date of Validity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = $pdo->query("SELECT * FROM iv WHERE EmpNo = '" . $_SESSION['EmpID'] . "'");
                                if($query->rowCount() == 0) {
                                ?>
                                <tr>
                                    <td><input name="Career1" class="form-control" type="text"/></td>
                                    <td><input name="Rating1" class="form-control" type="text"/></td>
                                    <td><input name="Date1" class="form-control" type="date"/></td>
                                    <td><input name="Place1" class="form-control" type="text"/></td>
                                    <td><input name="LiNum1" class="form-control" type="text"/></td>
                                    <td><input name="LiDate1" class="form-control" type="date"/></td>
                                </tr>
                                <?php 
                                } else {
                                    $i = 0;
                                    while($row = $query->fetch()) {
                                        $i++;
                                ?>
                                <tr>
                                    <td><input name="Career<?php echo $i;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Career']); ?>"/></td>
                                    <td><input name="Rating<?php echo $i;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Rating']); ?>"/></td>
                                    <td><input name="Date<?php echo $i;?>" class="form-control" type="date" value="<?php echo htmlspecialchars($row['Date']); ?>"/></td>
                                    <td><input name="Place<?php echo $i;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Place']); ?>"/></td>
                                    <td><input name="LiNum<?php echo $i;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['LiNum']); ?>"/></td>
                                    <td><input name="LiDate<?php echo $i;?>" class="form-control" type="date" value="<?php echo htmlspecialchars($row['LiDate']); ?>"/></td>
                                </tr>
                                <?php 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Hidden input to track number of rows -->
                        <input name="numtext" type="hidden" id="numtext" value="<?php echo ($query->rowCount() != 0) ? $query->rowCount() : '1'; ?>">

                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="button" id="addtext" name="addtext" 
                                    style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700;">
                                Add Civil Service Eligibility
                            </button>
                        </div>  
                    </div>                          
                </div>
            </div>
            <!--End Table IV. -->
        </div>
        </div>
           <div class="row">
                <div class="col-lg-12">
                    <!-- Table V -->
                    <div class="card">
                        <div class="card-header text-white" style="background-color: #006666; font-family: 'Century Gothic';">
                            <h3 class="card-title">V. WORK EXPERIENCE</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTablesV">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Inclusive Dates <br><small>(mm/dd/yyyy)</small></th>
                                            <th class="text-center">Position Title <br><small>(Write in full / Do not abbreviate)</small></th>
                                            <th class="text-center">Department / Agency / Office / Company <br><small>(Write in full / Do not abbreviate)</small></th>
                                            <th class="text-center" style="width:10%;">Monthly Salary</th>
                                            <th class="text-center" style="width:7%;">Salary/Job/Pay Grade <br><small>(if applicable) & Step / INCREMENT</small></th>
                                            <th class="text-center" style="width:10%;">Status of Appointment</th>
                                            <th class="text-center" style="width:5%;">Gov't Service <br><small>(Y/N)</small></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="width:7%;">From</th>
                                            <th class="text-center" style="width:7%;">To</th>
                                            <th colspan="6"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query1 = $pdo->query("SELECT * FROM v WHERE EmpNo = '" . $_SESSION['EmpID'] . "' ORDER BY IndateFrom DESC");
                                        if($query1->rowCount() == 0) {
                                        ?>
                                        <tr>
                                            <td><input name="IndateFrom1" class="form-control" type="date"/></td>
                                            <td><input name="IndateTo1" class="form-control" type="date"/></td>
                                            <td><input name="Position1" class="form-control" type="text"/></td>
                                            <td><input name="Dept1" class="form-control" type="text"/></td>
                                            <td><input name="Month1" class="form-control" type="text"/></td>
                                            <td><input name="Salary1" class="form-control" type="text"/></td>
                                            <td><input name="Status1" class="form-control" type="text"/></td>
                                            <td><input name="GovService1" class="form-control" type="text"/></td>
                                        </tr>
                                        <?php 
                                        } else {
                                            $q = 0;
                                            while($row = $query1->fetch()) {
                                                $q++;
                                        ?>
                                        <tr>
                                            <td><input name="IndateFrom<?php echo $q;?>" class="form-control" type="date" value="<?php echo htmlspecialchars($row['IndateFrom']); ?>"/></td>
                                            <td><input name="IndateTo<?php echo $q;?>" class="form-control" type="date" value="<?php echo htmlspecialchars($row['IndateTo']); ?>"/></td>
                                            <td><input name="Position<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Position']); ?>"/></td>
                                            <td><input name="Dept<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Dept']); ?>"/></td>
                                            <td><input name="Month<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Month']); ?>"/></td>
                                            <td><input name="Salary<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Salary']); ?>"/></td>
                                            <td><input name="Status<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['Status']); ?>"/></td>
                                            <td><input name="GovService<?php echo $q;?>" class="form-control" type="text" value="<?php echo htmlspecialchars($row['GovService']); ?>"/></td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Hidden input to track number of rows -->
                                <input name="numtext1" type="hidden" id="numtext1" 
                                    value="<?php echo ($query1->rowCount() != 0) ? $query1->rowCount() : '1'; ?>">

                                <div class="text-center mt-3">
                                    <button class="btn btn-primary" type="button" id="addtext1" name="addtext1" 
                                            style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700;">
                                        Add Work Experience
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table V -->

                    <!-- Save Button -->
                    <div class="text-center mt-4">
                        <button class="btn btn-success" type="submit" id="Save" name="Save" 
                                style="height:50px;width:300px;font-family: 'Century Gothic'; font-weight: 700;">
                            SAVE
                        </button>
                    </div>
                </div>
            </div>

		</div>
        <!-- end page-wrapper -->
    </div>
    <!-- end wrapper -->   
    
    <script type="text/javascript">
    	document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("addtext").onclick = function() {
        var numtext = parseInt(document.getElementById("numtext").value) + 1;       
        var table = document.getElementById("dataTablesIV");

        // always insert at the end
        var row = table.insertRow(-1);

        // create cells
        var Cell1 = row.insertCell(0);
        var Cell2 = row.insertCell(1);
        var Cell3 = row.insertCell(2);
        var Cell4 = row.insertCell(3);
        var Cell5 = row.insertCell(4);
        var Cell6 = row.insertCell(5);

        // inputs
        var input = document.createElement("input");
        input.type = "text";
        input.name = "Career" + numtext;
        input.className = "form-control";
        Cell1.appendChild(input);

        var input1 = document.createElement("input");
        input1.type = "text";
        input1.name = "Rating" + numtext;
        input1.className = "form-control";
        Cell2.appendChild(input1);

        var input2 = document.createElement("input");
        input2.type = "date";
        input2.name = "Date" + numtext;
        input2.className = "form-control";
        Cell3.appendChild(input2);

        var input3 = document.createElement("input");
        input3.type = "text";
        input3.name = "Place" + numtext;
        input3.className = "form-control";
        Cell4.appendChild(input3);

        var input4 = document.createElement("input");
        input4.type = "text";
        input4.name = "LiNum" + numtext;
        input4.className = "form-control";
        Cell5.appendChild(input4);

        var input5 = document.createElement("input");
        input5.type = "date";
        input5.name = "LiDate" + numtext;
        input5.className = "form-control";
        Cell6.appendChild(input5);

        // update counter
        document.getElementById("numtext").value = numtext;
    };

	
	document.getElementById("addtext1").onclick = function() 
    {
		var numtext1 = parseInt(document.getElementById("numtext1").value) + 1;
        var Cell1 = document.getElementById("VText1");
		var Cell2 = document.getElementById("VText2");
		var Cell3 = document.getElementById("VText3");
		var Cell4 = document.getElementById("VText4");
		var Cell5 = document.getElementById("VText5");
		var Cell6 = document.getElementById("VText6");
		var Cell7 = document.getElementById("VText7");
		var Cell8 = document.getElementById("VText8");
		
		var table = document.getElementById("dataTablesV");
		var row = table.insertRow(numtext1 + 1);

		var Cell1 = row.insertCell(0);
		var Cell2 = row.insertCell(1);
		var Cell3 = row.insertCell(2);
		var Cell4 = row.insertCell(3);
		var Cell5 = row.insertCell(4);
		var Cell6 = row.insertCell(5);
		var Cell7 = row.insertCell(6);
		var Cell8 = row.insertCell(7);
		
        var input1 = document.createElement("input");
        input1.type = "date";
		input1.name = "IndateFrom" + numtext1;
		input1.className = "form-control";
		Cell1.appendChild(input1);
		
		var input2 = document.createElement("input");
        input2.type = "date";
		input2.name = "IndateTo" + numtext1;
		input2.className = "form-control";
		Cell2.appendChild(input2);
		
		var input3 = document.createElement("input");
        input3.type = "text";
		input3.name = "Position" + numtext1;
		input3.className = "form-control";
		Cell3.appendChild(input3);
		
		var input4 = document.createElement("input");
        input4.type = "text";
		input4.name = "Dept" + numtext1;
		input4.className = "form-control";
		Cell4.appendChild(input4);
		
		var input5 = document.createElement("input");
        input5.type = "text";
		input5.name = "Month" + numtext1;
		input5.className = "form-control";
		Cell5.appendChild(input5);
		
		var input6 = document.createElement("input");
        input6.type = "text";
		input6.name = "Salary" + numtext1;
		input6.className = "form-control";
		Cell6.appendChild(input6);
		
		var input7 = document.createElement("input");
        input7.type = "text";
		input7.name = "Status" + numtext1;
		input7.className = "form-control";
		Cell7.appendChild(input7);
		
		var input8 = document.createElement("input");
        input8.type = "text";
		input8.name = "GovService" + numtext1;
		input8.className = "form-control";
		Cell8.appendChild(input8);

		document.getElementById("numtext1").value = numtext1;
    }
});
    </script>    
                </form>
            </div>
        </section>
    </div>
    <?php //include("modalpassword.php"); ?>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
$(document).ready(function () {
  $("#savePage2").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "../includes/functions/2ndinfo.php",
      type: "POST",
      data: $(this).serialize() + '&Save=1',
      data: $(this).serialize(),
      dataType: "text", 
      success: function (response) {
        try {
          let res = JSON.parse(response); 

          Swal.fire({
            title: res.title,
            text: res.message,
            icon: res.icon,
            confirmButtonText: "OK"
          }).then(() => {
            if (res.success) {
              location.reload();
            }
          });
        } catch (e) {
          console.error("Invalid JSON:", response);
          Swal.fire("Error", "Invalid server response", "error");
        }
      },
      error: function (xhr) {
        Swal.fire("Error", "Something went wrong. Try again.", "error");
      }
    });
  });
});
</script>

            