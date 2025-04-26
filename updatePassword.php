<?php

include "main.php";

$db = mainCheck();

// X Wasn't provided the right details
checkpoint(isset($_POST["newPassword"]), "Provide a new password!");
checkpoint(isset($_POST["oldPassword"]), "Provide a new password!");
checkpoint($_POST["oldPassword"] == $_POST["password"], "Old password isn't correct!");
checkpoint($_POST["newPassword"] != $_POST["oldPassword"], "New password was the same! ...password updated successfully?");

// Insert message
$query = $db->prepare("UPDATE `User` SET `Password` = ? WHERE `Name` = ? AND `Password` = ?");
$query->bind_param("sss", $_POST["newPassword"], $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
result(json_encode(array("response" => true, "error" => false, "message" => "Account updated successfully!")));
