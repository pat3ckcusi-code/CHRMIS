<?php
session_start();
require_once('../includes/initialize.php');

if(!isset($_SESSION['EmpID'])){
    echo json_encode(['status'=>'error','message'=>'Invalid employee number.']);
    exit;
}
?>
<!-- Page Header -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fas fa-file-alt"></i> Document Request</h1>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="content">
  <div class="container-fluid">

    <!-- Request Form Card -->
    <div class="card card-primary card-outline shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> Submit New Request</h3>
      </div>

      <div class="card-body">
        <form id="documentRequestForm">
<input type="hidden" id="EmpNo" value="<?php echo htmlspecialchars($_SESSION['EmpNo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-group">
            <label for="docType">Document Type <small class="text-muted">(Select one)</small></label>
            <select class="form-control select2" id="docType" style="width: 100%">
              <optgroup label="Certificate of Employment">
                <option value="COE_PERM">Permanent</option>
                <option value="COE_JOBORDER">Job Order</option>
                <option value="COE_MONTHLY">With Monthly Salary</option>
                <option value="COE_COMP">Employment & Compensation</option>
                <option value="COE_DESIG">With Designation & Job Description</option>
              </optgroup>
              <optgroup label="Other Certificates">
                <option value="LEAVE_CREDITS">Leave Credits</option>
                <option value="NO_PENDING_CASE">No Pending Case</option>
                <option value="NO_BANK_LOAN">No Existing Bank Loans</option>
                <option value="PAGIBIG">Pag-IBIG Contribution</option>
                <option value="SAME_PERSON">Same Person</option>
                <option value="VISA_TRAVEL">VISA / Travel Abroad</option>
              </optgroup>
            </select>
          </div>

          <div class="form-group">
            <label for="purpose">Purpose</label>
            <textarea class="form-control" id="purpose" rows="3" placeholder="State the purpose of your request..." maxlength="300"></textarea>
            <small id="charCount" class="form-text text-muted text-right">0 / 300</small>
          </div>

          <button type="button" class="btn btn-primary btn-block" id="submitRequest">
            <i class="fas fa-paper-plane"></i> Submit Request
          </button>

        </form>
      </div>
    </div>

    <!-- Previous Requests -->
<div class="card card-secondary card-outline shadow-sm mt-4">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-history"></i> Request History</h3>
  </div>

  <div class="card-body p-0">
    <table class="table table-hover table-striped mb-0">
      <thead class="bg-light">
        <tr>
          <th style="width: 120px">Date</th>
          <th>Document</th>
          <th style="width: 120px">Status</th>
          <th>Remarks</th>
          <th>HR Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Fetch employee requests dynamically
        $empID = $_SESSION['EmpID'];
        $stmt = $pdo->prepare("SELECT document_type, purpose, status, requested_on, hr_notes 
                               FROM document_requests 
                               WHERE EmpNo = :empID 
                               ORDER BY requested_on DESC");
        $stmt->execute([':empID' => $empID]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($requests as $r):
          $status = $r['status'];
          $badgeClass = '';
          switch($status){
              case 'Completed': $badgeClass = 'badge-success'; break;
              case 'Pending': $badgeClass = 'badge-warning'; break;
              case 'Rejected': $badgeClass = 'badge-danger'; break;
              default: $badgeClass = 'badge-secondary'; break;
          }
        ?>
        <tr>
          <td><?php echo date('M d, Y', strtotime($r['requested_on'])); ?></td>
          <td><?php echo htmlspecialchars($r['document_type']); ?></td>
          <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
          <td><?php echo htmlspecialchars($r['purpose']); ?></td>
          <td><?php echo htmlspecialchars($r['hr_notes']); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($requests)): ?>
        <tr>
          <td colspan="4" class="text-center text-muted">No previous requests found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


  </div>
</section>

<script>
  (function() {
    // Character counter for textarea (scoped)
    const purposeEl = document.getElementById('purpose');
    const charCount = document.getElementById('charCount');

    if (purposeEl && charCount) {
      purposeEl.addEventListener('input', () => {
        charCount.textContent = `${purposeEl.value.length} / 300`;
      });
    }
  })();
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  (function() {
    // Submit handler (scoped)
    const submitBtn = document.getElementById('submitRequest');
    if (!submitBtn) return;

    submitBtn.addEventListener('click', function() {
      const docTypeEl = document.getElementById('docType');
      const purposeEl = document.getElementById('purpose');
      const docType = docTypeEl ? docTypeEl.value : '';
      const purpose = purposeEl ? purposeEl.value.trim() : '';

      // Basic client-side validation
      if(!docType || !purpose) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Fields',
          text: 'Please select a document type and enter the purpose.',
        });
        return;
      }

      // Disable button while submitting
      this.disabled = true;

      // Send data via AJAX
      fetch('../includes/process_document_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          docType: docType,
          purpose: purpose,
        })
      })
      .then(response => response.json())
      .then(data => {
        if(data.status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Request Submitted',
            text: data.message,
          }).then(() => {
            // Reset form
            const form = document.getElementById('documentRequestForm');
            if (form) form.reset();
            const charCount = document.getElementById('charCount');
            if (charCount) charCount.textContent = '0 / 300';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
          });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Request Failed',
          text: 'Something went wrong. Please try again later.',
        });
      })
      .finally(() => {
        this.disabled = false;
      });
    });
  })();
</script>


