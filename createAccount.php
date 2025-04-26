<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

include "secret/dbconn.php";
include "error.php";
include "result.php";

$db = dbConnect();
$_POST = json_decode(file_get_contents("php://input"), true);

// X Couldn't connect
checkpoint(!$db->connect_error, "Database Connection Failed", $db->connect_error);

if (isset($_POST["key"])) {
	$privateKey = openssl_pkey_get_private(file_get_contents("/etc/apache2/ssl/chessbug.main.pem"));
	openssl_private_decrypt(base64_decode($_POST["key"]), $KEY, $privateKey, OPENSSL_RAW_DATA);
	$decrypted = openssl_decrypt(base64_decode($_POST["data"]), "aes-128-cbc", $KEY, OPENSSL_RAW_DATA, "hellochessbug!<3");
	$_POST = json_decode($decrypted, true);
	checkpoint(json_last_error() == JSON_ERROR_NONE, "JSON decoding error!", $decrypted, json_last_error_msg());
}

// X Wasn't provided login details
checkpoint(isset($_POST["username"]) && isset($_POST["password"]), "Missing username or password!");

$query = $db->prepare("SELECT * FROM `User` WHERE Name = ?");
$query->bind_param("s", $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// X Username already exists
checkpoint($query->get_result()->num_rows == 0, "Username taken!");

// X Wasn't provided a message
checkpoint(isset($_POST["email"]), "Provide an email!");

// Insert message
$query = $db->prepare("INSERT INTO `User` (Name, EmailAddress, Password, secret_key, bio) VALUES (?, ?, ?, \"\", \"\")");
$query->bind_param("sss", $_POST["username"], $_POST["email"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
result(json_encode(array("response" => true, "error" => false, "message" => "Account created successfully!")));
