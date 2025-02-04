<?php
session_start(); // Start session for user login tracking
require_once 'config.php'; // Include database connection file

// Secure Login Function
function login($email, $password) {
    global $conn;

    // Prepare SQL statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists, verify password
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) { 
            // Store user info in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('❌ Invalid email or password.'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('❌ Invalid email or password.'); window.location.href='index.html';</script>";
    }

    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    login($email, $password);
}

// Close connection
$conn->close();
?>
