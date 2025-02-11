<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "nav_bar.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

// Fetch user data from session
$user_id = $_SESSION['user_id']; // Get user_id from session
$role = $_SESSION['role'];
$full_name = $_SESSION['full_name'];
$registration_no = $_SESSION['registration_no'];

// Include the database connection file
if (file_exists('db_connection/db_connection.php')) {
    require_once 'db_connection/db_connection.php';
} else {
    die("Error: Database connection file not found!<br>");
}

// Fetch user details from the database
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize and get form data
    $position = $_POST['position'];
    $description = $_POST['description'];
    $minister_type = isset($_POST['minister-type']) ? $_POST['minister-type'] : null;

    // Prepare and bind SQL query to insert the submission into the election_submissions table
    $stmt = $conn->prepare("INSERT INTO election_submissions (position, description, minister, course, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $position, $description, $minister_type, $course, $user_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Submission successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Submission</title>
    <script>
        function showMinisterDropdown(value) {
            var ministerDetails = document.getElementById('minister-details');
            if (value === 'minister') {
                ministerDetails.style.display = 'block';
            } else {
                ministerDetails.style.display = 'none';
            }
        }
    </script>
    <style>
        /* Global Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
    
    justify-content: center;
    align-items: center;
   
}

/* Container for the form */
.container-right {
    width: 80%;
    max-width: 700px;
    padding: 30px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    text-align: center;  /* Horizontally center the text */
    margin: 0 auto;
}

/* Form Title */
h2 {
    text-align: center;
    color: #333;
    font-size: 2em;
    margin-bottom: 20px;
}

/* Form Group (labels and inputs) */
.form-group {
    margin-bottom: 25px;
}

label {
    font-weight: 600;
    color: #555;
    font-size: 1.1em;
    margin-bottom: 8px;
    display: block;
}

input[type="text"], textarea, select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    font-size: 1em;
    box-sizing: border-box;
    transition: all 0.3s ease-in-out;
}

/* Focus Effect */
input[type="text"]:focus, textarea:focus, select:focus {
    border-color: #4CAF50;
    background-color: #fff;
    outline: none;
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
}

/* Button Style */
button {
    width: 100%;
    background-color: #4CAF50;
    color: #fff;
    font-size: 1.2em;
    padding: 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button:hover {
    background-color: #45a049;
}

button:active {
    background-color: #3e8e41;
}

/* Minister Dropdown */
#minister-details {
    display: none;
    padding: 10px;
    background-color: #f1f1f1;
    border-radius: 8px;
    box-sizing: border-box;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .container-right {
        width: 90%;
        padding: 20px;
    }

    h2 {
        font-size: 1.8em;
    }

    .form-group {
        margin-bottom: 20px;
    }

    button {
        font-size: 1em;
        padding: 12px;
    }
}
.centered-heading {
    text-align: center;  /* Horizontally center the text */
    margin: 0 auto;      /* Center the block element itself, if needed */
    font-weight: bold;
}

    </style>
</head>
<body>
    <br><br>
    <h3 class="centered-heading">Election Form</h3>
    <div class="container-right">
       
        <?php if($role === 'candidate'): ?>

            <div>
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly />
            </div>

            <div>
                <label for="course">Course</label>
                <input type="text" name="course" id="course" value="<?php echo htmlspecialchars($course); ?>" readonly />
            </div>

            <!-- Position Dropdown -->
            <form method="POST">
                <label for="position">Election Position</label>
                <select name="position" id="position" onchange="showMinisterDropdown(this.value)">
                    <option value="">Select Position</option>
                    <option value="president" <?php echo (isset($_POST['position']) && $_POST['position'] === 'president') ? 'selected' : ''; ?>>President</option>
                    <option value="minister" <?php echo (isset($_POST['position']) && $_POST['position'] === 'minister') ? 'selected' : ''; ?>>Minister</option>
                    <option value="class_rep" <?php echo (isset($_POST['position']) && $_POST['position'] === 'class_rep') ? 'selected' : ''; ?>>Class Representative</option>
                </select>

                <!-- Minister Details (Only display when 'minister' is selected) -->
                <div id="minister-details" style="display:none;">
                    <label for="minister-type">Minister Type</label>
                    <select name="minister-type" id="minister-type">
                        <option value="prime" <?php echo (isset($_POST['minister-type']) && $_POST['minister-type'] === 'prime') ? 'selected' : ''; ?>>Prime Minister</option>
                        <option value="health" <?php echo (isset($_POST['minister-type']) && $_POST['minister-type'] === 'health') ? 'selected' : ''; ?>>Minister of Health</option>
                        <option value="loans" <?php echo (isset($_POST['minister-type']) && $_POST['minister-type'] === 'loans') ? 'selected' : ''; ?>>Minister of Loans</option>
                        <option value="security" <?php echo (isset($_POST['minister-type']) && $_POST['minister-type'] === 'security') ? 'selected' : ''; ?>>Security Minister</option>
                        <option value="education" <?php echo (isset($_POST['minister-type']) && $_POST['minister-type'] === 'education') ? 'selected' : ''; ?>>Education</option>
                    </select>
                </div>

                <label for="description" id="description-section">Description:</label>
                <textarea name="description" id="description" placeholder="Fill your slogan/policy here" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <br><br>
                <button type="submit" name="submit">Submit</button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>
