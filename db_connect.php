<?php
$host = "sqlXXX.epizy.com";  // Replace with your actual MySQL hostname
$user = "your_username";      // Your InfinityFree database username
$password = "your_password";  // Your InfinityFree database password
$database = "teahaven";       // Updated database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>
