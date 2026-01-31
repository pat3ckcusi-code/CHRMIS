<?php
// Simple print view for a travel order (ID passed via GET id)
require_once __DIR__ . '/includes/db_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>Invalid travel order ID.</p>";
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT t.*, GROUP_CONCAT(te.emp_no) AS emp_nos FROM travel_orders t LEFT JOIN travel_order_employees te ON te.travel_order_id = t.id WHERE t.id = ? GROUP BY t.id LIMIT 1");
    $stmt->execute([$id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) { echo "<p>Travel order not found.</p>"; exit; }

    $empNos = [];
    if (!empty($r['emp_nos'])) $empNos = explode(',', $r['emp_nos']);
    $employees = [];
    if (!empty($empNos)) {
        $in = implode(',', array_fill(0, count($empNos), '?'));
        $es = $pdo->prepare("SELECT EmpNo, CONCAT(Lname, ', ', Fname) AS name, Position FROM i WHERE EmpNo IN ($in)");
        $es->execute($empNos);
        $employees = $es->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Exception $e) {
    echo "<p>Error loading travel order.</p>"; error_log('travel_order_print error: '.$e->getMessage()); exit;
}
?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Travel Order <?php echo htmlspecialchars($r['travel_order_num']); ?></title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 24px; }
    h1 { font-size: 18px; margin-bottom: 8px; }
    .meta { margin-bottom: 12px; }
    .section { margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { padding: 6px 8px; vertical-align: top; }
    .employees td { border: 1px solid #ddd; }
  </style>
</head>
<body>
  <h1>Travel Order: <?php echo htmlspecialchars($r['travel_order_num']); ?></h1>
  <div class="meta">
    <strong>Created At:</strong> <?php echo htmlspecialchars($r['created_at']); ?><br>
    <strong>Status:</strong> <?php echo htmlspecialchars($r['status']); ?>
  </div>

  <div class="section">
    <strong>Destination:</strong> <?php echo htmlspecialchars($r['destination']); ?><br>
    <strong>Departure:</strong> <?php echo htmlspecialchars($r['start_date']); ?><br>
    <strong>Return:</strong> <?php echo htmlspecialchars($r['end_date']); ?><br>
  </div>

  <div class="section">
    <strong>Purpose:</strong>
    <div><?php echo nl2br(htmlspecialchars($r['purpose'])); ?></div>
  </div>

  <div class="section">
    <strong>Remarks:</strong>
    <div><?php echo nl2br(htmlspecialchars($r['remarks'] ?? '')); ?></div>
  </div>

  <div class="section">
    <strong>Employees:</strong>
    <table class="employees">
      <thead><tr><th>#</th><th>Name</th><th>Position</th></tr></thead>
      <tbody>
        <?php foreach ($employees as $i => $e): ?>
          <tr>
            <td><?php echo $i+1; ?></td>
            <td><?php echo htmlspecialchars($e['name']); ?></td>
            <td><?php echo htmlspecialchars($e['Position'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script>
    // auto print and close after print
    window.onload = function(){ window.print(); };
    window.onafterprint = function(){ setTimeout(function(){ window.close(); }, 200); };
  </script>
</body>
</html>
