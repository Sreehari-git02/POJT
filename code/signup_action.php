<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 2. Use Prepared Statements
    $stmt = $conn->prepare("INSERT INTO users (fullName, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>