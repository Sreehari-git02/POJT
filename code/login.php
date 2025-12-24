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

        <!-- Login Form -->
        
        <div class="loginForm">
            <form action="connect.php" method="POST">
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