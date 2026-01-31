<?php
// api/travel_order_pdf.php
// Generate a PDF for a single (approved) travel order
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/FPDI_Protection.php';

// start session to get department / user info for header
if (session_status() === PHP_SESSION_NONE) session_start();

// Resolve department display name if available
$deptDisplay = $_SESSION['Dept'] ?? '';

// Resolve a department-based logo file (tolerant to spaces/case) and fallback
$logo_dir = __DIR__ . '/../dist/img/logo/';
$dept_rel_path = '../dist/img/logo/AdminLTELogo.png';
if (is_dir($logo_dir)) {
    $normDept = preg_replace('/[^a-z0-9]/', '', strtolower($deptDisplay));
    $files = scandir($logo_dir);
    foreach ($files as $f) {
        if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), ['png','jpg','jpeg','gif'])) {
            $base = pathinfo($f, PATHINFO_FILENAME);
            $normFile = preg_replace('/[^a-z0-9]/', '', strtolower($base));
            if ($normFile === $normDept) {
                $dept_rel_path = '../dist/img/logo/' . $f;
                break;
            }
        }
    }
}

// Filesystem paths for images (FPDF needs filesystem path)
$leftLogoFile = realpath(__DIR__ . '/' . $dept_rel_path);
$rightLogoFile = realpath('C:\\xampp\\htdocs\\CHRMIS\\dist\\img\\mbs.jpg');

// Fallbacks if realpath failed
if ($leftLogoFile === false) {
        $leftLogoFile = realpath(__DIR__ . '/../dist/img/logo/AdminLTELogo.png');
}
if ($rightLogoFile === false) {
        $rightLogoFile = null;
}

// Small PDF class with header
class TO_PDF extends FPDI_Protection {
        public $meta = [];
        function Header(){
                // images
                $pw = $this->GetPageWidth();
                $leftW = 28; $leftX = 10; $y = 8;
                if (!empty($this->meta['leftLogo']) && file_exists($this->meta['leftLogo'])) {
                        $this->Image($this->meta['leftLogo'], $leftX, $y, $leftW);
                }
                $rightW = 40; $rightX = $pw - 10 - $rightW;
                $rightH = 26; // reduced height
                if (!empty($this->meta['rightLogo']) && file_exists($this->meta['rightLogo'])) {
                    $this->Image($this->meta['rightLogo'], $rightX, $y, $rightW, $rightH);
                }

                // center text between logos
                $this->SetY(10);
                $this->SetFont('Arial','',10);
                $this->Cell(0,6, 'Republic of the Philippine', 0, 1, 'C');
                $this->Cell(0,6, 'Province of Oriental Mindoro', 0, 1, 'C');
                $this->SetFont('Arial','B',12);
                $this->Cell(0,7, 'CITY OF CALAPAN', 0, 1, 'C');
                $this->Cell(0,7, 'OFFICE OF THE CITY MAYOR', 0, 1, 'C');
                // add small separation
                $this->Ln(2);
        }
}

// Show errors during debugging (remove or disable in production)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo 'Invalid travel order id.';
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT t.*, GROUP_CONCAT(te.emp_no) AS emp_nos FROM travel_orders t LEFT JOIN travel_order_employees te ON te.travel_order_id = t.id WHERE t.id = ? GROUP BY t.id LIMIT 1");
    $stmt->execute([$id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) {
        http_response_code(404);
        echo 'Travel order not found.';
        exit;
    }

    // Load employees (detect if `Position` column exists; provide fallback)
    $employees = [];
    if (!empty($r['emp_nos'])) {
        $empNos = explode(',', $r['emp_nos']);
        $in = implode(',', array_fill(0, count($empNos), '?'));
        try {
            $colStmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'i' AND COLUMN_NAME = 'Position'");
            $colStmt->execute();
            $hasPosition = (bool)$colStmt->fetchColumn();
            if ($hasPosition) {
                $es = $pdo->prepare("SELECT EmpNo, CONCAT(Lname, ', ', Fname) AS name, `Position` AS Position FROM i WHERE EmpNo IN ($in)");
            } else {
                $es = $pdo->prepare("SELECT EmpNo, CONCAT(Lname, ', ', Fname) AS name, '' AS Position FROM i WHERE EmpNo IN ($in)");
            }
            $es->execute($empNos);
            $employees = $es->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ee) {
            error_log('travel_order_pdf employee lookup error: ' . $ee->getMessage());
            $employees = [];
        }
    }

} catch (Exception $e) {
    error_log('travel_order_pdf error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Server error';
    exit;
}

// Build PDF (wrap to catch runtime exceptions)
try {
    $pdf = new TO_PDF();
    // provide meta data and logo paths
    $pdf->meta = [
        'to_num' => $r['travel_order_num'] ?? '',
        'user' => $_SESSION['AcctName'] ?? '',
        'leftLogo' => $leftLogoFile,
        'rightLogo' => $rightLogoFile
    ];
    $pdf->SetTitle('Travel Order ' . ($r['travel_order_num'] ?? ''));
    $pdf->SetAuthor('CHRMIS');
    $pdf->AliasNbPages();
    // Set custom page size: 8.5 x 13 inches -> 215.9mm x 330.2mm
    $pdf->AddPage('P', array(215.9, 330.2));

    // Main title
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,8,'TRAVEL ORDER', 0, 1, 'C');
    $pdf->Ln(2);

    // Top meta row: Date (left) and Dept/Office (right)
    $pdf->SetFont('Arial','',10);
    $leftDate = !empty($r['start_date']) ? date('F j, Y', strtotime($r['created_at'] ?? 'now')) : ($r['created_at'] ?? '');
    $deptOffice = $deptDisplay ?: ($r['Dept'] ?? '');
    // Compute usable page width using current X as left margin (avoid accessing protected properties)
    $leftMargin = $pdf->GetX();
    $pageW = $pdf->GetPageWidth() - 2 * $leftMargin;
    $halfW = $pageW / 2;
    $pdf->Cell($halfW,6,'Date: ' . $leftDate, 0, 0, 'L');
    $pdf->Cell($halfW,6,'Dept./Office: ' . $deptOffice, 0, 1, 'R');
    $pdf->Ln(4);

    // Build a bordered table for key fields (Name, Departure, Return, Destination, Report to, Purpose)
    $pdf->SetFont('Arial','',11);
    $labelW = 45;
    $valueW = $pageW - $labelW;
    $lineH = 6;

    // helper to estimate multicell height
    $calcHeight = function($txt, $w) use ($pdf, $lineH) {
        $txt = trim($txt);
        if ($txt === '') return $lineH;
        $paragraphs = explode("\n", $txt);
        $lines = 0;
        foreach ($paragraphs as $para) {
            $words = preg_split('/\s+/', trim($para));
            $cur = '';
            foreach ($words as $word) {
                $test = $cur === '' ? $word : $cur . ' ' . $word;
                if ($pdf->GetStringWidth($test) <= $w) {
                    $cur = $test;
                } else {
                    $lines++;
                    $cur = $word;
                }
            }
            if ($cur !== '') $lines++;
        }
        return max(1, $lines) * $lineH;
    };

    // prepare values
    $namesList = [];
    foreach ($employees as $e) { $namesList[] = $e['name']; }
    $namesText = implode("\n", $namesList);
    $depText = !empty($r['start_date']) ? date('F j, Y', strtotime($r['start_date'])) : '';
    $retText = !empty($r['end_date']) ? date('F j, Y', strtotime($r['end_date'])) : '';
    $destText = $r['destination'] ?? '';
    $reportText = $r['report_to'] ?? '';
    $purposeText = $r['purpose'] ?? '';

    $rows = [
        ['label' => 'Name', 'value' => $namesText],
        ['label' => 'Date of Departure', 'value' => $depText],
        ['label' => 'Date of Return', 'value' => $retText],
        ['label' => 'Destination', 'value' => $destText],
        ['label' => 'Report to', 'value' => $reportText],
        ['label' => 'Purpose of Travel', 'value' => $purposeText],
    ];

    foreach ($rows as $row) {
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(245,245,245);
        // compute height for value
        $pdf->SetFont('Arial','',11);
        $h = $calcHeight($row['value'], $valueW - 2) ;
        // draw label cell (use background)
        $x = $pdf->GetX(); $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($labelW, $h, $row['label'], 1, 0, 'L', true);
        // draw value cell as rectangle then MultiCell inside
        $pdf->SetFont('Arial','',11);
        $pdf->SetXY($x + $labelW, $y);
        $pdf->MultiCell($valueW, $lineH, $row['value'], 1, 'L');
        // ensure position at next line start
    }

    $pdf->Ln(6);

    $pdf->Ln(8);

    // Signatures area: two columns
    $sigW = ($pageW) / 2;
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($sigW,6,'RECOMMENDING APPROVAL:',0,0,'L');
    $pdf->Cell($sigW,6,'APPROVED:',0,1,'C');
    $pdf->Ln(12);
    // signature lines
    $pdf->Cell($sigW,6,'_____________________________',0,0,'L');
    $pdf->Cell($sigW,6,'_____________________________',0,1,'C');

    // Determine recommender (Department Head of user's Dept) and approver (Mayor)
    $recName = $r['recommender_name'] ?? null;
    $recDesig = '';
    if (!empty($_SESSION['Dept'])) {
        try {
            $hstmt = $pdo->prepare("SELECT AcctName, designation FROM adminusers WHERE Dept = ? AND access_level = 'Department Head' LIMIT 1");
            $hstmt->execute([$_SESSION['Dept']]);
            $head = $hstmt->fetch(PDO::FETCH_ASSOC);
            if ($head) {
                $recName = $head['AcctName'] ?: $recName;
                $recDesig = $head['designation'] ?? $recDesig;
            }
        } catch (Exception $dex) {
            error_log('travel_order_pdf recommender lookup error: ' . $dex->getMessage());
        }
    }
    // Fallback to session user if recommender not found
    if (empty($recName)) {
        $recName = $_SESSION['AcctName'] ?? $_SESSION['EmpName'] ?? ($r['recommender_name'] ?? '________________________');
    }

    // Approver: Mayor
    $aprName = $r['approver_name'] ?? null;
    $aprDesig = '';
    try {
        $mstmt = $pdo->prepare("SELECT AcctName, designation FROM adminusers WHERE access_level = 'Mayor' LIMIT 1");
        $mstmt->execute();
        $mayor = $mstmt->fetch(PDO::FETCH_ASSOC);
        if ($mayor) {
            $aprName = $mayor['AcctName'] ?: $aprName;
            $aprDesig = $mayor['designation'] ?? $aprDesig;
        }
    } catch (Exception $mex) {
        error_log('travel_order_pdf mayor lookup error: ' . $mex->getMessage());
    }
    if (empty($aprName)) $aprName = $r['approver_name'] ?? '________________________';

    $pdf->SetFont('Arial','',10);
    $pdf->Cell($sigW,6,$recName,0,0,'L');
    $pdf->Cell($sigW,6,$aprName,0,1,'C');
    if ($recDesig || $aprDesig) {
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell($sigW,6,$recDesig,0,0,'L');
        $pdf->Cell($sigW,6,$aprDesig,0,1,'C');
    }
    $pdf->Ln(10);

    // Certificate of Appearance
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,6,'CERTIFICATE OF APPEARANCE',0,1,'C');
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(0,6, 'I hereby certify that the above-named personnel appeared at the ________ on ________ day of ________ ' . date('Y') . ' and for the purpose indicated in the Travel Order and the details of travel at the back.');

    $filename = 'travel_order_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', ($r['travel_order_num'] ?? '')) . '.pdf';
    $pdf->Output('I', $filename);
    exit;

} catch (Throwable $e) {
    // Log and display error for debugging
    error_log('travel_order_pdf runtime error: ' . $e->getMessage() . " in " . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo '<h2>Server error generating PDF</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine()) . '</pre>';
    exit;
}
