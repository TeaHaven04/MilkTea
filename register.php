<?php
// Database connection details from InfinityFree
$servername = "sql101.infinityfree.com";  // MySQL Host
$username = "if0_38233040";              // MySQL Username
$password = "ddZHoh7v34Lj";              // MySQL Password
$dbname = "if0_38233040_teahaven";       // MySQL Database Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate required fields are not empty
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check password strength (example: minimum 8 characters)
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters.");
    }

    // Hash password before storing (for security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to avoid SQL injection
    $sql = "INSERT INTO users (fullName, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Check if prepare was successful
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    // Check for execution success
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
