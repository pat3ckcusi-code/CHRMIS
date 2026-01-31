<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Travel Order Viewer</title>
<style>
html, body { margin:0; height:100%; }
#pdf-container { width:100%; height:100%; overflow:auto; text-align:center; }
canvas { display:block; margin: 1px auto; }
@media print { body { margin: 0; } canvas { page-break-after: avoid; margin: 0; width: 100%; height: auto; } }
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

const id = parseInt(new URLSearchParams(window.location.search).get('id') || '0', 10);
const url = 'api/travel_order_pdf.php?id=' + encodeURIComponent(id);
const loadingTask = getDocument({ url, withCredentials: false });

const canvas = document.getElementById('pdf-canvas');
const ctx = canvas.getContext('2d');

// Letter size px approximation (used previously in site)
const mmToPx = mm => mm * 3.78;
const pageWidth  = mmToPx(215.9);
const pageHeight = mmToPx(279.4);
const margin     = mmToPx(0.5);

loadingTask.promise.then(async pdf => {
    const page = await pdf.getPage(1);
    const viewport = page.getViewport({ scale: 1 });
    const scaleX = (pageWidth - 2 * margin) / viewport.width;
    const scaleY = (pageHeight - 2 * margin) / viewport.height;
    const scale = Math.min(scaleX, scaleY);
    const scaledViewport = page.getViewport({ scale });

    canvas.width  = scaledViewport.width;
    canvas.height = scaledViewport.height;

    await page.render({ canvasContext: ctx, viewport: scaledViewport }).promise;

    // Viewing-only: do not auto-print or force download.
}).catch(err => {
    console.error(err);
    const msg = document.createElement('div');
    msg.style.padding = '20px';
    msg.style.color = '#900';
    msg.textContent = 'Failed to load PDF: ' + (err.message || err);
    document.getElementById('pdf-container').innerHTML = '';
    document.getElementById('pdf-container').appendChild(msg);
});
</script>
</body>
</html>
