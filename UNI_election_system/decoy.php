<?php
session_start(); // Ensure session is started
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to update your profile picture.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Database connection parameters
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "sudosql";            // Your database password
$dbname = "election_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle profile picture upload
if (isset($_POST['uploadprofilebutton'])) {
    // Check if a file was uploaded
    if (!empty($_FILES['avatar']['name'])) {
        $filename = uniqid() . "-" . basename($_FILES['avatar']['name']); // Unique filename
        $tmpname = $_FILES['avatar']['tmp_name'];
        $folder = 'uploads/';

        // Move uploaded file
        if (move_uploaded_file($tmpname, $folder . $filename)) {
            // Update the user's profile picture in the DB
            $sql = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $filename, $user_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<br>Profile updated successfully!";
                } else {
                    echo "<br>Profile update failed! No changes made.";
                }

                $stmt->close();
            } else {
                echo "<br>Error with SQL: " . $conn->error;
            }
        } else {
            echo "<br>Error uploading file.";
        }
    } else {
        echo "<br>No file uploaded.";
    }
}

// Fetch user profile picture from DB
$sql = "SELECT profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Set profile picture path
$profile_picture_path = !empty($profile_picture) ? 'uploads/' . $profile_picture : 'uploads/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
</head>
<style>
    /* Reset some default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and container styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

.profile-container-d {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.container-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
}

.upload-btn {
    position: relative;
    cursor: pointer;
    margin-bottom: 15px;
}

.upload-btn input[type="file"] {
    display: none; /* Hide file input */
}

#profile-photo {
    border-radius: 50%;
    border: 2px solid #ddd;
    width: 150px;
    height: 150px;
    object-fit: cover;
    transition: border-color 0.3s ease;
}

#profile-photo:hover {
    border-color: #007BFF; /* Blue border on hover */
}

/* Upload button styles */
input[type="submit"] {
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

input[type="submit"]:focus {
    outline: none;
}

</style>
<body>
    <div class="profile-container-d">
        <div class="container-left">
            <!-- Profile Picture and Upload Form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <label class="upload-btn">
                    <!-- Preview the current profile picture or the default if none is set -->
                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(event)">
                    <img id="profile-photo" src="<?php echo htmlspecialchars($profile_picture_path); ?>" alt="Profile Photo" width="150">
                </label>
                <br>
                <input type="submit" value="Upload" name="uploadprofilebutton">
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile-photo');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
