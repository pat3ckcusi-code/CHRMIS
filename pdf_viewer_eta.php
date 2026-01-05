<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PDF Viewer</title>
<style>
html, body { margin:0; height:100%; }
#pdf-container { width:100%; height:100%; overflow:auto; text-align:center; }
canvas { display:block; margin: 1px auto; } /* ~0.5mm top/bottom margin */
@media print {
    body { margin: 0; }
    canvas { page-break-after: avoid; margin: 0; width: 100%; height: auto; }
}
</style>
</head>
<body>
<div id="pdf-container">
  <canvas id="pdf-canvas"></canvas>
</div>

<script type="module">
import { getDocument, GlobalWorkerOptions } from './pdfjs/build/pdf.mjs';

// PDF.js worker
GlobalWorkerOptions.workerSrc = './pdfjs/build/pdf.worker.mjs';

const url = 'api/ETA_pdf.php?id=<?php echo intval($_GET['id'] ?? 0); ?>';
const loadingTask = getDocument({ url, withCredentials: false });

const canvas = document.getElementById('pdf-canvas');
const ctx = canvas.getContext('2d');

// Letter size: 215.9mm × 279.4mm
const mmToPx = mm => mm * 3.78; 
const pageWidth  = mmToPx(215.9);
const pageHeight = mmToPx(279.4);
const margin     = mmToPx(0.5); // 0.5mm

loadingTask.promise.then(async pdf => {
    const page = await pdf.getPage(1);

    // Scale PDF page to fit inside Letter with very small margins
    const viewport = page.getViewport({ scale: 1 });
    const scaleX = (pageWidth - 2 * margin) / viewport.width;
    const scaleY = (pageHeight - 2 * margin) / viewport.height;
    const scale = Math.min(scaleX, scaleY);

    const scaledViewport = page.getViewport({ scale });

    canvas.width  = scaledViewport.width;
    canvas.height = scaledViewport.height;

    await page.render({ canvasContext: ctx, viewport: scaledViewport }).promise;

    // Automatically open print dialog
    window.print();
}).catch(err => {
    console.error(err);
    alert("Failed to load PDF: " + err.message);
});
</script>
</body>
</html>
