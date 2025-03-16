<?php

include "main.php";

$db = mainCheck();

// X Wasn't provided the right details
checkpoint(isset($_POST["newPassword"]), "Provide a new password!");

// Insert message
$query = $db->prepare("UPDATE `User` SET `Password` = ? WHERE `Name` = ? AND `Password` = ?");
$query->bind_param("sss", $_POST["newPassword"], $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
echo json_encode(array("response" => true, "error" => false, "message" => "Account updated successfully!"));
