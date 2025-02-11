<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection/db_connection.php'; // Include your database connection




// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $registration_no = trim($_POST['registration_no']);
    $password = trim($_POST['password']);
    
    // Validate user input
    if (!empty($registration_no) && !empty($password)) {
        // Query the database
        $query = 'SELECT * FROM users WHERE registration_no = ?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $registration_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password (assuming passwords are hashed using bycrypt)
            if (password_verify($password, $user['password_hash'])) {

                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['registration_no'] = $user['registration_no'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirect to the dashboard
                header('Location: dashbord.php');
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>


<style>
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #23242a;
    margin: 0;
}

.box {
    position: relative;
    width: 380px;
    height: 500px;
    background: #1c1c1c;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.box::before, .box::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg,transparent, transparent, #45f3ff, #45f3ff, #45f3ff);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
}

.box::after {
    animation-delay: -3s;
}

.borderLine::before, .borderLine::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #ff2770, #ff2770, #ff2770);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -1.5s;
}

@keyframes animate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.box form {
    position: absolute;
    inset: 4px;
    background: #222;
    padding: 50px 40px;
    border-radius: 8px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.box form h1 {
    color: #fff;
    font-weight: 500;
    text-align: center;
    letter-spacing: 0.1em;
}

.box form .inputBox {
    position: relative;
    width: 100%;
    margin-top: 35px;
}

.box form .inputBox input {
    position: relative;
    width: 100%;
    padding: 20px 0px 10px;
    background: transparent;
    outline: none;
    border: none;
    box-shadow: none;
    color: #23242a;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
    z-index: 10;
}

.box form .inputBox span {
    position: absolute;
    left: 0;
    padding: 20px 0px 10px;
    pointer-events: none;
    color: #8f8f8f;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
}

.box form .inputBox input:valid ~ span,
.box form .inputBox input:focus ~ span {
    color: #fff;
    font-size: 0.75em;
    transform: translateY(-34px);
}

.box form .inputBox i {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    transition: 0.5s;
    pointer-events: none;
}

.box form .inputBox input:valid ~ i,
.box form .inputBox input:focus ~ i {
    height: 44px;
}

.box form .link {
    display: flex;
    justify-content: center;
    width: 100%;
}

.box form .link a {
    margin: 10px 0;
    font-size: 1em;
    color: #fff;
    text-decoration: none;
}

.box form .link a:hover {
    color: crimson;
}

.box form input[type="submit"] {
    border: none;
    outline: none;
    padding: 9px 25px;
    background: #fff;
    cursor: pointer;
    font-size: 0.9em;
    border-radius: 4px;
    font-weight: 600;
    width: 100px;
    margin-top: 10px;
}

.box form input[type="submit"]:active {
    opacity: 0.8;
}

@media (max-width: 480px) {
    .box {
        width: 100%;
        height: auto;
        padding: 20px;
    }

    .box form {
        padding: 30px;
    }

    .box form h1 {
        font-size: 1.5em;
    }

    .box form .inputBox input,
    .box form input[type="submit"] {
        width: 100%;
        font-size: 1.1em;
    }
}
</style>

<body class="login-page">
    

        <div class="logo-backo">
                        <img aria-hidden="true" src="https://www.iaa.ac.tz/storage/app/public/206/6658987f4f099_Untitled-1.png" alt="iaa-logo">
        </div>
        
    <div class="box">  
        <span class="borderline"></span>  
        <form action="" method="POST" class="login">
            
                
                <h1>WELCOME</h1>
                <br>
                <div class="inputBox">
                    <input type="text" name="registration_no" required>
                    <span>Registration_no</span>
                    <i></i>
                </div>

                <div class="inputBox">
                    <input type="password" name="password" required>
                    <span>Password</span>
                    <i></i>
                </div>

                <div class="link">
                    <a href="#">Forgot password?</a>
                </div>
            
                <input type="submit" value="Login">
            
            <!-- Display errors, if any -->
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
