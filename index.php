<?php
session_start();

// Database connection details
$servername = "sql101.infinityfree.com";  // Your MySQL hostname (provided by InfinityFree)
$username = "if0_38233040";              // Your MySQL username (provided by InfinityFree)
$password = "ddZHoh7v34Lj";              // Your MySQL password (provided by InfinityFree)
$dbname = "teahaven_db";                 // Your database name (replace with your actual database name)

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
    $stmt->bind_param("s", $email); // "s" is for string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user session and redirect to the dashboard
            $_SESSION['user'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Set error message in session for invalid password
            $_SESSION['error_message'] = "Invalid email or password!";
            header("Location: index.php");
            exit();
        }
    } else {
        // Set error message in session if user does not exist
        $_SESSION['error_message'] = "Invalid email or password!";
        header("Location: index.php");
        exit();
    }
}

// Close the connection
$conn->close();
?>
