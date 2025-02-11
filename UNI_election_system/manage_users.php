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