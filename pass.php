<?php
include "db_connection/db_connection.php";

// Replace with your admin credentials
$admin_id = 2; // Replace with the admin's user_id
$new_password = "mfumo101"; // Replace with the desired admin password

// Hash the password using bcrypt
$hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

// Update the admin password in the database
$sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hashedPassword, $admin_id);

if ($stmt->execute()) {
    echo "Admin password updated successfully!";
} else {
    echo "Error updating admin password: " . $stmt->error;
}
?>
