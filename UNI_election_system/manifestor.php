<?php
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "nav_bar.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

include "db_connection/db_connection.php";
include "upload_handler.php";

// Function to map position keys to user-friendly names
function getPositionTitle($position) {
    $titles = [
        "president" => "Presidential Candidates",
        "minister" => "Minister Candidates",
        "class_rep" => "Class Representative Candidates"
    ];
    return $titles[$position] ?? ucfirst($position);
}

// Fetch manifestations based on position
function getManifestationsByPosition($conn, $position) {
    $stmt = $conn->prepare("
        SELECT 
            es.id,
            es.file_path, 
            es.file_data, 
            es.description, 
            es.created_at, 
            u.full_name, 
            u.profile_picture
        FROM 
            election_submissions es
        JOIN 
            users u 
        ON 
            es.user_id = u.user_id
        WHERE 
            es.position = ?
        ORDER BY 
            es.created_at DESC
    ");
    $stmt->bind_param("s", $position);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Manifestations</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    text-align: center;
}

h1 {
    color: #333;
}

.manifestation-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.manifestation {
    width: 300px;
    padding: 15px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.manifestation:hover {
    transform: scale(1.05);
}

.media {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
}

.profile-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    margin-top: 10px;
}

.profile-pic {
    width: 100px;   /* Reduced width */
    height: 100px;  /* Reduced height to make the photo smaller */
    border-radius: 50%;  /* Make the image round */
    object-fit: cover;   /* Ensure the image covers the entire circle */
}

.name {
    font-weight: bold;
    font-size: 1em;
    color: #333;
}

.description {
    font-size: 0.9em;
    color: #555;
    margin-top: 10px;
}


    </style>
</head>
<body>
    <h1>Election Manifestations</h1>

<?php
$positions = ["president", "minister", "class_rep"];

foreach ($positions as $position) :
    $manifestations = getManifestationsByPosition($conn, $position);
    if (empty($manifestations)) continue;
?>

    <h2><?= getPositionTitle($position) ?></h2>
    <div class="manifestation-section">
        <?php foreach ($manifestations as $manifestation) : ?>
            <div class="manifestation">
                <div class="profile-container">
                    <!-- Display Profile Picture -->
                    <img id="profile-photo" src="uploads/<?php echo htmlspecialchars($manifestation['profile_picture']); ?>" alt="Profile Photo" width="150">
                    <span class="name"><?= htmlspecialchars($manifestation['full_name'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <p class="description">"<?= htmlspecialchars($manifestation['description'], ENT_QUOTES, 'UTF-8') ?>"</p>
            </div>
        <?php endforeach; ?>
    </div>

<?php endforeach; ?>

</body>
</html>
