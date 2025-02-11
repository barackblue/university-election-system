<?php
include 'nav_bar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        .content {
            text-align: center;
            padding: 20px;
        }
        iframe {
            width: 80%;
            height: 400px;
            border: 0;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        p {
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Election System - Location</h1>
    </div>
    <div class="content">
        <h2>Find Us Here</h2>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.8354345097966!2d-122.42067948468538!3d37.77492957975971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c5f0ed3a5%3A0x3c285dc5cbb5ec2b!2sSan%20Francisco!5e0!3m2!1sen!2sus!4v1677089378826!5m2!1sen!2sus" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <p>Visit us at our campus to learn more about the election process and facilities available.</p>
    </div>
</body>
</html>
