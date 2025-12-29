<?php 
session_start(); // Start the session
include 'connect.php'; 

// If user is already logged in, redirect them to index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <div class="loginContainer">
        <h1 class="heading">WELCOME BACK!</h1>
        <h3 class="loginHead">USER LOG-IN</h3>

        <div class="loginForm">
            <?php if (isset($_GET['error'])): ?>
                <p class="errorMessage">
                    <?php 
                        if ($_GET['error'] == "nouser") echo "No user found with that Email/Username.";
                        if ($_GET['error'] == "wrongpass") echo "Incorrect password. Please try again.";
                    ?>
                </p>
            <?php endif; ?>

            <form action="login_action.php" method="POST">
                <div class="inputGroup">
                    <label for="userIdentifier">Username/Email</label>
                    <input type="text" id="userIdentifier" name="userIdentifier" placeholder="Username/Email" required>
                </div>

                <div class="inputGroup">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" id="loginBtn">Log In</button>
            </form>

            <p class="signupLink">
                Don't have an account? <a href="signup.php">Create an Account</a>
            </p>
        </div>
    </div>
</body>
</html>