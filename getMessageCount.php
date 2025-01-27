<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to check
checkpoint(isset($_POST["chat"]), "Provide chat ID integer to get messages from!");

// Gather all messages from given chat
$query = $db->prepare("SELECT COUNT(*) FROM `Message` WHERE `Chat` = ?");
$query->bind_param("i", $_POST["chat"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode("response"=>$query->get_result()->fetch_all(MYSQLI_ASSOC)["COUNT(*)"], "error"=>false);
