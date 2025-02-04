<?php
session_start(); // Start session for user authentication
require_once 'config.php'; // Include database connection file

// Function to sanitize input
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $fullName = cleanInput($_POST['fullName']);
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);
    $confirmPassword = cleanInput($_POST['confirmPassword']);

    // Validate inputs
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("<script>alert('❌ All fields are required.'); window.location.href='register.html';</script>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<script>alert('❌ Invalid email format.'); window.location.href='register.html';</script>");
    }

    if (strlen($password) < 8) {
        die("<script>alert('❌ Password must be at least 8 characters long.'); window.location.href='register.html';</script>");
    }

    if ($password !== $confirmPassword) {
        die("<script>alert('❌ Passwords do not match!'); window.location.href='register.html';</script>");
    }

    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("<script>alert('❌ This email is already registered.'); window.location.href='register.html';</script>");
    }
    $stmt->close();

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful! Redirecting to login...'); window.location.href='index.html';</script>";
        exit();
    } else {
        die("<script>alert('❌ Error registering user. Please try again.'); window.location.href='register.html';</script>");
    }

    $stmt->close();
}

$conn->close();
?>
