<?php
// Supabase connection details
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';  // Host from your connection details
$port = '6543';  // Port for connection pooling (you can use 5432 if not using pooling)
$dbname = 'tea_haven;  // Database name
$user = 'postgres.kmvjbwggkzpwyfyqkczw';  // Username from your connection details
$password = 'kyle123';  // Password from your connection details

// Create connection using mysqli
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle login
function login($email, $password) {
    global $conn;
    
    // Sanitize inputs to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    // Query to find the user by email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // User found, check if password matches
        $row = $result->fetch_assoc();
        
        // Verify the password (assuming it's stored hashed)
        if (password_verify($password, $row['password'])) {
            // Password is correct, redirect to dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid email or password.";
        }
    } else {
        // User not found
        echo "Invalid email or password.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Call the login function
    login($email, $password);
}

$conn->close();
?>
