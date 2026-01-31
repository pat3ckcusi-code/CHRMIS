<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../includes/db_config.php';

try {
    // Fetch travel orders that are Pending Approval
    $sql = "SELECT t.id, t.travel_order_num, t.purpose, t.remarks, t.destination, t.start_date, t.end_date, t.status, t.created_at, t.created_by, 
                   GROUP_CONCAT(DISTINCT CONCAT(i.Lname, ', ', i.Fname) SEPARATOR '||') AS employees_names_str,
                   COUNT(DISTINCT te.emp_no) AS employees_count
            FROM travel_orders t
            LEFT JOIN travel_order_employees te ON te.travel_order_id = t.id
            LEFT JOIN i ON i.EmpNo = te.emp_no
            WHERE t.status = 'Pending Approval'
            GROUP BY t.id
            ORDER BY t.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($rows as $r) {
        $names = [];
        if (!empty($r['employees_names_str'])) {
            $names = array_map('trim', explode('||', $r['employees_names_str']));
        }
        // try to fetch created_by name
        $created_by_name = '';
        if (!empty($r['created_by'])) {
            try {
                $c = $pdo->prepare("SELECT CONCAT(Lname, ', ', Fname) AS name FROM i WHERE EmpNo = ? LIMIT 1");
                $c->execute([$r['created_by']]);
                $cv = $c->fetch(PDO::FETCH_ASSOC);
                if ($cv) $created_by_name = $cv['name'];
            } catch (Throwable $e){}
        }

        $out[] = [
            'id' => (int)$r['id'],
            'travel_order_num' => $r['travel_order_num'],
            'purpose' => $r['purpose'],
            'destination' => $r['destination'],
            'start_date' => $r['start_date'],
            'end_date' => $r['end_date'],
            'status' => $r['status'],
            'created_at' => $r['created_at'],
            'created_by' => $r['created_by'],
            'created_by_name' => $created_by_name,
            'employees_count' => (int)$r['employees_count'],
            'employees_names' => $names
        ];
    }

    echo json_encode($out);
    exit;

} catch (Exception $e) {
    error_log('get_travel_orders_mayor error: ' . $e->getMessage());
    echo json_encode([]);
    exit;
}
