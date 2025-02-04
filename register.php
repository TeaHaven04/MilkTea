<?php
session_start();
require_once 'config.php'; // Database connection file

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = cleanInput($_POST['fullName']);
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);
    $confirmPassword = cleanInput($_POST['confirmPassword']);

    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('❌ All fields are required.'); window.location.href='register.html';</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('❌ Invalid email format.'); window.location.href='register.html';</script>";
        exit();
    }

    if (strlen($password) < 8) {
        echo "<script>alert('❌ Password must be at least 8 characters long.'); window.location.href='register.html';</script>";
        exit();
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('❌ Passwords do not match!'); window.location.href='register.html';</script>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('❌ Email already registered.'); window.location.href='register.html';</script>";
        exit();
    }
    $stmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful! Redirecting to login...'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('❌ Registration failed. Please try again.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
