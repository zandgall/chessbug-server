<?php
// Include the GoogleAuthenticator library
require_once 'GoogleAuthenticatorKliewe.php';  // Path to the GoogleAuthenticator class file
include "main.php";

// Create GoogleAuthenticator instance
$ga = new PHPGangsta_GoogleAuthenticator();

// Assume the username and OTP are sent via POST
$username = $_POST['username'];
$otp = $_POST['otp'];  // OTP entered by the user

$conn = mainCheck();

// Retrieve secret key from database
$query = "SELECT secret_key FROM User WHERE Name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($stored_secret_key);
$stmt->fetch();
$stmt->close();
$conn->close();

checkpoint($stored_secret_key, "User not found!");

// Verify the OTP
$result = $ga->verifyCode($stored_secret_key, $otp, 2);  // 2 = allow a window of 2 attempts (you can adjust as needed)
checkpoint($result, "Invalid OTP!");
result(json_encode(array("response" => true, "error" => false)));
