<?php
include "db_connection/db_connection.php"; // Include the database connection file

// Step 1: Check if id is provided in the URL (it’s the primary key)
if (!isset($_GET['id'])) {
    die("Invalid request - No ID provided");
}

$id = intval($_GET['id']); // Convert id to an integer to prevent SQL injection

// Step 2: Query the database to get the file path based on the id
$stmt = $conn->prepare("SELECT id, file_path FROM election_submissions WHERE id = ?");
$stmt->bind_param("i", $id); // Bind the id to the query
$stmt->execute();
$result = $stmt->get_result();

// Step 3: Debugging - Check if the result is as expected
if ($result->num_rows > 0) {
    $file = $result->fetch_assoc();
    echo "Found file with ID: " . $file['id']; // Debugging line
} else {
    die("No records found for the provided ID.");
}

// Step 4: Check if file_path exists
if (empty($file['file_path'])) {
    die("No file path found for ID: " . $id);
}

// Rest of the code (file serving)
$file_path = $file['file_path'];
$full_path = __DIR__ . "/" . $file_path; // Absolute path

// Ensure file exists
if (!file_exists($full_path)) {
    die("File does not exist: " . $full_path);
}

// Serve the file as usual
$finfo = finfo_open(FILEINFO_MIME_TYPE); // Get MIME type
$mime_type = finfo_file($finfo, $full_path);
finfo_close($finfo);

// Set headers and output the file
header("Content-Type: " . $mime_type);
header("Content-Length: " . filesize($full_path));
readfile($full_path); // Serve the file content
exit;
?>
