
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
$qquery=$pdo->query("SELECT * FROM question WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
$roww = $qquery->fetch();
if (!$roww) {
    $roww = [
        '34a_choice'   => '',
        '34b_choice'   => '',
        '34b_details'  => '',
        '35a_choice'    => '',
        '35a_details'   => '',
        '35b_choice'    => '',
        '35b_details'   => '',
        '36a_choice'    => '',
        '36a_details'   => '',
        '37a_choice'    => '',
        '37a_details'   => '',
        '38a_choice'    => '',
        '38a_details'   => '',
        '38b_choice'    => '',
        '38b_details'   => '',
        '39a_choice'    => '',
        '39a_details'   => '',
        '40a_choice'    => '',
        '40a_details'   => '',
        '40b_choice'    => '',
        '40b_details'   => '',
        '40c_choice'    => '',
        '40c_details'   => '',
    ];
}

$refquery = $pdo->query("SELECT * FROM reference WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
$p = 0;
while($ref = $refquery->fetch())
{
	$p++;	
	$refName[$p] = $ref['Name'];
	$refAdd[$p] = $ref['Address'];
	$refTel[$p] = $ref['Tel'];
}

$queryGovID=$pdo->query("select * from govid where EmpNo = '" . $_SESSION["EmpID"] . "'");
$rowGovId = $queryGovID->fetch();

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
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- <form action="../includes/functions/lastinfo.php" method="post">                     -->
                        <form id = "3andLast" method="post">
							 <input type="hidden" name="Save" value="1">
                        <!-- paste dito -->
        <div id="page-wrapper">            
            <div class="row">
                 <!--  page header -->
                <div class="col-lg-12">
                    <h1 class="page-header">Personal Data Sheet</h1>
                </div>
                 <!-- end  page header -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Table VI -->
                    <div class="card shadow-sm">
                        <!-- Card Header -->
                        <div class="card-header text-white" style="background-color: #006666;">
                            <h6 class="mb-0">
                                VI. Voluntary Work or Involvement in Civic / Non-Government / People / Voluntary Organization/s
                            </h6>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTablesVI">
                                    <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle" style="font-size: small; width: 45%">
                                                <span style="font-family: 'Century Gothic'; font-weight: 600;">Name & Address of Organization</span><br>
                                                <small>(Write in full)</small>
                                            </th>
                                            <th colspan="2" class="text-center" style="font-size: small;">
                                                <span style="font-family: 'Century Gothic'; font-weight: 600;">Inclusive Dates</span><br>
                                                <small>(mm/dd/yyyy)</small>
                                            </th>
                                            <th rowspan="2" class="text-center align-middle" style="font-size: small; width: 10%;">
                                                <span style="font-family: 'Century Gothic'; font-weight: 600;">Number of Hours</span>
                                            </th>
                                            <th rowspan="2" class="text-center align-middle" style="font-size: small;">
                                                <span style="font-family: 'Century Gothic'; font-weight: 600;">Position / Nature of Work</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="width: 7%; font-size: small;">From</th>
                                            <th class="text-center" style="width: 7%; font-size: small;">To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = $pdo->query("SELECT * FROM vi WHERE EmpNo = '" . $_SESSION['EmpID'] . "'");
                                        if($query->rowCount() == 0) {
                                        ?>
                                            <tr>
                                                <td><input name="Name1" class="form-control" type="text" id="Name1" style="font-family: 'Century Gothic';"/></td>
                                                <td><input name="DatesFrom1" class="form-control" type="date" id="DatesFrom1"/></td>
                                                <td><input name="DatesTo1" class="form-control" type="date" id="DatesTo1"/></td>
                                                <td><input name="NumHours1" class="form-control" type="text" id="NumHours1"/></td>
                                                <td><input name="Position1" class="form-control" type="text" id="Position1"/></td>
                                            </tr>
                                        <?php 
                                        } else {
                                            $i = 0;
                                            while($row = $query->fetch()) {
                                                $i++;
                                        ?>
                                            <tr>
                                                <td><input name="<?php echo 'Name'.$i;?>" class="form-control" type="text" value="<?php echo $row['NameandAdd'];?>" style="font-family: 'Century Gothic';"/></td>
                                                <td><input name="<?php echo 'DatesFrom'.$i;?>" class="form-control" type="date" value="<?php echo $row['InclusiveFrom'];?>"/></td>
                                                <td><input name="<?php echo 'DatesTo'.$i;?>" class="form-control" type="date" value="<?php echo $row['InclusiveTo'];?>"/></td>
                                                <td><input name="<?php echo 'NumHours'.$i;?>" class="form-control" type="text" value="<?php echo $row['NumHours'];?>"/></td>
                                                <td><input name="<?php echo 'Position'.$i;?>" class="form-control" type="text" value="<?php echo $row['Position'];?>"/></td>
                                            </tr>
                                        <?php 
                                            }
                                        }
                                        ?>
                                        <input type="hidden" name="numtext" id="numtext" 
                                            value="<?php echo ($query->rowCount() != 0) ? $query->rowCount() : 1; ?>">
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary font-weight-bold" 
                                        id="addtext" name="addtext" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                    Add Voluntary Work or Involvement
                                </button>
                                <button type="submit" class="btn btn-success font-weight-bold" 
                                        id="Save" name="Save" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                    SAVE
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--End Table VI. -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                  <!--   Table VII. -->
                    <div class="card shadow-sm">
                        <!-- Card Header -->
                        <div class="card-header text-white" style="background-color: #006666;">
                            <h6 class="mb-0">
                                VII. LEARNING AND DEVELOPMENT (L&amp;D) Interventions/Training Programs Attended
                            </h6>
                            <small class="d-block">
                                (Start from the most recent L&amp;D/training program and include only the relevant L&amp;D/training taken 
                                for the last five (5) years for Division Chief/Executive/Managerial positions)
                            </small>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTablesVII">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th rowspan="2" style="width: 45%;">Title of Learning and Development Interventions/Training Programs<br>
                                                <small>(Write in full)</small>
                                            </th>
                                            <th colspan="2">Inclusive Dates of Attendance<br>
                                                <small>(mm/dd/yyyy)</small>
                                            </th>
                                            <th rowspan="2" style="width: 10%;">Number of Hours</th>
                                            <th rowspan="2" style="width: 10%;">Type of LD<br>
                                                <small>(Managerial / Supervisory / Technical / etc.)</small>
                                            </th>
                                            <th rowspan="2">Conducted / Sponsored by<br>
                                                <small>(Write in full)</small>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width: 7%;">From</th>
                                            <th style="width: 7%;">To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $year5 = date("Y") - 5;
                                            $query1 = $pdo->query("SELECT * FROM vii 
                                                                WHERE YEAR(InclusiveFrom) BETWEEN '". $year5 ."' AND '". date("Y") ."' 
                                                                AND EmpNo = '" . $_SESSION['EmpID'] . "'");

                                            if($query1->rowCount() == 0) {
                                        ?>
                                            <tr>
                                                <td><input name="Learning1" class="form-control" type="text" id="Learning1"></td>
                                                <td><input name="ADatesFrom1" class="form-control" type="date" id="ADatesFrom1"></td>
                                                <td><input name="ADatesTo1" class="form-control" type="date" id="ADatesTo1"></td>
                                                <td><input name="Hours1" class="form-control" type="text" id="Hours1"></td>
                                                <td><input name="Type1" class="form-control" type="text" id="Type1"></td>
                                                <td><input name="Conducted1" class="form-control" type="text" id="Conducted1"></td>
                                            </tr>
                                        <?php 
                                            } else {
                                                $j = 0;
                                                while($row = $query1->fetch()) { $j++;
                                        ?>
                                            <tr>
                                                <td><input name="Learning<?php echo $j;?>" class="form-control" type="text" 
                                                        value="<?php echo $row['Title'];?>"></td>
                                                <td><input name="ADatesFrom<?php echo $j;?>" class="form-control" type="date" 
                                                        value="<?php echo $row['InclusiveFrom'];?>"></td>
                                                <td><input name="ADatesTo<?php echo $j;?>" class="form-control" type="date" 
                                                        value="<?php echo $row['InclusiveTo'];?>"></td>
                                                <td><input name="Hours<?php echo $j;?>" class="form-control" type="text" 
                                                        value="<?php echo $row['NumHours'];?>"></td>
                                                <td><input name="Type<?php echo $j;?>" class="form-control" type="text" 
                                                        value="<?php echo $row['LDType'];?>"></td>
                                                <td><input name="Conducted<?php echo $j;?>" class="form-control" type="text" 
                                                        value="<?php echo $row['ConBy'];?>"></td>
                                            </tr>
                                        <?php 
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>

                                <!-- Hidden counter -->
                                <input type="hidden" name="numtext1" id="numtext1" 
                                    value="<?php echo ($query1->rowCount() != 0) ? $query1->rowCount() : '1'; ?>">
                            </div>

                            <!-- Action Buttons -->
                             <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary font-weight-bold" 
                                        id="addtext1" name="addtext1" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                    Add Learning and Development
                                </button>
                                <button type="submit" class="btn btn-success font-weight-bold" 
                                        id="Save" name="Save" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                    SAVE
                                </button>
                            </div>                            
                        </div>
                    </div>

                     <!-- End  Table VII. -->
                </div>
			</div>
			<div class="row">
                <div class="col-lg-12">
                  <!--   Table VIII. -->
                    <div class="panel panel-default">
                        <div class="card-header text-white" style="background-color: #006666;">
                            VIII. OTHER INFORMATION
						</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="card card-primary">                        
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTablesVIII">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th style="width: 33%">Special Skills and Hobbies</th>
                                        <th style="width: 33%">Non-Academic Distinctions/Recognition <br><small>(Write in full)</small></th>
                                        <th style="width: 33%">Membership in Association/Organization <br><small>(Write in full)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $query2 = $pdo->query("SELECT * FROM viii WHERE EmpNo = '" . $_SESSION['EmpID'] . "'");
                                if ($query2->rowCount() == 0) {
                                ?>
                                    <tr>
                                        <td><input name="Skills[]" class="form-control" type="text" /></td>
                                        <td><input name="Recognition[]" class="form-control" type="text" /></td>
                                        <td><input name="Membership[]" class="form-control" type="text" /></td>
                                    </tr>
                                <?php 
                                } else {
                                    while($row = $query2->fetch()) {
                                ?>
                                    <tr>
                                        <td><input name="Skills[]" class="form-control" type="text" value="<?php echo $row['Skills'];?>"/></td>
                                        <td><input name="Recognition[]" class="form-control" type="text" value="<?php echo $row['Recognition'];?>"/></td>
                                        <td><input name="Membership[]" class="form-control" type="text" value="<?php echo $row['Membership'];?>"/></td>
                                    </tr>
                                <?php 
                                    }
                                }
                                ?>
                                </tbody>
                            </table>

                            <!-- hidden counter -->
                            <input type="hidden" name="numtext2" id="numtext2" 
                                value="<?php echo ($query2->rowCount() != 0) ? $query2->rowCount() : '1'; ?>">

                            <!-- Buttons -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary font-weight-bold" 
                                        id="addtext2" name="addtext2" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                Add Other Information
                                </button>
                                <button type="submit" class="btn btn-success font-weight-bold" 
                                        id="Save" name="Save" 
                                        style="height:35px; width:300px; font-family: 'Century Gothic';">
                                    Save
                                </button>
                            </div>

                            <br />
								<table class="table table-striped table-bordered table-hover">

    <!-- 34 -->
    <tr>
        <td style="font-size: medium; width:70%">
            Are you related by consanguinity or affinity to the appointing or recommending authority,
            or to the chief of bureau or office or to the person who has immediate supervision over you
            in the Office, Bureau or Department where you will be appointed,<br/>
            a. within the third degree?<br/>
            b. within the fourth degree (for Local Government Unit - Career Employees)?
        </td>
        <td style="text-align: center; font-size: medium;">

            <!-- 34a -->
            <input id="rb34Y" type="radio" name="34a_choice" value="YES" <?php if($roww['34a_choice']=='YES') echo 'checked';?> required />
            <label for="rb34Y">YES</label>
            &nbsp;&nbsp;
            <input id="rb34N" type="radio" name="34a_choice" value="NO" <?php if($roww['34a_choice']=='NO') echo 'checked';?> />
            <label for="rb34N">NO</label>
            <br/><br/>

            <!-- 34b -->
            <input id="rb34bYes" type="radio" name="34b_choice" value="YES" <?php if($roww['34b_choice']=='YES') echo 'checked';?> required/>
            <label for="rb34bYes">YES</label>
            &nbsp;&nbsp;
            <input id="rb34bNo" type="radio" name="34b_choice" value="NO" <?php if($roww['34b_choice']=='NO') echo 'checked';?> />
            <label for="rb34bNo">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="34b_details" type="text" id="34b_details"
                   value="<?php echo $roww['34b_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 35a -->
    <tr>
        <td style="font-size: medium;">
            a. Have you ever been found guilty of any administrative offense?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb35Yes" type="radio" name="35a_choice" value="YES" <?php if($roww['35a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb35Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb35No" type="radio" name="35a_choice" value="NO" <?php if($roww['35a_choice']=='NO') echo 'checked';?> />
            <label for="rb35No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="35a_details" type="text" id="35a_details"
                   value="<?php echo $roww['35a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 35b -->
    <tr>
        <td style="font-size: medium;">
            b. Have you been criminally charged before any court?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb35bYes" type="radio" name="35b_choice" value="YES" <?php if($roww['35b_choice']=='YES') echo 'checked';?> required/>
            <label for="rb35bYes">YES</label>
            &nbsp;&nbsp;
            <input id="rb35bNo" type="radio" name="35b_choice" value="NO" <?php if($roww['35b_choice']=='NO') echo 'checked';?> />
            <label for="rb35bNo">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="35b_details" type="text" id="35b_details"
                   value="<?php echo $roww['35b_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 36 -->
    <tr>
        <td style="font-size: medium;">
            Have you ever been convicted of any crime or violation of any law, decree,
            ordinance or regulation by any court or tribunal?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb36Yes" type="radio" name="36a_choice" value="YES" <?php if($roww['36a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb36Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb36No" type="radio" name="36a_choice" value="NO" <?php if($roww['36a_choice']=='NO') echo 'checked';?> />
            <label for="rb36No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="36a_details" type="text" id="36a_details"
                   value="<?php echo $roww['36a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 37 -->
    <tr>
        <td style="font-size: medium;">
            Have you ever been separated from service in any of the following modes:
            resignation, retirement, dropped from the rolls, dismissal, termination,
            end of term, finished contract or phased out (abolition) in the public or private sector?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb37Yes" type="radio" name="37a_choice" value="YES" <?php if($roww['37a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb37Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb37No" type="radio" name="37a_choice" value="NO" <?php if($roww['37a_choice']=='NO') echo 'checked';?> />
            <label for="rb37No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="37a_details" type="text" id="37a_details"
                   value="<?php echo $roww['37a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 38a -->
    <tr>
        <td style="font-size: medium;">
            a. Have you ever been a candidate in a national or local election held within the last year
            (except Barangay election)?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb38Yes" type="radio" name="38a_choice" value="YES" <?php if($roww['38a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb38Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb38No" type="radio" name="38a_choice" value="NO" <?php if($roww['38a_choice']=='NO') echo 'checked';?> />
            <label for="rb38No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="38a_details" type="text" id="38a_details"
                   value="<?php echo $roww['38a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 38b -->
    <tr>
        <td style="font-size: medium;">
            b. Have you resigned from government service during the three (3)-month period before
            the last election to promote/actively campaign for a national or local candidate?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb38bYes" type="radio" name="38b_choice" value="YES" <?php if($roww['38b_choice']=='YES') echo 'checked';?> required/>
            <label for="rb38bYes">YES</label>
            &nbsp;&nbsp;
            <input id="rb38bNo" type="radio" name="38b_choice" value="NO" <?php if($roww['38b_choice']=='NO') echo 'checked';?> />
            <label for="rb38bNo">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="38b_details" type="text" id="38b_details"
                   value="<?php echo $roww['38b_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 39 -->
    <tr>
        <td style="font-size: medium;">
            Have you acquired the status of an immigrant or permanent resident of another country?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb39Yes" type="radio" name="39a_choice" value="YES" <?php if($roww['39a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb39Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb39No" type="radio" name="39a_choice" value="NO" <?php if($roww['39a_choice']=='NO') echo 'checked';?> />
            <label for="rb39No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="39a_details" type="text" id="39a_details"
                   value="<?php echo $roww['39a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 40a -->
    <tr>
        <td style="font-size: medium;">
            Pursuant to: (a) Indigenous People's Act (RA 8371);
            (b) Magna Carta for Disabled Persons (RA 7277);
            and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:<br/>
            a. Are you a member of any indigenous group?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb40Yes" type="radio" name="40a_choice" value="YES" <?php if($roww['40a_choice']=='YES') echo 'checked';?> required/>
            <label for="rb40Yes">YES</label>
            &nbsp;&nbsp;
            <input id="rb40No" type="radio" name="40a_choice" value="NO" <?php if($roww['40a_choice']=='NO') echo 'checked';?> />
            <label for="rb40No">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="40a_details" type="text" id="40a_details"
                   value="<?php echo $roww['40a_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 40b -->
    <tr>
        <td style="font-size: medium;">
            b. Are you a person with disability?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb40bYes" type="radio" name="40b_choice" value="YES" <?php if($roww['40b_choice']=='YES') echo 'checked';?> required/>
            <label for="rb40bYes">YES</label>
            &nbsp;&nbsp;
            <input id="rb40bNo" type="radio" name="40b_choice" value="NO" <?php if($roww['40b_choice']=='NO') echo 'checked';?> />
            <label for="rb40bNo">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="40b_details" type="text" id="40b_details"
                   value="<?php echo $roww['40b_details'] ?? '';?>" />
        </td>
    </tr>

    <!-- 40c -->
    <tr>
        <td style="font-size: medium;">
            c. Are you a solo parent?
        </td>
        <td style="text-align: center; font-size: medium;">
            <input id="rb40cYes" type="radio" name="40c_choice" value="YES" <?php if($roww['40c_choice']=='YES') echo 'checked';?> required/>
            <label for="rb40cYes">YES</label>
            &nbsp;&nbsp;
            <input id="rb40cNo" type="radio" name="40c_choice" value="NO" <?php if($roww['40c_choice']=='NO') echo 'checked';?> />
            <label for="rb40cNo">NO</label>
            <br/>
            if YES, give details:<br/>
            <input class="form-control" name="40c_details" type="text" id="40c_details"
                   value="<?php echo $roww['40c_details'] ?? '';?>" />
        </td>
    </tr>

</table>

                            <br>
                          <table class="table table-striped table-bordered table-hover">
								<tr>
									<td colspan="3" class="style2" style="text-align: center; font-size: medium;">
										REFERENCES (Person not related by consanguinity or affinity to 
										applicant/appointee)</td>
								</tr>
								<tr>
									<td class="style2" style="text-align: center; font-size: medium;">
										Name</td>
									<td class="style2" style="text-align: center; font-size: medium;">
										Address</td>
									<td class="style2" style="text-align: center; font-size: medium;">
										Tel No.</td>
								</tr>
								<tr id="RefText1">
									<td>
										<input class="form-control" name="txtName1" type="text" id="txtName1" style="width:100%;"  value="<?php if($p > 0) echo $refName[1]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtAddress1" type="text" id="txtAddress1" style="width:100%;"  value="<?php if($p > 0) echo $refAdd[1]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtTel1" type="text" id="txtTel1" style="width:100%;"  value="<?php if($p > 0) echo $refTel[1]; ?>"/>
									</td>
								</tr>
								<tr id="RefText2">
									<td>
										<input class="form-control" name="txtName2" type="text" id="txtName2" style="width:100%;"  value="<?php if($p > 1) echo $refName[2]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtAddress2" type="text" id="txtAddress2" style="width:100%;"  value="<?php if($p > 1) echo $refAdd[2]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtTel2" type="text" id="txtTel2" style="width:100%;"  value="<?php if($p > 1) echo $refTel[2]; ?>"/>
									</td>
								</tr>
								<tr id="RefText3">
									<td>
										<input class="form-control" name="txtName3" type="text" id="txtName3" style="width:100%;"  value="<?php if($p > 2) echo $refName[3]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtAddress3" type="text" id="txtAddress3" style="width:100%;"  value="<?php if($p > 2) echo $refAdd[3]; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtTel3" type="text" id="txtTel3" style="width:100%;" value="<?php if($p > 2) echo $refTel[3]; ?>"/>
									</td>
								</tr>
							</table> 
                            <table class="table table-striped table-bordered table-hover">
								<tr>
									<td colspan="4" class="style2" style="text-align: center; font-size: medium;">
										Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.)                               
                                        PLEASE INDICATE ID Number and Date of Issuance</td>
								</tr>
								<tr>
									<td class="style2" style="text-align: center; font-size: medium;">
										Government Issued ID: </td>
									<td class="style2" style="text-align: center; font-size: medium;">
										ID/License/Passport No.: </td>
									<td class="style2" style="text-align: center; font-size: medium;">
										Date of Issuance:</td>
                                        <td class="style2" style="text-align: center; font-size: medium;">
										Place of Issuance:</td>
								</tr>
                                <!-- GOv ID's -->
								<tr id="GovID">
									<td>
										<input class="form-control" name="txtGovID" type="text" id="txtGovID" style="width:100%;"  value="<?php echo (is_array($rowGovId) && isset($rowGovId['GovID'])) ? htmlspecialchars($rowGovId['GovID']) : ''; ?>"/>
									</td>
									<td>
										<input class="form-control" name="txtGovIDNo" type="text" id="txtGovIDNo" style="width:100%;"  value="<?php echo (is_array($rowGovId) && isset($rowGovId['GovIDNo'])) ? htmlspecialchars($rowGovId['GovIDNo']) : ''; ?>"/>
									</td>
									<td>
										<input class="form-control form-control-sm" type="date" name="txtIssuance1" id="txtIssuance1" value="<?php echo htmlspecialchars($rowGovId['Issuance']); ?>" required>
									</td>
                                    <td>
										<input class="form-control" name="txtIssuance2" type="text" id="txtIssuance2" style="width:100%;"  value="<?php echo (is_array($rowGovId) && isset($rowGovId['Place'])) ? htmlspecialchars($rowGovId['Place']) : ''; ?>"/>
									</td>
								</tr>								
							</table>   
                        </div>
                     <!-- End  Table VIII. -->
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTablesIV">
                            <tbody>
								<div align="center">
								<input class="btn btn-success" type="submit" value="SAVE" id="Save" name="Save" style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;" />
								</div>
							</tbody>
                        </table>
                    </div>
                </div>
			</div>
		</div>
        <!-- end page-wrapper -->
    </div>
    <!-- end wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="assets/plugins/jquery-1.10.2.js"></script>
    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/plugins/pace/pace.js"></script>
    <script src="assets/scripts/siminta.js"></script>
    <!-- Page-Level Plugin Scripts-->
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    
    <script type="text/javascript">
	function getansA(yesno)
	{
		name = yesno.slice(4);
		
		var ans = document.getElementById("txta" + name).value;
		if (!(ans == ""))
		{
			document.getElementById("rb" + name + "Yes").checked = true;
		}
		else
		{
			document.getElementById("rb" + name + "No").checked = true;
		}
	}
	function getansB(yesno)
	{
		name = yesno.slice(4);
		
		var ans = document.getElementById("txtb" + name).value;
		if (!(ans == ""))
		{
			document.getElementById("rb" + name + "bYes").checked = true;
		}
		else
		{
			document.getElementById("rb" + name + "bNo").checked = true;
		}
	}
	function getansC(yesno)
	{
		name = yesno.slice(4);
		
		var ans = document.getElementById("txtc" + name).value;
		if (!(ans == ""))
		{
			document.getElementById("rb" + name + "cYes").checked = true;
		}
		else
		{
			document.getElementById("rb" + name + "cNo").checked = true;
		}
		
	}
	
	function validateA(num)
	{
		id = num.slice(0,-1);
		letter = num.slice(-1);
		document.getElementById("txt" + letter + id).focus();
	}
	
	function validateB(num)
	{
		id = num.slice(0,-1);
		letter = num.slice(-1);
		document.getElementById("txt" + letter + id).value = "";
		document.getElementById("txt" + letter + id).blur();
	}
	
	function checkinput(txt, id, place)
	{
		get = id.slice(-3);
		num = get.slice(-2);
		letter = get.slice(0,-2);
		salert = false;
		if ((txt == '')&&(document.getElementByName(num + letter).value == "YES"))
		{
			if(salert == false)
			{
				document.getElementById(id).focus();
			}
			salert = true;
		}
		else
		{
			document.getElementById('error').InnerHTML = "";
		}
	}
	
    	document.getElementById("addtext").onclick = function() 
    {
		var numtext = parseInt(document.getElementById("numtext").value) + 1;
        /*var Cell1 = document.getElementById("VIText1");
		var Cell2 = document.getElementById("VIText2");
		var Cell3 = document.getElementById("VIText3");
		var Cell4 = document.getElementById("VIText4");
		var Cell5 = document.getElementById("VIText5");*/
		
		var table = document.getElementById("dataTablesVI");
		var row = table.insertRow(numtext + 1);

		var Cell1 = row.insertCell(0);
		var Cell2 = row.insertCell(1);
		var Cell3 = row.insertCell(2);
		var Cell4 = row.insertCell(3);
		var Cell5 = row.insertCell(4);
		
        var input = document.createElement("input");
        input.type = "text";
		input.name = "Name" + numtext;
		input.className = "form-control";
		Cell1.appendChild(input);
		
		var input1 = document.createElement("input");
        input1.type = "date";
		input1.name = "DatesFrom" + numtext;
		input1.className = "form-control";
		Cell2.appendChild(input1);
		
		var input2 = document.createElement("input");
        input2.type = "date";
		input2.name = "DatesTo" + numtext;
		input2.className = "form-control";
		Cell3.appendChild(input2);
		
		var input3 = document.createElement("input");
        input3.type = "text";
		input3.name = "NumHours" + numtext;
		input3.className = "form-control";
		Cell4.appendChild(input3);
		
		var input4 = document.createElement("input");
        input4.type = "text";
		input4.name = "Position" + numtext;
		input4.className = "form-control";
		Cell5.appendChild(input4);

		document.getElementById("numtext").value = numtext;
    }
	
	document.getElementById("addtext1").onclick = function() 
    {
		var numtext1 = parseInt(document.getElementById("numtext1").value) + 1;
        /*var Cell1 = document.getElementById("VIIText1");
		var Cell2 = document.getElementById("VIIText2");
		var Cell3 = document.getElementById("VIIText3");
		var Cell4 = document.getElementById("VIIText4");
		var Cell5 = document.getElementById("VIIText5");
		var Cell6 = document.getElementById("VIIText6");*/
		
		var table = document.getElementById("dataTablesVII");
		var row = table.insertRow(2);

		var Cell1 = row.insertCell(0);
		var Cell2 = row.insertCell(1);
		var Cell3 = row.insertCell(2);
		var Cell4 = row.insertCell(3);
		var Cell5 = row.insertCell(4);
		var Cell6 = row.insertCell(5);
		
        var input1 = document.createElement("input");
        input1.type = "text";
		input1.name = "Learning" + numtext1;
		input1.className = "form-control";
		Cell1.appendChild(input1);
		
		var input2 = document.createElement("input");
        input2.type = "date";
		input2.name = "ADatesFrom" + numtext1;
		input2.className = "form-control";
		Cell2.appendChild(input2);
		
		var input3 = document.createElement("input");
        input3.type = "date";
		input3.name = "ADatesTo" + numtext1;
		input3.className = "form-control";
		Cell3.appendChild(input3);
		
		var input4 = document.createElement("input");
        input4.type = "text";
		input4.name = "Hours" + numtext1;
		input4.className = "form-control";
		Cell4.appendChild(input4);
		
		var input5 = document.createElement("input");
        input5.type = "text";
		input5.name = "Type" + numtext1;
		input5.className = "form-control";
		Cell5.appendChild(input5);
		
		var input6 = document.createElement("input");
        input6.type = "text";
		input6.name = "Conducted" + numtext1;
		input6.className = "form-control";
		Cell6.appendChild(input6);

		document.getElementById("numtext1").value = numtext1;
    }
	
	document.getElementById("addtext2").onclick = function () {
    var numtext2 = parseInt(document.getElementById("numtext2").value) + 1;
    var table = document.getElementById("dataTablesVIII").getElementsByTagName('tbody')[0];
    var row = table.insertRow(-1); // append at bottom

    // Create 3 cells
    var Cell1 = row.insertCell(0);
    var Cell2 = row.insertCell(1);
    var Cell3 = row.insertCell(2);

    // Skills input
    var input1 = document.createElement("input");
    input1.type = "text";
    input1.name = "Skills[]"; 
    input1.className = "form-control";
    Cell1.appendChild(input1);

    // Recognition input
    var input2 = document.createElement("input");
    input2.type = "text";
    input2.name = "Recognition[]"; 
    input2.className = "form-control";
    Cell2.appendChild(input2);

    // Membership input
    var input3 = document.createElement("input");
    input3.type = "text";
    input3.name = "Membership[]"; 
    input3.className = "form-control";
    Cell3.appendChild(input3);

    // update hidden counter
    document.getElementById("numtext2").value = numtext2;
};

    </script>
                        <!-- hanggahgn dito -->
                        </form>
                    </div>
                </section>
            </div>
    <?php //include("modalpassword.php"); ?>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
include("../partials/modals/modalpassword.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
$(document).ready(function () {
  $("#3andLast").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "../includes/functions/lastinfo.php",
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

            