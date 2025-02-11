// serve_file.php
<?php
if (isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];

    // Database connection
    include "db_connection/db_connection.php";

    // Query to fetch the file data from the database
    $stmt = $conn->prepare("SELECT file_data, file_path FROM election_submissions WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Get the file extension
        $fileExt = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));

        // Set the correct content-type based on file extension
        if (in_array($fileExt, ['mp4', 'webm', 'ogg'])) {
            header("Content-Type: video/$fileExt");
        } elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
            header("Content-Type: image/$fileExt");
        } else {
            header("Content-Type: application/octet-stream");
        }

        // Output the file data
        echo $row['file_data'];
    } else {
        echo "File not found.";
    }
}
