<?php
// Database connection details from InfinityFree
$servername = "sql101.infinityfree.com";  // MySQL Host
$username = "if0_38233040";               // MySQL Username
$password = "ddZHoh7v34Lj";       // MySQL Password
$dbname = "if0_38233040_teahaven";        // MySQL Database Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash password before storing (for security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (fullName, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
