<?php
include "main.php";

$db = mainCheck();

// X No user to get profile picture from provided
checkpoint(isset($_POST["target"]), "Provide user to grab profile picture from!");

$query = $db->prepare("SELECT `pfp` FROM `User` WHERE Name=?");
$query->bind_param("s", $_POST["target"]);

checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return the UUID of the profile picture
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["pfp"], "error" => false)));
