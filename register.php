<?php
session_start(); // Start session for user authentication

// Load environment variables (for better security, use an .env file in production)
$host = "sql206.thsite.top"; // Your MySQL Host
$username = "thsi_38239187"; // Your MySQL User
$password = "Jcnicdao45"; // Your vPanel Password
$database = "thsi_38239187_TeaHaven"; // Your Database Name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

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
        die("❌ All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    if (strlen($password) < 8) {
        die("❌ Password must be at least 8 characters long.");
    }

    if ($password !== $confirmPassword) {
        die("❌ Passwords do not match!");
    }

    // Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("❌ This email is already registered.");
    }
    $stmt->close();

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (email, password, full_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashedPassword, $fullName);

    if ($stmt->execute()) {
        echo "✅ Registration successful! Redirecting...";
        header("refresh:2; url=index.html"); // Redirect to login page after 2 seconds
        exit();
    } else {
        die("❌ Error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
