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

    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        die("<script>alert('❌ Database error. Please try again later.'); window.location.href='register.html';</script>");
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('❌ This email is already registered.'); window.location.href='register.html';</script>";
        exit();
    }
    $stmt->close();

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("<script>alert('❌ Database error. Please try again later.'); window.location.href='register.html';</script>");
    }
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful! Redirecting to login...'); window.location.href='index.html';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Error registering user. Please try again.'); window.location.href='register.html';</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
