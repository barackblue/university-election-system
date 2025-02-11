<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('da_connection/db_connection.php');

// Start the session
session_start();

// Debug: Print received POST data
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $position = isset($_POST['position']) ? $_POST['position'] : null;
    $election_submission_id = isset($_POST['election_submission_id']) ? intval($_POST['election_submission_id']) : null;
    $candidate_id = isset($_POST['candidate_id']) ? intval($_POST['candidate_id']) : null;

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("Error: You must be logged in to vote.");
    }
    $user_id = $_SESSION['user_id'];

    // Debug: Check collected data
    echo "<br>Position: " . htmlspecialchars($position);
    echo "<br>Election Submission ID: " . htmlspecialchars($election_submission_id);
    echo "<br>Candidate ID: " . htmlspecialchars($candidate_id);

    // Validate form inputs
    if (!$position || !$election_submission_id || !$candidate_id) {
        die("<br>Error: Missing required fields.");
    }

    echo "<br>All fields are set. Proceeding...";
}
?>
