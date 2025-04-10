<?php

include "main.php";

$db = mainCheck();

// X Wasn't provided the right details
checkpoint(isset($_POST["newUsername"]), "Provide a new username!");
checkpoint(isset($_POST["newEmail"]), "Provide a new email!");
checkpoint(isset($_POST["newBio"]), "Provide a new bio!");

// Insert message
$query = $db->prepare("UPDATE `User` SET `Name` = ?, `EmailAddress` = ?, `Bio` = ? WHERE `Name` = ?");
$query->bind_param("ssss", $_POST["newUsername"], $_POST["newEmail"], $_POST["newBio"], $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return true
result(json_encode(array("response" => true, "error" => false, "message" => "Account updated successfully!")));
