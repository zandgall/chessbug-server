<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to send to
checkpoint(isset($_POST["chat"]), "Provide chat ID integer to send a message too!");

// X Wasn't provided a message
checkpoint(isset($_POST["content"]), "Provide a message!");

// Insert message
$query = $db->prepare("INSERT INTO `Message` (Content, Sender, Chat) SELECT ?, User.UserID, ? FROM User WHERE User.Name = ? AND User.Password = ?");
$query->bind_param("siss", $_POST["content"], $_POST["chat"], $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(true);
