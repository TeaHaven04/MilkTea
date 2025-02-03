<?php
session_start();

// Database connection details
$servername = "sql101.infinityfree.com";
$username = "if0_38233040";
$password = "ddZHoh7v34Lj";
$dbname = "if0_38233040_teahaven";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    // Sanitize input
    $full_name = mysqli_real_escape_string($conn, $full_name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $confirm_password = mysqli_real_escape_string($conn, $confirm_password);

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long!";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email is already registered!";
        exit();
    }
    $stmt->close();

    // Insert user data into the database using prepared statements
    $sql = "INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $full_name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to login page...";
        header("Location: index.html");  // Redirect to the login page after success
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
