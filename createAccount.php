<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

include "secret/dbconn.php";
include "error.php";

$db = dbConnect();
$_POST = json_decode(file_get_contents("php://input"), true);

// X Couldn't connect
checkpoint(!$db->connect_error, "Database Connection Failed", $db->connect_error);

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
$query = $db->prepare("INSERT INTO `User` (Name, EmailAddress, Password) VALUES (?, ?, ?)");
$query->bind_param("sss", $_POST["username"], $_POST["email"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
echo json_encode(array("response" => true, "error" => false, "message" => "Account created successfully!"));
