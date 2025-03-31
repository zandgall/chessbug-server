<?php
include "main.php";

$db = mainCheck();

// Gather all messages from given chat
$query = $db->prepare("SELECT * FROM `User` WHERE `Name` = ? AND `Password` = ?");
$query->bind_param("ss", $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0], "error" => false)));
