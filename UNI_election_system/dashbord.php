<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include "nav_bar.php";

if (!isset($_SESSION['user_id'])) { header('location: login.php'); exit; }
$role = $_SESSION['role'] ?? '';
$full_name = $_SESSION['full_name'] ?? '';
$user_class = $_SESSION['class'] ?? ''; 


include "db_connection/db_connection.php";


$user_id = $_SESSION['user_id'] ?? '';
$course = '';

if ($user_id) {
    // Get the logged-in user's course
    $user_query = mysqli_query($conn, "SELECT course FROM users WHERE user_id = '$user_id'");
    $user_result = mysqli_fetch_assoc($user_query);
    $course = $user_result['course'] ?? '';
}

$presidents = mysqli_query($conn, "SELECT u.full_name, u.profile_picture, e.* FROM election_submissions e JOIN users u ON u.user_id = e.user_id WHERE e.position = 'president'");
$ministers = mysqli_query($conn, "SELECT u.full_name, u.profile_picture, e.* FROM election_submissions e JOIN users u ON u.user_id = e.user_id WHERE e.position = 'minister'");
// Fetch the class reps
$class_reps = mysqli_query($conn, "SELECT u.full_name, u.profile_picture, e.description, e.user_id 
    FROM election_submissions e 
    JOIN users u ON u.user_id = e.user_id 
    WHERE e.position = 'class_rep' AND e.course = '$course' AND u.course = '$course'");



$sql = "SELECT user_id, full_name, profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Bind the parameter for user_id

// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $value = $conn->real_escape_string($_POST['value']);

    $sql = "UPDATE users SET $column = '$value' WHERE user_id = $id";
    echo ($conn->query($sql)) ? "User updated successfully!" : "Error updating user: " . $conn->error;
}

// Delete user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE user_id = $id";
    echo ($conn->query($sql)) ? "User deleted successfully!" : "Error deleting user: " . $conn->error;
}

// Check if there is a vote message in session and assign it to $voteMessage
$voteMessage = isset($_SESSION['voteMessage']) ? $_SESSION['voteMessage'] : null;

// Clear the session message after displaying it
unset($_SESSION['voteMessage']);



//frank ---decoy
//erick ---1234
//mahela ---newton
//salome ---lenga
?>

<!DOCTYPE html>
<html>
<head>
    <title>Election Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
 /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* General Body Padding */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0 20px; /* Adding padding to the body to prevent content from touching the screen edges */
}

/* Table Containers */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    margin-right: auto;
    padding: 0 10px; /* Add some internal padding */
}

/* Other Container Padding */
.container {
    padding: 20px; /* Padding for other container elements */
    margin: 20px auto;
    width: 95%;
}

/* Table Padding */
th, td {
    padding: 12px 20px;
    border: 1px solid #ddd;
    text-align: left;
}

/* ... Other Styles Continue ... */

/* Header */
h1, h2, h3 {
    font-family: 'Arial', sans-serif;
    font-weight: normal;
    color: #333; /* Dark text */
}
/* Center the h3 element */
.centered-heading {
    text-align: center;  /* Horizontally center the text */
    margin: 0 auto;      /* Center the block element itself, if needed */
    font-weight: bold;
}


/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
}

.modal-content {
    background-color: #fff;
    color: #333;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 50%;
    text-align: center;
    position: relative;
    border: 2px solid #ddd;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

/* Buttons */
button {
    padding: 10px 20px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

button:focus {
    outline: none;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

button:active {
    background-color: #004085;
}

button.removeUser {
    background-color: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
}

button.removeUser:hover {
    background-color: #c82333;
}

/* Search Bar */
.search-container {
    margin: 20px 0;
    text-align: center;
}

#search {
    width: 50%;
    padding: 10px;
    font-size: 16px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    color: #333;
    border-radius: 5px;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px 20px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f1f1f1;
    font-weight: bold;
    color: #333;
}

td {
    color: #333;
}

td[contenteditable="true"]:hover {
    background-color: #f1f1f1;
}

/* Candidate Table */
.candidate-table {
    width: 70%;
    margin: 0 auto;
    border-collapse: collapse;
}

.candidate-table th, .candidate-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.candidate-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.candidate img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

/* Vote Buttons */
/* Center container for the Vote Button */
.vote-button-container {
    display: flex;
    justify-content: center;  /* Horizontally center the button */
    align-items: center;      /* Vertically center the button */
               /* Makes sure the container fills the screen */
}

/* Vote Button styles */
#voteButton {
    padding: 12px 24px;
    font-size: 16px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
    width: 70%;
    background-color: #28a745;
    color: white;
}

#voteButton:hover {
    background-color: #218838;
}

#voteButton:focus {
    outline: none;
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
}

#voteButton:active {
    background-color: #1e7e34;
}






/* Form and Input Styling */
input[type="text"], input[type="password"], select {
    background-color: #fff;
    color: #333;
    border: 1px solid #ddd;
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
}

input[type="text"]:focus, input[type="password"]:focus, select:focus {
    outline: none;
    border-color: #007bff;
}

/* User Input Fields */
.user-input {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 5px 0;
    background: #f1f1f1;
    padding: 10px;
    border-radius: 5px;
}

.user-input input, .user-input select {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
}

/* Table & Modal - Manage Users */
table {
    width: 100%;
    margin-top: 20px;
    background-color: #fff;
}

th, td {
    padding: 12px 20px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f1f1f1;
}

/* Content */
h2 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}

/* Modal for User Actions */
#userModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.5);
}

#userModal .modal-content {
    background: #fff;
    color: #333;
    padding: 20px;
    border-radius: 10px;
    width: 50%;
}

#userModal .close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}

#userModal input, #userModal select {
    margin: 5px 0;
    padding: 8px;
    border-radius: 5px;
    background-color: #fff;
    color: #333;
}

/* Remove user button in modal */
.removeUser {
    background-color: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 5px;
}

/* Add User Button Styles */
button[type="button"] {
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 5px;
}

button[type="button"]:hover {
    background-color: #218838;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .candidate-table, .search-container, table {
        width: 100%;
        padding: 10px;
    }

    #search {
        width: 90%;
    }
}


    </style>
</head>
<body>
    
    
    <?php if ($role == 'admin'): ?>
        <h2>Manage Users.</h2>
    <button onclick="openUserModal()" id="add-userbtn">➕ Add User</button>

    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUserModal()">&times;</span>
            <h2>Add Users</h2>

            <form action="add_user.php" method="post" id="userForm">
                <div id="userContainer">
                    <div class="user-input">
                        <input type="text" name="full_name[]" placeholder="Full Name" required>
                        <input type="text" name="registration_no[]" placeholder="Registration No" required>
                        <input type="text" name="course[]" placeholder="Course">
                        <input type="password" name="password[]" placeholder="Password" required>
                        <select name="role[]">
                            <option value="student">Student</option>
                            <option value="lecture">Lecture</option>
                            <option value="candidate">Candidate</option>
                        </select>
                        <button type="button" class="removeUser" onclick="removeUser(this)">❌</button>
                    </div>
                </div>
                
                <button type="button" onclick="addUser()">➕ Add User</button>
                <br><br>
                <button type="submit">Submit</button>
            </form>

            <p id="errorMessage" style="color: red;"></p>
        </div>
    </div>

    <!-- Search Bar -->
<div class="search-container">
    <input type="text" id="search" placeholder="Search by Registration No, Full Name, or ID">
</div>

<!-- Users Table -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Registration No</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Role</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users_result->fetch_assoc()): ?>
        <tr>
            <td><?= $user['user_id'] ?></td>
            <td contenteditable="true" onBlur="updateUser(<?= $user['user_id'] ?>, 'registration_no', this.innerText)"><?= $user['registration_no'] ?></td>
            <td contenteditable="true" onBlur="updateUser(<?= $user['user_id'] ?>, 'full_name', this.innerText)"><?= $user['full_name'] ?></td>
            <td contenteditable="true" onBlur="updateUser(<?= $user['user_id'] ?>, 'course', this.innerText)"><?= $user['course'] ?></td>
            <td contenteditable="true" onBlur="updateUser(<?= $user['user_id'] ?>, 'role', this.innerText)"><?= $user['role'] ?></td>
            <td contenteditable="true" onBlur="updateUser(<?= $user['user_id'] ?>, 'email', this.innerText)"><?= $user['email'] ?></td>
            <td>
                <button class="delete-btn" onclick="deleteUser(<?= $user['user_id'] ?>)">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php endif; ?>


<?php if ($role != 'admin'): ?>

<?php
// Display the vote confirmation message
if ($voteMessage) {
    echo "<p>$voteMessage</p>";
} else {
    // Show the voting form if no submission has occurred yet
?>
<form id="voteForm" action="vote_process.php" method="POST">
    <h2>Vote now to shape your future here!</h2>

    <h3 class="centered-heading">Presidents</h3>
    <table class="candidate-table">
        <tr>
            <th>Profile</th>
            <th>Full Name</th>
            <th>Description</th>
            <?php if ($role != 'lecture'): ?>
                <th>Vote</th>
            <?php endif; ?>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($presidents)): ?>
            <tr class="candidate">
                <!-- Use the profile picture from uploads/ folder -->
                <td><img id="profile-photo" src="uploads/<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Photo" width="150"></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['description'] ?></td>
                <?php if ($role != 'lecture'): ?>
                    <td><input type="radio" name="president_vote" value="<?= $row['user_id'] ?>" required></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <h3 class="centered-heading">Ministers</h3>
    <table class="candidate-table">
        <tr>
            <th>Profile</th>
            <th>Full Name</th>
            <th>Position</th>
            <th>Description</th>
            <th>Vote</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($ministers)): ?>
            <tr class="candidate">
                <!-- Use the profile picture from uploads/ folder -->
                <td><img id="profile-photo" src="uploads/<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Photo" width="150"></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['minister'] ?></td>
                <td><?= $row['description'] ?></td>
                <?php if ($role != 'lecture'): ?>
                    <td><input type="radio" name="minister_vote" value="<?= $row['user_id'] ?>" required></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>
    
    <?php if ($role != 'lecture'): ?>
       <br>
        <h3 class="centered-heading">Class Representatives</h3>
        <table class="candidate-table">
            <tr>
                <th>Profile</th>
                <th>Full Name</th>
                <th>Description</th>
                <th>Vote</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($class_reps)): ?>
                <tr class="candidate">
                    <!-- Use the profile picture from uploads/ folder -->
                    <td><img id="profile-photo" src="uploads/<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Photo" width="150"></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><input type="radio" name="class_rep_vote" value="<?= $row['user_id'] ?>" required></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <!-- Hidden input for confirmation -->
    <input type="hidden" id="confirmVoteInput" name="confirm_vote" value="">
    <br><br>
    <!-- Wrapper for Vote Button to center it -->
    <div class="vote-button-container">
        <button type="submit" name="submit_vote" id="voteButton" onclick="return confirmVote()">Submit Vote</button>
    </div>
</form>



<?php } ?>

<?php endif; ?>


    <script>
        function openUserModal() {
            document.getElementById('userModal').style.display = 'block';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function addUser() {
            const userContainer = document.getElementById('userContainer');
            const userInputs = userContainer.getElementsByClassName('user-input');

            if (userInputs.length >= 100) {
                alert("You can add a maximum of 100 users.");
                return;
            }

            const div = document.createElement('div');
            div.classList.add('user-input');
            div.innerHTML = `
                <input type="text" name="full_name[]" placeholder="Full Name" required>
                <input type="text" name="registration_no[]" placeholder="Registration No" required>
                <input type="text" name="course[]" placeholder="Course">
                <input type="password" name="password[]" placeholder="Password" required>
                <select name="role[]">
                    <option value="student">Student</option>
                    <option value="lecture">Lecture</option>
                    <option value="candidate">Candidate</option>
                </select>
                <button type="button" class="removeUser" onclick="removeUser(this)">❌</button>
            `;
            userContainer.appendChild(div);
        }

        function removeUser(button) {
            const userContainer = document.getElementById('userContainer');
            const userInputs = userContainer.getElementsByClassName('user-input');

            if (userInputs.length > 1) {
                button.parentElement.remove();
            } else {
                alert("You must add at least one user.");
            }
        }

        document.getElementById('userForm').addEventListener('submit', function(event) {
            const userInputs = document.getElementsByClassName('user-input');
            if (userInputs.length < 1 || userInputs.length > 100) {
                document.getElementById('errorMessage').textContent = "You must add between 1 and 100 users.";
                event.preventDefault();
            }
        });

        // Close the modal if the user clicks outside the content
        window.onclick = function(event) {
            var modal = document.getElementById('userModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };


        //manage users
        function updateUser(userId, column, value) {
            let formData = new FormData();
            formData.append("update_user", true);
            formData.append("id", userId);
            formData.append("column", column);
            formData.append("value", value);

            fetch("dashbord.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => alert(data));
        }

        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                let formData = new FormData();
                formData.append("delete_user", true);
                formData.append("id", userId);

                fetch("dashbord.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => alert(data));
            }
        }


        //vote logic
        function confirmVote() {
            var userChoice = confirm("Are you sure with your choice?");
            if (userChoice) {
                document.getElementById("confirmVoteInput").value = 'yes';
                return true;  // Allows form submission
            } else {
                alert("Vote submission cancelled.");
                return false; // Prevents form submission
            }
        }

        function cancelVote() {
            alert("Your vote has been canceled.");
        }



    </script>
    <div style="height:10px;">

    </div>
    <?php
    include 'footer.php';
    ?>
</body>
</html>
