<?php
// This partial now loads data from the API endpoint via AJAX.
// Determine initial display month/year from GET or default to current.
$displayMonth = isset($_GET['month']) && (int)$_GET['month'] > 0 ? (int)$_GET['month'] : (int)date('n');
$displayYear  = isset($_GET['year']) && (int)$_GET['year'] > 0 ? (int)$_GET['year'] : (int)date('Y');

// API path (root-relative to CHRMIS). Adjust if your app is mounted elsewhere.
$apiUrl = '../api/get_employee_statistics.php';
?>

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Employee ETA / Locator Usage</h1>
            </div>
        </div>
    </div>
</section>
<style>
/* Table design tweaks */
.stats-table .text-truncate { max-width: 240px; }
.usage-link { text-decoration: none; }
.usage-link .badge { cursor: pointer; }
.modal .table th, .modal .table td { vertical-align: middle; }
</style>
<script>
// use a window-scoped name to avoid redeclaration when this partial is loaded multiple times
window._employeeStatisticsApiUrl = window._employeeStatisticsApiUrl || '<?= htmlspecialchars($apiUrl) ?>';

function formatBadge(count, type) {
    let cls = 'secondary';
    if (type === 'eta') cls = count > 0 ? 'info' : 'secondary';
    if (type === 'locator') cls = count > 0 ? 'warning' : 'secondary';
    if (type === 'total') cls = count >= 5 ? 'danger' : (count >= 3 ? 'warning' : 'success');
    return `<span class="badge badge-${cls}">${count}</span>`;
}

// Format date string as "Jan 11, 2026"
function formatDateStr(dateStr) {
    if (!dateStr) return '';
    try {
        // normalize common MySQL datetime (space) to ISO parseable
        let s = String(dateStr).trim();
        if (s.indexOf(' ') !== -1 && s.indexOf('T') === -1 && s.indexOf('-') !== -1) {
            s = s.replace(' ', 'T');
        }
        const d = new Date(s);
        if (isNaN(d)) return dateStr;
        return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
    } catch (e) {
        return dateStr;
    }
}

// Format time string (HH:MM:SS or datetime) to 12-hour time like "6:15 PM"
function formatTimeStr(timeStr) {
    if (!timeStr) return '';
    try {
        let s = String(timeStr).trim();
        // If it's a pure time like HH:MM or HH:MM:SS, prefix a date so Date() can parse it
        if (/^\d{1,2}:\d{2}(:\d{2})?$/.test(s)) {
            s = '1970-01-01T' + s;
        } else if (s.indexOf(' ') !== -1 && s.indexOf('T') === -1) {
            s = s.replace(' ', 'T');
        }
        const d = new Date(s);
        if (isNaN(d)) return timeStr;
        return d.toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit', hour12: true });
    } catch (e) {
        return timeStr;
    }
}

function renderRows(rows) {
    const $body = $('#statsBody');
    $body.empty();
    if (!rows || rows.length === 0) {
        $body.append(`<tr><td colspan="6" class="text-muted text-center">No ETA / Locator usage records found.</td></tr>`);
        return;
    }

    rows.forEach(function(row){
        const empNo = $('<div>').text(row.EmpNo || '').html();
        const parts = [row.Lname || '', row.Fname || '', row.Mname || '', row.Extension || ''].filter(Boolean);
        const name = $('<div>').text(parts.join(', ')).html();
        const dept = $('<div>').text(row.Dept || '').html();
        const eta = parseInt(row.eta_count) || 0;
        const locator = parseInt(row.locator_count) || 0;
        const total = parseInt(row.total_usage) || 0;

        const currentMonth = window._stats_current_month || <?= (int)$displayMonth ?>;
        const currentYear  = window._stats_current_year  || <?= (int)$displayYear ?>;

        const etaBadge = `<a href="#" class="usage-link" data-emp="${empNo}" data-type="ETA" data-month="${currentMonth}" data-year="${currentYear}">${formatBadge(eta,'eta')}</a>`;
        const locatorBadge = `<a href="#" class="usage-link" data-emp="${empNo}" data-type="Locator" data-month="${currentMonth}" data-year="${currentYear}">${formatBadge(locator,'locator')}</a>`;

        const tr = `
            <tr>
                <td class="align-middle">${empNo}</td>
                <td class="text-truncate" style="max-width:240px;">${name}</td>
                <td class="align-middle">${dept}</td>
                <td class="text-center align-middle">${etaBadge}</td>
                <td class="text-center align-middle">${locatorBadge}</td>
                <td class="text-center align-middle">${formatBadge(total,'total')}</td>
            </tr>
        `;
        $body.append(tr);
    });
}

function fetchStats(month, year) {
    const $table = $('.card-body.table-responsive');
    const $tbody = $('#statsBody');
    $tbody.html(`<tr><td colspan="6" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>`);

    $.getJSON(window._employeeStatisticsApiUrl, { month: month, year: year })
        .done(function(resp){
            if (resp && resp.success) {
                // store current month/year for modal links
                window._stats_current_month = month;
                window._stats_current_year = year;
                renderRows(resp.data);
            } else {
                $tbody.html(`<tr><td colspan="6" class="text-danger text-center">Failed to load data.</td></tr>`);
            }
        })
        .fail(function(){
            $tbody.html(`<tr><td colspan="6" class="text-danger text-center">Failed to load data.</td></tr>`);
        });
}

$(document).on('click', '.month-nav', function(e){
    e.preventDefault();
    const month = $(this).data('month');
    const year  = $(this).data('year');
    // update label
    const $container = $(this).closest('.d-flex');
    const label = $container.find('.font-weight-bold');
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    label.text(monthName + ' ' + year);
    // update prev/next buttons to be relative to the new current month
    updateNavButtons($container, month, year);
    fetchStats(month, year);
});

$(document).on('click', '#monthToday', function(e){
    e.preventDefault();
    const month = $(this).data('month');
    const year  = $(this).data('year');
    const $container = $(this).closest('.d-flex');
    const label = $container.find('.font-weight-bold');
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    label.text(monthName + ' ' + year);
    updateNavButtons($container, month, year);
    fetchStats(month, year);
});

// initial load
$(function(){
    const $container = $('.d-flex.justify-content-between').first();
    // ensure nav buttons match initial display
    updateNavButtons($container, <?= (int)$displayMonth ?>, <?= (int)$displayYear ?>);
    fetchStats(<?= (int)$displayMonth ?>, <?= (int)$displayYear ?>);
});

// Update prev/next button data attributes for a container given current month/year
function updateNavButtons($container, month, year) {
    if (!$container || $container.length === 0) return;
    const prevBtn = $container.find('.month-nav').first();
    const nextBtn = $container.find('.month-nav').last();

    const cur = new Date(year, month - 1, 1);
    const prev = new Date(cur); prev.setMonth(cur.getMonth() - 1);
    const next = new Date(cur); next.setMonth(cur.getMonth() + 1);

    const pMonth = prev.getMonth() + 1, pYear = prev.getFullYear();
    const nMonth = next.getMonth() + 1, nYear = next.getFullYear();

    prevBtn.data('month', pMonth).data('year', pYear).attr('data-month', pMonth).attr('data-year', pYear);
    nextBtn.data('month', nMonth).data('year', nYear).attr('data-month', nMonth).attr('data-year', nYear);
}
</script>

<!-- Modals for ETA and Locator details -->
<!-- ETA Usage Modal -->
<div class="modal fade" id="etaUsageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">ETA Usage Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Travel Date</th>
                                <th>Business Type</th>
                                <th>Destination</th>
                                <th>Travel Detail</th>
                            </tr>
                        </thead>
                        <tbody id="etaModalBody">
                            <tr><td colspan="4" class="text-center text-muted">No records.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Locator Usage Modal -->
<div class="modal fade" id="locatorUsageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Locator Usage Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Travel Date</th>
                                        <th>Intended Departure</th>
                                        <th>Intended Arrival</th>
                                        <th>Destination</th>
                                        <th>Business Type</th>
                                        <th>Travel Detail</th>
                                        <th>Arrival Time</th>
                                    </tr>
                                </thead>
                                <tbody id="locatorModalBody">
                                    <tr><td colspan="7" class="text-center text-muted">No records.</td></tr>
                                </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle clicks on usage badges (delegated)
$(document).on('click', '.usage-link', function(e){
        e.preventDefault();
        const empNo = $(this).data('emp');
        const type = $(this).data('type');
        // prefer data-month/year on element, fallback to global
        const month = $(this).data('month') || window._stats_current_month || <?= (int)$displayMonth ?>;
        const year  = $(this).data('year')  || window._stats_current_year  || <?= (int)$displayYear ?>;

        const url = '../api/get_employee_usage_details.php';
        $.getJSON(url, { empNo: empNo, type: type, month: month, year: year })
                .done(function(resp){
                        if (!resp || !resp.success) {
                                alert('Failed to load details');
                                return;
                        }

                        if (type === 'ETA') {
                            const $b = $('#etaModalBody');
                            $b.empty();
                            if (resp.data.length === 0) {
                                $b.append('<tr><td colspan="4" class="text-center text-muted">No records for selected month.</td></tr>');
                            } else {
                                resp.data.forEach(function(r){
                                    const travelDate = formatDateStr(r.travel_date);
                                    const business = $('<div>').text(r.business_type || '').html();
                                    const destination = $('<div>').text(r.destination || '').html();
                                    const detail = $('<div>').text(r.travel_detail || '').html();
                                    $b.append(`<tr>
                                        <td>${travelDate}</td>
                                        <td>${business}</td>
                                        <td>${destination}</td>
                                        <td>${detail}</td>
                                    </tr>`);
                                });
                            }
                            $('#etaUsageModal').modal('show');
                        } else {
                            const $b = $('#locatorModalBody');
                            $b.empty();
                            if (resp.data.length === 0) {
                                $b.append('<tr><td colspan="7" class="text-center text-muted">No records for selected month.</td></tr>');
                            } else {
                                resp.data.forEach(function(r){
                                    const travelDate = formatDateStr(r.travel_date);
                                    const intendedDeparture = formatTimeStr(r.intended_departure);
                                    const intendedArrival = formatTimeStr(r.intended_arrival);
                                    const destination = $('<div>').text(r.destination || '').html();
                                    const business = $('<div>').text(r.business_type || '').html();
                                    const detail = $('<div>').text(r.travel_detail || '').html();
                                    const arrivalTime = formatTimeStr(r.Arrival_Time) || formatTimeStr(r.arrival_date);
                                    $b.append(`<tr>
                                        <td>${travelDate}</td>
                                        <td>${intendedDeparture}</td>
                                        <td>${intendedArrival}</td>
                                        <td>${destination}</td>
                                        <td>${business}</td>
                                        <td>${detail}</td>
                                        <td>${arrivalTime}</td>
                                    </tr>`);
                                });
                            }
                            $('#locatorUsageModal').modal('show');
                        }
                })
                .fail(function(){
                        alert('Failed to load details');
                });
});
</script>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Usage Statistics
                </h3>
            </div>

            <div class="card-body table-responsive">
                <?php
                    // Previous / next month helper (use displayMonth/displayYear set at top)
                    $prev = (new DateTime())->setDate($displayYear, $displayMonth, 1)->modify('-1 month');
                    $next = (new DateTime())->setDate($displayYear, $displayMonth, 1)->modify('+1 month');
                ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button class="btn btn-sm btn-outline-primary month-nav" data-month="<?= $prev->format('n') ?>" data-year="<?= $prev->format('Y') ?>">&laquo; Prev</button>
                        <span class="mx-2 font-weight-bold"><?= htmlspecialchars(date('F', mktime(0,0,0,$displayMonth,1,$displayYear))) . ' ' . $displayYear ?></span>
                        <button class="btn btn-sm btn-outline-primary month-nav" data-month="<?= $next->format('n') ?>" data-year="<?= $next->format('Y') ?>">Next &raquo;</button>
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm btn-secondary" id="monthToday" data-month="<?= date('n') ?>" data-year="<?= date('Y') ?>">This Month</a>
                    </div>
                </div>
                <table class="table table-bordered table-hover table-striped table-sm stats-table text-nowrap align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:12%">Employee Number</th>
                            <th style="width:36%">Employee Name</th>
                            <th style="width:18%">Department</th>
                            <th class="text-center" style="width:10%">ETA Usage</th>
                            <th class="text-center" style="width:12%">Locator Usage</th>
                            <th class="text-center" style="width:12%">Total Usage</th>
                        </tr>
                    </thead>
                    <tbody id="statsBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-muted">
                Showing ETA and Locator usage based on approved applications
            </div>
        </div>

    </div>
</section>

