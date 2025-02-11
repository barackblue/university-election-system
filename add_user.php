<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db_connection/db_connection.php"; // Include your database connection

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_nos = $_POST['registration_no'];
    $full_names = $_POST['full_name'];
    $roles = $_POST['role'];
    $course = $_POST['course'];
    $passwords = $_POST['password'];

    // Loop through each user data to insert into the database
    for ($i = 0; $i < count($registration_nos); $i++) {
        $reg_no = $conn->real_escape_string($registration_nos[$i]);
        $full_name = $conn->real_escape_string($full_names[$i]);
        $role = $conn->real_escape_string($roles[$i]);
        $course = $conn->real_escape_string($course[$i]);
        $password = password_hash($passwords[$i], PASSWORD_BCRYPT); // Hash the password

        // Check if registration number already exists
        $sql = "SELECT * FROM users WHERE registration_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $reg_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Error: Registration number $reg_no already exists.<br>";
            continue; // Skip adding this user if they already exist
        }

        // Insert new user into the database
        $sql = "INSERT INTO users (registration_no, full_name, role, course, password_hash) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $reg_no, $full_name, $role, $course, $password);

        if ($stmt->execute()) {
            echo "User $full_name added successfully!<br>";
        } else {
            echo "Error adding user $full_name: " . $stmt->error . "<br>";
        }
    }
}

//Use $conn->real_escape_string() to sanitize inputs to prevent SQL injection
//Password Hashing:

//Passwords are hashed using password_hash before storing them in the database. This ensures security.
?>

<!--
    samson
    Mbuni1
    Tanga10
    Kivuyo10
    Mromba101
    Francis101
    for admin --mabibo101
-->
