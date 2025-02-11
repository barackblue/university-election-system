<?php
include 'nav_bar.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db_connection/db_connection.php";

// Define positions for election
$positions = ['president', 'minister', 'class_rep'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 2.5rem;
            margin: 20px 0;
            color: #007bff;
        }

        h2 {
            font-size: 1.8rem;
            color: #343a40;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        .results-section {
            margin: 40px 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .candidate {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .candidate:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .candidate img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-right: 20px;
            object-fit: cover;
        }

        .candidate-info {
            flex: 1;
        }

        .candidate-info p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .candidate-info strong {
            color: #007bff;
        }

        .no-candidates {
            font-size: 1.2rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .candidate {
                flex-direction: column;
                align-items: flex-start;
            }

            .candidate img {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <h1 style="text-align: center;">Election Results</h1>

    <?php
    // Loop through each position (president, minister, class_rep)
    foreach ($positions as $position) {
        echo "<div class='results-section'>";
        echo "<h2>" . ucfirst($position) . " Candidates</h2>";

        // Step 1: Fetch candidates for each position, join with votes and users
        $sql = "SELECT u.full_name, u.profile_picture, es.position, es.description, es.file_path, 
                       COUNT(v.id) AS vote_count
                FROM election_submissions es
                LEFT JOIN votes v ON es.id = v.election_submission_id AND v.position = ?
                LEFT JOIN users u ON es.user_id = u.user_id
                WHERE es.position = ?
                GROUP BY es.id
                ORDER BY vote_count DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $position, $position);
        $stmt->execute();
        $result = $stmt->get_result();

        // Step 2: Display candidates and their vote counts
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display candidate info
                echo "<div class='candidate'>";
                echo "<img src='uploads/{$row['profile_picture']}' alt='Profile Picture'>";
                echo "<div class='candidate-info'>";
                echo "<p><strong>Name:</strong> " . $row['full_name'] . "</p>";
                echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
                echo "<p><strong>Votes:</strong> " . $row['vote_count'] . "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-candidates'>No candidates found for the " . ucfirst($position) . " position.</p>";
        }

        echo "</div>"; // End of results-section
    }
    ?>

</body>
</html>
