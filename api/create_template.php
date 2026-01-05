<?php
require_once __DIR__ . '/../vendor/autoload.php';

// No namespace needed, just instantiate FPDF directly
$pdf = new \FPDF();  // <-- note the backslash
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Hello World',0,1);
$pdf->Output();


// Example fields
$pdf->SetXY(20,30);
$pdf->Write(6,'Employee Name: ___________________________');
$pdf->SetXY(20,50);
$pdf->Write(6,'Department: ______________________________');
$pdf->SetXY(20,70);
$pdf->Write(6,'Intended Departure: _______________________');
$pdf->SetXY(20,90);
$pdf->Write(6,'Intended Arrival: _________________________');

// Save template
$pdf->Output('F', __DIR__ . '/../templates/locator-form.pdf');

echo "Template created successfully at ../templates/locator-form.pdf";
