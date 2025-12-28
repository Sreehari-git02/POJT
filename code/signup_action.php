<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // 1. Check passwords
    if ($password !== $confirmPassword) {
        header("Location: signup.php?error=passmiss");
        exit();
    }

    // 2. Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        header("Location: signup.php?error=taken");
        exit();
    }
    $check->close();

    // 3. Save User
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fullName, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: signup.php?signup=success");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>