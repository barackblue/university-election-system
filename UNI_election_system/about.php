<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "nav_bar.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - IAA Online Voting System</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        h1, h2 {
            margin: 0 0 20px;
        }

        section {
            padding: 50px 20px;
        }

        a {
            text-decoration: none;
            color: #ff9800;
        }

        a:hover {
            color: #fff;
        }

        .team-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .team-member {
            text-align: center;
            max-width: 200px;
        }

        .team-member img {
            width: 100%;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .team-member p {
            margin: 5px 0;
        }

        .mission-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .mission-box {
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .mission-box h2 {
            color: #ff9800;
            font-size: 24px;
        }

        .contact-section {
            padding: 20px;
            text-align: center;
        }
            .mission-box {
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth animation */
        }

        .mission-box:hover {
            transform: scale(1.05); /* Slightly increase size */
            box-shadow: 0 6px 15px rgba(255, 152, 0, 0.5); /* Highlight with a glow */
        }

    </style>
</head>
<body>
    <section style="text-align: center;">
        <h1>About Us</h1>
        <p style="font-size: 18px; max-width: 800px; margin: 0 auto; line-height: 1.6;">
            Welcome to <strong>IAA Online Voting System</strong>. Our platform is dedicated to providing a secure, transparent, and user-friendly system for managing elections. We aim to empower individuals with the tools needed to participate in a democratic process that is accessible to everyone.
        </p>
    </section>

    <!-- Mission Section -->
    <section>
        <div class="mission-container">
            <div class="mission-box">
                <h2>Mission 1</h2>
                <p>To provide a secure platform that ensures every vote is counted fairly and transparently.</p>
            </div>
            <div class="mission-box">
                <h2>Mission 2</h2>
                <p>To empower individuals by fostering trust and accessibility in the democratic process.</p>
            </div>
            <div class="mission-box">
                <h2>Mission 3</h2>
                <p>To innovate and lead in creating digital voting systems that simplify election management.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section>
        <h2 style="text-align: center;">Meet Our Team</h2>
        <div class="team-container">
            <div class="team-member">
                <img src="team_member_placeholder.jpg" alt="Team Member">
                <p><strong>Hezron Lameck</strong></p>
                <p style="font-size: 14px; color: #ccc;">System Administrator</p>
            </div>
            <div class="team-member">
                <img src="team_member_placeholder.jpg" alt="Team Member">
                <p><strong>Kelvin B Yusuph</strong></p>
                <p style="font-size: 14px; color: #ccc;">Lead Developer</p>
            </div>
            <div class="team-member">
                <img src="team_member_placeholder.jpg" alt="Team Member">
                <p><strong>Richard Chagonja</strong></p>
                <p style="font-size: 14px; color: #ccc;">UI/UX Designer</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <p>Want to know more? Contact us at <a href="mailto:contact@iaa-voting-system.com">contact@iaa-voting-system.com</a>.</p>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
