<?php
session_start();
// Database connection details
$servername = "sql101.infinityfree.com";  // MySQL hostname (provided by InfinityFree)
$username = "if0_38233040";               // MySQL username
$password = "ddZHoh7v34Lj";               // MySQL password
$dbname = "if0_38233040_teahaven";        // Database name (make sure it's correct)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch user data from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email); // "s" indicates the type is string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password against the hashed password stored in the database
        if (password_verify($password, $user['password'])) {
            // Store user session and redirect to the dashboard
            $_SESSION['user'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password: set error message and redirect to login page
            $_SESSION['error_message'] = "Invalid email or password!";
            header("Location: index.php");
            exit();
        }
    } else {
        // User does not exist: set error message and redirect to login page
        $_SESSION['error_message'] = "Invalid email or password!";
        header("Location: index.php");
        exit();
    }
}

// Close the connection
$conn->close();
?>
