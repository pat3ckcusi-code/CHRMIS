<?php
// ================= STATIC DATA =================
$hrName = "HR Administrator";

$stats = [
    "pending" => 4,
    "today" => 7,
    "overrides" => 3,
    "lwop" => 2
];

$approvedLeaves = [
    ["name"=>"Juan Dela Cruz","type"=>"Vacation Leave","dates"=>"Mar 3–5"],
    ["name"=>"Ana Perez","type"=>"Sick Leave","dates"=>"Mar 6"]
];

$habitualEmployees = [
    ["name"=>"Ana Perez","pattern"=>"Frequent Mon/Fri","risk"=>"High"],
    ["name"=>"Mark Santos","pattern"=>"Repeated Sick Leave","risk"=>"Medium"]
];
// --- HR Dashboard placeholder data ---

$kpis = [
    'utilization_pct' => 65,        // percent used of entitlement
    'total_used' => 650,            // example: days used
    'total_entitled' => 1000,
    'lwop_pct' => 2.3,
    'lwop_count' => 12,
    'lwop_days_mtd' => 24,
    'lwop_days_ytd' => 210,
    'lwop_cost' => 12345, // optional payroll impact
    'risk_index' => 27,
    'approval_time_days' => 1.8
];

$alerts = [
    'policy_violations' => [
        ['employee'=>'Jose Ramos','violation'=>'Late filing','policy'=>'Filing Policy 4.2','freq'=>3,'status'=>'Open'],
        ['employee'=>'Ana Perez','violation'=>'No supporting doc','policy'=>'Doc Req 2.1','freq'=>1,'status'=>'Open']
    ],
    'habitual_flags' => [
        ['employee'=>'Mark Santos','pattern'=>'Mon/Fri','frequency'=>'5 in 6 months','risk'=>'High','recommendation'=>'Manager counselling'],
        ['employee'=>'Liza Cruz','pattern'=>'Pre-holiday','frequency'=>'3 in 3 months','risk'=>'Medium','recommendation'=>'Monitor']
    ],
    'leave_exhaustion' => [
        ['employee'=>'Marian Lopez','balance'=>0,'projected_depletion'=>'2026-02-10','type'=>'Sick','action'=>'Advisory'],
        ['employee'=>'Carlos Dela','balance'=>2,'projected_depletion'=>'2026-03-01','type'=>'Vacation','action'=>'Policy reminder']
    ]
];

$leaveTrend = [
    'labels'=>['Aug','Sep','Oct','Nov','Dec','Jan','Feb'],
    'filed'=>[140,160,120,170,160,180,190],
    'approved'=>[120,140,110,160,150,170,180],
    'rejected'=>[10,8,5,6,7,4,3]
];

$leaveDistribution = [
    'labels'=>['Vacation','Sick','Emergency','LWOP','Special'],
    'data'=>[55,30,6,4,5]
];

$deptHeatmap = [
    'headers'=>['2026-01-01','2026-01-02','2026-01-03','2026-01-04'],
    'rows'=>[
        ['dept'=>'Admin','vals'=>[0,1,0,2]],
        ['dept'=>'Faculty','vals'=>[3,2,1,4]],
        ['dept'=>'IT','vals'=>[0,0,1,0]]
    ]
];

$riskScores = [
    ['dept'=>'Admin','score'=>12],
    ['dept'=>'Faculty','score'=>28],
    ['dept'=>'IT','score'=>6]
];

// end PHP placeholders
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>HR Leave Management | AdminLTE</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ADMINLTE & BOOTSTRAP -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

<!-- NAVBAR -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link">👤 <?php echo $hrName; ?></span>
        </li>
    </ul>
</nav>

<!-- SIDEBAR -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">HR Leave Portal</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item"><a href="#" class="nav-link active"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-lock"></i><p>Control Center</p></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-eye"></i><p>Monitoring</p></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-chart-bar"></i><p>Analytics</p></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="nav-icon fas fa-clipboard-list"></i><p>Audit Logs</p></a></li>
            </ul>
        </nav>
    </div>
</aside>

<!-- CONTENT -->
<div class="content-wrapper">

<!-- DASHBOARD -->
<section class="content pt-3">
<div class="container-fluid">
<!-- EXECUTIVE KPIs -->
<div class="row">
    <div class="col-3">
        <div class="info-box">
            <span class="info-box-icon bg-dark"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Utilization</span>
                <span class="info-box-number"><?php echo $kpis['utilization_pct']; ?>%</span>
                <div class="progress mt-2" style="height:8px">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $kpis['utilization_pct']; ?>%" aria-valuenow="<?php echo $kpis['utilization_pct']; ?>" aria-valuemin="0" aria-valuemax="100" title="<?php echo $kpis['total_used'].' / '.$kpis['total_entitled']; ?>"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="info-box">
            <span class="info-box-icon bg-dark"><i class="fas fa-user-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">LWOP</span>
                <span class="info-box-number"><?php echo $kpis['lwop_pct']; ?>% (<?php echo $kpis['lwop_count']; ?> emp)</span>
                <small class="text-muted">MTD days: <?php echo $kpis['lwop_days_mtd']; ?> — YTD: <?php echo $kpis['lwop_days_ytd']; ?></small><br>
                <small class="text-muted">Payroll impact est.: ₱<?php echo number_format($kpis['lwop_cost']); ?></small>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="info-box">
            <span class="info-box-icon bg-dark"><i class="fas fa-shield-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Risk Index</span>
                <?php $ri = intval($kpis['risk_index']); $riclass = $ri>=70? 'badge-danger':($ri>=40? 'badge-warning':'badge-success'); ?>
                <span class="badge <?php echo $riclass; ?> p-2"><?php echo $ri; ?>/100</span>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="info-box">
            <span class="info-box-icon bg-dark"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Approval Time</span>
                <span class="info-box-number"><?php echo $kpis['approval_time_days']; ?> days</span>
                <small class="text-muted">Median time to decision</small>
            </div>
        </div>
    </div>
</div>

<!-- RISK & COMPLIANCE ALERTS -->
<div class="card">
    <div class="card-header bg-danger">
        <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Risk & Compliance Alerts</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <h6>Policy Violations</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped" id="violations-table">
                        <thead><tr><th>Employee</th><th>Violation</th><th>Policy</th><th>Frequency</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach($alerts['policy_violations'] as $pv): ?>
                            <tr><td><?php echo $pv['employee']; ?></td><td><?php echo $pv['violation']; ?></td><td><?php echo $pv['policy']; ?></td><td><?php echo $pv['freq']; ?></td><td><?php echo $pv['status']; ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h6 class="mt-3">Habitual Leave Flags</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped" id="habitual-table">
                        <thead><tr><th>Employee</th><th>Pattern</th><th>Frequency</th><th>Risk</th><th>Recommendation</th></tr></thead>
                        <tbody>
                        <?php foreach($alerts['habitual_flags'] as $hf): ?>
                            <tr><td><?php echo $hf['employee']; ?></td><td><?php echo $hf['pattern']; ?></td><td><?php echo $hf['frequency']; ?></td><td><?php echo $hf['risk']; ?></td><td><?php echo $hf['recommendation']; ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h6 class="mt-3">Leave Exhaustion</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped" id="exhaustion-table">
                        <thead><tr><th>Employee</th><th>Balance</th><th>Projected Depletion</th><th>Type</th><th>HR Action</th></tr></thead>
                        <tbody>
                        <?php foreach($alerts['leave_exhaustion'] as $le): ?>
                            <tr><td><?php echo $le['employee']; ?></td><td><?php echo $le['balance']; ?></td><td><?php echo $le['projected_depletion']; ?></td><td><?php echo $le['type']; ?></td><td><?php echo $le['action']; ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ORGANIZATION ANALYTICS -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Leave Trend (Monthly)</h3></div>
            <div class="card-body"><canvas id="trendChart" height="140"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Leave Distribution (Type)</h3></div>
            <div class="card-body"><canvas id="distChart" height="140"></canvas></div>
        </div>
    </div>
</div>

<!-- DEPARTMENT STABILITY -->
<div class="card">
    <div class="card-header"><h3 class="card-title">Department Stability</h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>Department</th>
                            <?php foreach($deptHeatmap['headers'] as $h): ?><th><?php echo $h; ?></th><?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($deptHeatmap['rows'] as $r): ?>
                            <tr><td><?php echo $r['dept']; ?></td>
                            <?php foreach($r['vals'] as $v): ?><td class="heatmap-cell"><?php echo $v; ?></td><?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <h6>Risk Score Table</h6>
                <table class="table table-sm">
                    <thead><tr><th>Department</th><th>Risk Score</th></tr></thead>
                    <tbody>
                    <?php foreach($riskScores as $rs): ?>
                        <tr><td><?php echo $rs['dept']; ?></td><td><?php echo $rs['score']; ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- HR DECISION PANEL -->
<div class="card">
    <div class="card-header bg-dark"><h3 class="card-title">HR Decision Panel</h3></div>
    <div class="card-body d-flex flex-wrap align-items-center">
        <div class="mr-3"><button class="btn btn-outline-primary">Overrides</button></div>
        <div class="mr-3"><button class="btn btn-outline-secondary">Bulk Actions</button></div>
        <div class="mr-3"><button class="btn btn-outline-danger">Freeze Dates</button></div>
        <div class="ml-auto text-muted"><small>Actions are placeholders — integrate with APIs.</small></div>
    </div>
</div>
<!-- Modals for HR Decision Panel -->
<!-- Overrides Modal -->
<div class="modal fade" id="modal-overrides" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Overrides / Exception</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form id="form-overrides">
                    <div class="form-group"><label>Action</label><select class="form-control" name="action"><option value="cancel">Cancel Approved Leave</option><option value="convert">Convert Leave Type</option><option value="adjust">Adjust Credits</option></select></div>
                    <div class="form-group"><label>Target (Leave ID or Employee)</label><input class="form-control" name="target"></div>
                    <div class="form-group"><label>Justification</label><textarea class="form-control" name="justification" required></textarea></div>
                    <div class="form-group"><label>Audit Note</label><input class="form-control" name="audit_note"></div>
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-primary" id="submit-overrides">Submit</button><button class="btn btn-secondary" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="modal-bulk" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Bulk Actions</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form id="form-bulk">
                    <div class="form-group"><label>Action</label><select class="form-control" name="bulk_action"><option value="mass_approve">Mass Approve</option><option value="mass_reject">Mass Reject</option><option value="credit_correction">Bulk Credit Correction</option></select></div>
                    <div class="form-group"><label>Filter / Target</label><input class="form-control" name="filter"></div>
                    <div class="form-group"><label>Justification</label><textarea class="form-control" name="justification"></textarea></div>
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-primary" id="submit-bulk">Execute</button><button class="btn btn-secondary" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<!-- Freeze Dates Modal -->
<div class="modal fade" id="modal-freeze" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Freeze Dates (Set hiring/leave freeze)</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <form id="form-freeze">
                    <div class="form-group"><label>Start Date</label><input type="date" class="form-control" name="start"></div>
                    <div class="form-group"><label>End Date</label><input type="date" class="form-control" name="end"></div>
                    <div class="form-group"><label>Reason</label><input class="form-control" name="reason"></div>
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-danger" id="submit-freeze">Freeze</button><button class="btn btn-secondary" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

</div>
</section>
</div>

<!-- FOOTER -->
<footer class="main-footer text-center">
    <strong>HRIS Leave Management Portal</strong>
</footer>

</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Render charts using placeholder PHP data
document.addEventListener('DOMContentLoaded', function(){
    try{
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type:'line',
            data:{
                labels: <?php echo json_encode($leaveTrend['labels']); ?>,
                datasets:[
                    {label:'Filed',data:<?php echo json_encode($leaveTrend['filed']); ?>,borderColor:'#6c757d',backgroundColor:'rgba(108,117,125,0.06)',fill:true},
                    {label:'Approved',data:<?php echo json_encode($leaveTrend['approved']); ?>,borderColor:'#007bff',backgroundColor:'rgba(0,123,255,0.08)',fill:true},
                    {label:'Rejected',data:<?php echo json_encode($leaveTrend['rejected']); ?>,borderColor:'#dc3545',backgroundColor:'rgba(220,53,69,0.06)',fill:true}
                ]
            },
            options:{responsive:true, interaction:{mode:'index', intersect:false}, plugins:{tooltip:{callbacks:{label:function(ctx){return ctx.dataset.label+': '+ctx.formattedValue;}}}}
        });

        const distCtx = document.getElementById('distChart').getContext('2d');
        new Chart(distCtx, {type:'doughnut', data:{labels:<?php echo json_encode($leaveDistribution['labels']); ?>, datasets:[{data:<?php echo json_encode($leaveDistribution['data']); ?>, backgroundColor:['#007bff','#28a745','#ffc107','#dc3545','#6c757d']}]}, options:{responsive:true}});

        // Heatmap coloring: find max
        const heatCells = Array.from(document.querySelectorAll('.heatmap-cell'));
        const values = heatCells.map(c=>parseInt(c.textContent)||0);
        const max = Math.max.apply(null, values.concat([1]));
        heatCells.forEach(function(cell){
            const v = parseInt(cell.textContent)||0;
            const intensity = Math.round((v/max)*200); // 0-200
            cell.style.background = 'rgba(220,53,69,' + (v===0?0:Math.min(0.9, 0.2 + intensity/255)) + ')';
            cell.style.color = v>0? '#fff':'#000';
        });

    }catch(e){ console.error('Chart render error',e); }
});

// Decision panel placeholders
// Decision panel handlers: open modals and submit to placeholder API
document.getElementById('btn-overrides').addEventListener('click', function(){ $('#modal-overrides').modal('show'); });
document.getElementById('btn-bulk').addEventListener('click', function(){ $('#modal-bulk').modal('show'); });
document.getElementById('btn-freeze').addEventListener('click', function(){ $('#modal-freeze').modal('show'); });

function postHRAction(payload){
    return fetch('api/api_hr_actions.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)}).then(r=>r.json());
}

document.getElementById('submit-overrides').addEventListener('click', function(){
    const data = Object.fromEntries(new FormData(document.getElementById('form-overrides')).entries());
    if(!data.justification){ alert('Justification required'); return; }
    postHRAction({action:'override', data:data}).then(j=>{ alert(j.message||'Submitted'); $('#modal-overrides').modal('hide'); }).catch(e=>{ alert('Submit failed'); });
});

document.getElementById('submit-bulk').addEventListener('click', function(){
    const data = Object.fromEntries(new FormData(document.getElementById('form-bulk')).entries());
    if(!data.bulk_action){ alert('Select action'); return; }
    postHRAction({action:'bulk', data:data}).then(j=>{ alert(j.message||'Executed'); $('#modal-bulk').modal('hide'); }).catch(e=>{ alert('Execute failed'); });
});

document.getElementById('submit-freeze').addEventListener('click', function(){
    const data = Object.fromEntries(new FormData(document.getElementById('form-freeze')).entries());
    if(!data.start || !data.end){ alert('Start and end required'); return; }
    postHRAction({action:'freeze', data:data}).then(j=>{ alert(j.message||'Frozen'); $('#modal-freeze').modal('hide'); }).catch(e=>{ alert('Freeze failed'); });
});
</script>
</body>
</html>
