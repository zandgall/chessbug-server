<?php

include "main.php";

$conn = mainCheck();

// Assume the secret key and username are sent via POST
$secret_key = $_POST['secret_key'];
$username = $_POST['username'];

// Save secret key to the database
$query = "UPDATE User SET secret_key = ? WHERE Name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $secret_key, $username);  // Bind parameters (secret key, username)

checkpoint($stmt->execute(), "Error saving secret key", $stmt->error);

result(json_encode(array("response" => true, "error" => false)));
