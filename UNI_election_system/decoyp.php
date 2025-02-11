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
        echo "";
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



//other profile easy



$user_id = $_SESSION['user_id']; // Fetch user_id from session
$role = $_SESSION['role'];
$full_name = $_SESSION['full_name'];
$registration_no = $_SESSION['registration_no'];


// Fetch user details (for displaying the current profile picture)
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);



// Fetch user details
$result = $conn->query("SELECT full_name, course, email, profile_picture FROM users WHERE user_id='$user_id'");
if ($result) {
    $user = $result->fetch_assoc();
    $full_name = $user['full_name'] ?? "";
    $course = $user['course'] ?? "";
} else {
    $user = null;
    $course = "";
    $full_name = "";
    echo "Error fetching user data: " . $conn->error;
}




 // Handle email update
 if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $new_email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } else {
        // Check if the email is already in use by another user
        $check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $check_email->bind_param("si", $new_email, $user_id);
        $check_email->execute();
        $result = $check_email->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use by another user.";
        } else {
            // Update the email if allowed
            $update_email = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
            $update_email->bind_param("si", $new_email, $user_id);

            if ($update_email->execute()) {
                echo "Email updated successfully!";
            } else {
                echo "Error updating email: " . $conn->error;
            }
        }
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'], $_POST['new_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Fetch the stored hash for the current password
    $sql = "SELECT password_hash FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $user_data = mysqli_fetch_assoc($result);

    // Verify the current password with the stored hash
    if (password_verify($current_password, $user_data['password_hash'])) {
        // Update the password hash
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET password_hash = '$new_password_hash' WHERE user_id = '$user_id'";

        if (mysqli_query($conn, $update_sql)) {
            echo "Password updated successfully!";
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        echo "Current password is incorrect.";
    }
}
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

/* Body and main container styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 20px;
}

.profile-container-d {
    width: 100%;
    max-width: 800px;
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Profile picture section */
.container-left {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.upload-btn {
    position: relative;
    cursor: pointer;
}

.upload-btn input[type="file"] {
    display: none; /* Hide file input */
}

#profile-photo {
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 2px solid #ddd;
    transition: border-color 0.3s ease;
}

#profile-photo:hover {
    border-color: #007BFF; /* Border color on hover */
}

/* Form input and button styles */
input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 15px;
    font-size: 16px;
}

input[type="email"]:focus, input[type="password"]:focus {
    border-color: #007BFF;
    outline: none;
}

button[type="submit"] {
    background-color: #007BFF;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

button[type="submit"]:focus {
    outline: none;
}

/* Label and description */
p {
    font-size: 14px;
    margin-bottom: 10px;
    color: #555;
}

/* Icon for password visibility toggle */
span {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
}

/* Style for input wrapper */
div[style*="position: relative"] {
    position: relative;
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

        <form method="POST">
                <p>Set recovery email in case you forget your password:</p>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" placeholder="example@gmail.com" required>
                <button type="submit">Update Email</button>
        </form>

         <!-- Change Password -->
        <form method="POST">
                <p>Change Password:</p>
                <div style="position: relative; display: inline-block;">
                    <input type="password" name="current_password" id="current_password" placeholder="Current Password" required>
                    <span onclick="togglePasswordVisibility('current_password', this)" style="cursor: pointer;">👁️</span>
                </div>
                <br><br>

                <div style="position: relative; display: inline-block;">
                    <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                    <span onclick="togglePasswordVisibility('new_password', this)" style="cursor: pointer;">👁️</span>
                </div>
                <br><br>

                <button type="submit">Update Password</button>
            </form>

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

        function togglePasswordVisibility(fieldId, iconElement) {
                    const passwordField = document.getElementById(fieldId);
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        iconElement.textContent = "🙈"; // Change to hide icon
                    } else {
                        passwordField.type = "password";
                        iconElement.textContent = "👁️"; // Change back to show icon
                    }
                }
    </script>
</body>
</html>
