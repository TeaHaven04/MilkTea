<?php
session_start();

// Placeholder credentials (for testing purposes)
$valid_email = "test@teahaven.com";
$valid_password = "password123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate credentials
    if ($email === $valid_email && $password === $valid_password) {
        // Store user session and redirect to the dashboard
        $_SESSION['user'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        // Set error message in session and redirect back to login page
        $_SESSION['error_message'] = "Invalid email or password!";
        header("Location: index.php");
        exit();
    }
}
?>
