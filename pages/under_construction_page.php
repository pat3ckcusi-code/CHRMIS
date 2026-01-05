<?php
// under_construction.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Construction</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('dist/img/city_hall1.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .banner img {
            max-width: 80%;
            height: auto;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
        }

        .message {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 25px 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }

        .message h1 {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .message p {
            font-size: 1.1rem;
            color: #555;
        }

        @media (max-width: 768px) {
            .banner img {
                max-width: 95%;
            }

            .message h1 {
                font-size: 1.8rem;
            }

            .message p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="banner">
            <img src="../dist/img/chrmd_banner.jpg" alt="CHRM Banner">
        </div>
        <div class="message">
            <h1>Page Under Construction</h1>
            <p>We are working hard to bring you this page. Please check back soon!</p>
        </div>
    </div>
</body>
</html>
