<?php
include('db_connection.php'); // Ensure the path matches your structure

if ($conn->ping()) {
    echo "Connection successful!";
} else {
    echo "Connection failed: " . $conn->connect_error;
}
$conn->close();
?>
