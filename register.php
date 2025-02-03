<?php
// Start session
session_start();

// Database connection details
$servername = "sql101.infinityfree.com";
$username = "if0_38233040";
$password = "ddZHoh7v34Lj";
$dbname = "teahaven";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate input fields
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error_message'] = "Please fill in all fields!";
        header("Location: register.html");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error_message'] = "Passwords do not match!";
        header("Location: register.html");
        exit();
    }

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Email is already registered!";
        header("Location: register.html");
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: index.html");
        exit();
    } else {
        $_SESSION['error_message'] = "Error occurred during registration!";
        header("Location: register.html");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
