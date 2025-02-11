<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "nav_bar.php";
include "db_connection/db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // Now that we know user is logged in, retrieve user_id

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $created_at = date('Y-m-d H:i:s');

    // Insert feedback into the user_feedback table with user_id
    $sql = "INSERT INTO users (full_name, email, message, created_at, user_id) 
            VALUES ('$name', '$email', '$message', '$created_at', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Your feedback has been sent successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Contact Us</h1>
    </div>
    <div class="content">
        <h2>Get in Touch</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
