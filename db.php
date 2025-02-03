<?php
$servername = "	sql101.infinityfree.com"; // The database host from InfinityFree
$username = "if0_38233040"; // Your database username
$password = "ddZHoh7v34Lj"; // Your database password
$dbname = "if0_38233040_teahaven"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
