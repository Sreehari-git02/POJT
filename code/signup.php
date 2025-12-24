<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="../style/signup.css">
</head>
<body>
    <div class="signupContainer">
        <h1 class="heading">Create a new Account</h1>
        <h5 class="signupHead">It's Quick and Easy</h5>

        <div class="signinForm">
            <div class="inputGroup">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>
            </div>

            <div class="inputGroup">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="inputGroup">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>

            <div class="inputGroup">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="password" required>
            </div>

            <div class="inputGroup">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Repeat Password" required>
            </div>
            <button type="submit" id="signupBtn">Create Account</button>
            <p class="loginLink">Already have an account? <a href="login.php">Log In</a>
            </p>
        </div>
    </div>
</body>
</html>