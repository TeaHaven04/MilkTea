<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql206.thsite.top"; // Use your TinkerHost MySQL hostname
$username = "thsi_38239187"; // Your MySQL username
$password = "your_mysql_password"; // Your actual MySQL password
$dbname = "your_database_name"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connected successfully to the database!";
}
?>
