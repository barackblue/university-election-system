<?php
$servername = "sql3.freesqldatabase.com";  // FreeSQL host
$username = "sql3762201";                  // FreeSQL database username
$password = "H6TJQsjMEF";                  // FreeSQL database password
$dbname = "sql3762201";                    // FreeSQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3306);  // Port 3306 for MySQL

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
echo "Connected successfully";
?>



