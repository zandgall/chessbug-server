<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided a person to add as a friend
checkpoint(isset($_POST["target"]), "Provide a target to friend!");

// Create new chat
$db->query("INSERT INTO `Chat` VALUES (NULL)");
$chatID = $db->insert_id;

// Insert message
$query = $db->prepare("INSERT INTO `Friends` (User1, User2, Chat, RequestStatus) SELECT a.UserID, b.UserID, ?, \"ACTIVE\" FROM User as a JOIN User as b WHERE a.Name = ? AND a.Password = ? AND b.Name = ?");
$query->bind_param("isss", $chatID, $_POST["username"], $_POST["password"], $_POST["target"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(array("response" => true, "error" => false));
