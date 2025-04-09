<?php

include "main.php";

$db = mainCheck();

// X Wasn't provided the right details
checkpoint(isset($_POST["newUsername"]), "Provide a new username!");
checkpoint(isset($_POST["newPassword"]), "Provide a new password!");
checkpoint(isset($_POST["newEmail"]), "Provide a new email!");
checkpoint(isset($_POST["newBio"]), "Provide a new bio!")

// Insert message
$query = $db->prepare("UPDATE `User` SET `Name` = ?, `Password` = ?, `EmailAddress` = ? WHERE `Name` = ? AND `Password` = ?");
$query->bind_param("sssss", $_POST["newUsername"], $_POST["newPassword"], $_POST["newEmail"], $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
result(json_encode(array("response" => true, "error" => false, "message" => "Account updated successfully!")));
