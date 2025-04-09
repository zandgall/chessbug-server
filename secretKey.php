<?php
// Database connection
$host = 'localhost';  // Your database host
$dbname = 'chessbug';  // Your database name
$username = 'csc';     // Your DB username
$password = 'csccapstone2025!';  // Your DB password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume the secret key and username are sent via POST
$secret_key = $_POST['secret_key'];
$username = $_POST['username'];

// Save secret key to the database
$query = "UPDATE users SET secret_key = ? WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $secret_key, $username);  // Bind parameters (secret key, username)

if ($stmt->execute()) {
    echo "Secret key saved successfully for user: $username";
} else {
    echo "Error saving secret key: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
