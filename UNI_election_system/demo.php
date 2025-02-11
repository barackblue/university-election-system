<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
if (file_exists('db_connection/db_connection.php')) {
    require_once 'db_connection/db_connection.php';
    echo "Database file included successfully!<br>";
} else {
    die("Error: Database connection file not found!<br>");
}

// Check if the database connection variable is set
if (!isset($conn)) {
    die("Error: Database connection variable `\$conn` is not set!<br>");
}

// Check if there is a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!<br>";
}

// Handle form submission
if (isset($_POST['uploadfilebutton'])) {
    
    
    $filename = $_FILES['uploadFile'] ['name'];
    $tmpname = $_FILES['uploadFile']['tmp_name'];

    echo $filename;
    echo $tmpname;

    $folder = 'uploads/';

    move_uploaded_file($tmpname,$folder.$filename);

    $sql = "INSERT INTO images (imagePath) VALUES('$filename')";

    $query = mysqli_query($conn,$sql);

    if($query){
        echo "<br>Image Uploaded to Database";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Demo</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="uploadFile">
        <input type="submit" value="Upload" name="uploadfilebutton">
    </form>
</body>
</html>
