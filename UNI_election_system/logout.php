<?php
session_start();
session_unset(); // Clear session variables
session_destroy(); // Destroy session
header('Location: index.php'); // Redirect to login
exit;
?>
