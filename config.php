<?php
// MySQL Database Connection Details
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
?>
