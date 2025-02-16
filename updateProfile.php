<?php

include "main.php";

$db = mainCheck();

// X Wasn't provided an email
checkpoint(isset($_POST["content"]), "Provide a message!");

// Insert message
$query = $db->prepare("UPDATE `User` SET `Name` = ?, `Password` = ?, `Email` = ?)");
$query->bind_param("sss", $_POST["username"], $_POST["password"], $_POST["email"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
echo json_encode(array("response" => true, "error" => false, "message" => "Account updated successfully!"));
