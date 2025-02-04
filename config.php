<?php
$servername = "sql206.thsite.top"; // Use the hostname from your MySQL Info
$username = "thsi_38239187"; // Use your MySQL username
$password = "your_mysql_password"; // Replace with your actual MySQL password
$dbname = "your_database_name"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
