<?php
session_start(); // Start session for user login tracking

// MySQL Database Connection Details (Update these values)
$host = "sql206.thsite.top"; // Your MySQL Host
$username = "thsi_38239187"; // Your MySQL User
$password = "Your_vPanel_Password"; // Your vPanel Password
$database = "thsi_38239187_TeaHaven"; // Your Database Name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

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
            header("Location: dashboard.html");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    login($email, $password);
}

// Close connection
$conn->close();
?>
