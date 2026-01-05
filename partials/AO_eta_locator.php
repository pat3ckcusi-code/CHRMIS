<?php
require_once('../includes/db_config.php');
session_start();

// Get AO department from session
$aoDept = $_SESSION['Dept'] ?? null;
if (!$aoDept) {
    echo "<p class='text-danger text-center mt-3'>No active AO session found.</p>";
    exit;
}

// Current date and time for filtering past ETAs/Locators
$now = date('Y-m-d H:i:s');

try {
    $stmtETA = $pdo->prepare("
    SELECT
        l.id, 
        e.EmpNo,
        CONCAT(e.Lname, ', ', e.Fname, ' ', COALESCE(e.Mname,''), ' ', COALESCE(e.Extension,'')) AS EmployeeName,
        l.destination,
        l.business_type,
        l.travel_date,
        l.other_purpose,
        l.travel_detail,
        l.status,
        l.Arrival_Time
    FROM eta_locator AS l
    INNER JOIN i AS e ON e.EmpNo = l.EmpNo
    WHERE e.Dept = :dept
      AND l.application_type = 'ETA'
      AND l.status = 'Approved'
      AND l.travel_date >= CURDATE()
    ORDER BY l.travel_date ASC
");
$stmtETA->execute(['dept' => $aoDept]);
$etaRecords = $stmtETA->fetchAll(PDO::FETCH_ASSOC);


    // Locator: approved
    $stmtLOC = $pdo->prepare("
    SELECT
        l.id, 
        e.EmpNo,
        CONCAT(
            e.Lname, ', ', e.Fname, ' ',
            COALESCE(e.Mname,''), ' ',
            COALESCE(e.Extension,'')
        ) AS EmployeeName,
        l.destination,
        l.business_type,
        l.travel_detail,
        l.travel_date,
        l.arrival_date,
        l.intended_departure,
        l.intended_arrival,
        l.status,
        l.Arrival_Time
    FROM eta_locator AS l
    INNER JOIN i AS e ON e.EmpNo = l.EmpNo
    WHERE e.Dept = :dept
      AND l.application_type = 'Locator'
      AND l.status = 'Approved'
      AND l.Arrival_Time IS NULL
    ORDER BY l.travel_date ASC, l.intended_departure ASC
");
$stmtLOC->execute(['dept' => $aoDept]);
$locatorRecords = $stmtLOC->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p class='text-danger text-center'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<!-- On ETA Table -->
<h5><i class="fas fa-plane-departure text-secondary"></i> On ETA </h5>
<div class="table-responsive">
  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="bg-light">
      <tr>
        <th>Employee Name</th>
        <th>Destination</th>
        <th>Travel Date</th>
        <th>Purpose</th>
        <th>Travel Details</th>
        <th>Status</th>
        <!-- <th>Action</th> -->
      </tr>
    </thead>
    <tbody>
      <?php if(empty($etaRecords)): ?>
        <tr><td colspan="7" class="text-muted">No employee on ETA.</td></tr>
      <?php else: ?>
        <?php foreach($etaRecords as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['EmployeeName']) ?></td>
            <td><?= htmlspecialchars($row['destination']) ?></td>
            <td><?= date('M d, Y', strtotime($row['travel_date'])) ?></td>
            <td>
              <?php
                if (strtolower($row['business_type']) === 'general expense/other') {
                    // business_type is "General Expense/Other", show concatenated
                    echo htmlspecialchars($row['business_type']) . ' - ' . htmlspecialchars($row['other_purpose']);
                } else {
                    // Otherwise, show only business_type
                    echo htmlspecialchars($row['business_type']);
                }
                ?>
            </td>
            <td><?= htmlspecialchars($row['travel_detail']) ?></td>
            <td><span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span></td>
            <!-- <td>              
              <button class="btn btn-sm btn-primary">View</button>
            </td> -->
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- On Locator Table -->
<h5 class="mt-4"><i class="fas fa-map-pin text-warning"></i> On Locator </h5>
<div class="table-responsive">
  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="bg-light">
      <tr>
        <th>Employee Name</th>
        <th>Business Type</th>
        <th>Location</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Travel Details</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>

<?php if (empty($locatorRecords)): ?>
    <tr>
        <td colspan="7" class="text-center text-muted">
            No employee on Locator
        </td>
    </tr>
<?php else: ?>
    <?php foreach ($locatorRecords as $row): ?>
        <?php 
            $arrivalRecorded = (
                $row['Arrival_Time'] !== null &&
                $row['Arrival_Time'] !== "" &&
                $row['Arrival_Time'] !== "0000-00-00 00:00:00"
            );
        ?>
        <tr>
            <td><?= htmlspecialchars($row['EmployeeName']) ?></td>
            <td><?= htmlspecialchars($row['business_type']) ?></td>
            <td><?= htmlspecialchars($row['destination']) ?></td>
            <td><?= date('h:i A', strtotime($row['intended_departure'])) ?></td>
            <td><?= date('h:i A', strtotime($row['intended_arrival'])) ?></td>
            <td><?= htmlspecialchars($row['travel_detail']) ?></td>

            <td>
                <?php if (!$arrivalRecorded): ?>
                    <!-- Arrival Time IS NULL → Green Button -->
                    <button 
                        class="btn btn-success btn-sm confirmArrivalBtn"
                        data-id="<?= $row['id']; ?>"
                        data-empno="<?= $row['EmpNo']; ?>"
                    >
                        Confirm Arrival
                    </button>

                <?php else: ?>
                    <!-- Arrival Time NOT NULL → Gray Disabled Button -->
                    <button class="btn btn-secondary btn-sm" disabled>
                        Arrival Time Recorded
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

<?php endif; ?>

</tbody>

  </table>
</div>


<!-- Optional: Auto-refresh every 30 seconds -->
<script>
setInterval(function(){
    $("#etaLocatorContainer").load("AO_eta_locator.php #etaLocatorContainer > *");
}, 30000);
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on("click", ".confirmArrivalBtn", function() {
    let recordId = $(this).data("id");
    let empNo = $(this).data("empno");

    Swal.fire({
        title: "Enter Arrival Time",
        input: "time",
        inputLabel: "Please select the actual arrival time",
        inputAttributes: { required: true },
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        inputValidator: (value) => {
            if (!value) return "Arrival time is required!";
        }
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "../api/save_arrival_time.php",
                type: "POST",
                data: {
                    id: recordId,
                    empNo: empNo,
                    arrivalTime: result.value
                },
                success: function(response) {
                  try {
                      let res = JSON.parse(response);

                      if (res.status === "success") {

                          // Disable THIS button immediately
                          let btn = $(`button[data-id='${recordId}']`);
                          btn.removeClass("btn-success").addClass("btn-secondary");
                          btn.prop("disabled", true);
                          btn.text("Arrival Recorded");

                          Swal.fire({
                              icon: "success",
                              title: "Arrival Time Recorded!",
                              text: res.message
                          });

                      } else {
                          Swal.fire("Error", res.message, "error");
                      }
                  } catch(e) {
                      Swal.fire("Error", "Invalid server response.", "error");
                  }
              },
                error: function() {
                    Swal.fire("Error", "Something went wrong while saving.", "error");
                }
            });

        }
    });
});

</script>

