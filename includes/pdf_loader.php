<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pdfUrl = "../includes/APPLICATION_FOR_LEAVE.php?id=" . $id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loading PDF...</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        #loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        iframe {
            display: none;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader">
        <div class="spinner"></div>
        <p>Loading Application, please wait...</p>
    </div>

    <!-- PDF iframe -->
    <iframe id="pdfFrame" src="<?= $pdfUrl ?>"></iframe>

    <script>
    const iframe = document.getElementById('pdfFrame');
    const loader = document.getElementById('loader');

    iframe.onload = function() {
        // Hide loader, show PDF
        loader.style.display = 'none';
        iframe.style.display = 'block';

        // Tell parent page to close SweetAlert
        if (window.opener && window.opener.Swal) {
            window.opener.Swal.close();
        }
    };
    </script>
</body>
</html>
