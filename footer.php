<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>footer</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<style>
        footer {
            background-color: #000; /* Black background */
            color: #fff; /* White text */
            padding: 20px;
            text-align: center;
            border-top: 1px solid #444; /* Subtle border */
        }

        footer a {
            text-decoration: none;
            color: #fff; /* Default link color */
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #ff9800; /* Accent color on hover */
        }

        footer p {
            font-size: 14px;
            margin: 0;
        }

        footer div {
            margin-bottom: 15px;
        }

        footer div:last-child a {
            margin-right: 10px;
            font-size: 16px; /* Slightly larger for social media links */
        }
        

</style>
<body>
<!-- footer.php -->
    <footer style="background-color: #000; color: #fff; padding: 20px; text-align: center; border-top: 1px solid #444;">
        <div style="margin-bottom: 15px;">
            <p style="margin: 0; font-size: 14px;">&copy; <?php echo date("Y"); ?> IAA Election System. All Rights Reserved.</p>
        </div>
        <div style="margin-bottom: 15px; font-size: 14px;">
            <a href="contacts.php" style="text-decoration: none; color: #fff; margin-right: 15px;">Contact</a>
            <a href="location.php" style="text-decoration: none; color: #fff; margin-right: 15px;">Location</a>
            <a href="about.php" style="text-decoration: none; color: #fff;">About</a>
        </div>
        <div style="font-size: 16px;">
            <a href="https://web.facebook.com/Iaatz/?_rdc=1&_rdr" target="_blank" style="text-decoration: none; color: #4267B2; margin-right: 10px;"><i class="fab fa-facebook"></i></a>
                
            <a href="https://x.com/iaatanzania?lang=en&mx=2" target="_blank" style="text-decoration: none; color: #1DA1F2; margin-right: 10px;"><i class="fab fa-twitter"></i></a>
                
            <a href="https://www.instagram.com/iaa_tz/" target="_blank" style="text-decoration: none; color: #C13584; margin-right: 10px;">I<i class="fab fa-instagram"></i></a>
                
            <a href="https://www.linkedin.com/company/iaatz/" target="_blank" style="text-decoration: none; color: #0077B5;"><i class="fab fa-linkedin"></i></a>
                
        </div>
    </footer>

  
</body>
</html>
