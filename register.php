<?php
// Supabase connection details
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';  // Host from your connection details
$port = '6543';  // Port for connection pooling (you can use 5432 if not using pooling)
$dbname = 'postgresql';  // Corrected database name
$user = 'postgres.kmvjbwggkzpwyfyqkczw';  // Username from your connection details
$password = 'kyle123';  // Password from your connection details

// Create connection using mysqli
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Check if passwords match
    if ($password != $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "This email is already registered.";
        exit();
    }

    // Insert the new user into the database
    $sql = "INSERT INTO users (email, password, full_name) VALUES ('$email', '$hashedPassword', '$fullName')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: index.html");  // Redirect to login page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
