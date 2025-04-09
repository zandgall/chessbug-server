<?php
// Include the GoogleAuthenticator library
require_once 'GoogleAuthenticator.php';  // Path to the GoogleAuthenticator class file

// Create GoogleAuthenticator instance
$ga = new PHPGangsta_GoogleAuthenticator();

// Assume the username and OTP are sent via POST
$username = $_POST['username'];
$otp = $_POST['otp'];  // OTP entered by the user

// Fetch the stored secret key for the user from the database
$host = 'localhost';
$dbname = 'chessbug';
$dbuser = 'csc';
$dbpassword = 'csccapstone2025!';

$conn = new mysqli($host, $dbuser, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve secret key from database
$query = "SELECT secret_key FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($stored_secret_key);
$stmt->fetch();

if ($stored_secret_key) {
    // Verify the OTP
    $result = $ga->verifyCode($stored_secret_key, $otp, 2);  // 2 = allow a window of 2 attempts (you can adjust as needed)

    if ($result) {
        echo "OTP is valid!";
    } else {
        echo "Invalid OTP!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();
?>
