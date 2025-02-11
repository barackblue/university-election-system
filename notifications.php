<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db_connection/db_connection.php";  // Ensure this is included to establish the DB connection
include "nav_bar.php";

$role = $_SESSION['role'];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

// Fetch user feedback from the database, including user info
$sql = "SELECT uf.id, uf.name, uf.email, uf.message, uf.created_at
        FROM user_feedback uf
        JOIN users u ON uf.user_id = u.user_id
        ORDER BY uf.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 2px;
            border-radius: 20px;
            
        }
        .content {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            
        }
        th {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>
<body>
<?php if ($role === 'admin'): ?>
   
    <div class="header">
        <h1>User Feedback</h1>
    </div>
    <div class="content">
        <h2>Messages</h2>
        <table>
            <thead>
                <tr>
                    <th>Profile photo</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Received At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <!-- Optionally, display a message for non-admin users -->
    <p>Wait a minute.</p>
    <p>U have no notifications yet</p>
<?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
