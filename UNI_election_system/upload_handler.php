<?php
// upload_handler.php

function handleFileUpload($inputName, $uploadDir) {
    if (isset($_FILES[$inputName])) {
        $file = $_FILES[$inputName];

        // Check for errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "Error uploading file.";
            return false;
        }

        // Ensure it's a valid file type (image/video)
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'video/mp4', 'video/avi'];
        if (!in_array($file['type'], $allowedMimeTypes)) {
            echo "Invalid file type. Only images and videos are allowed.";
            return false;
        }

        // Generate a unique name for the uploaded file
        $newFileName = uniqid('upload_') . '_' . basename($file['name']);
        $destination = $uploadDir . $newFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName; // Return the path for saving in the database
        } else {
            echo "Error moving the uploaded file.";
            return false;
        }
    }
    return false;
}
?>
