<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided a person to add as a friend
checkpoint(isset($_POST["target"]), "Provide a target to friend!");

// Create new chat
$db->query("INSERT INTO `Chat` VALUES (NULL)");
$chatID = $db->insert_id;

// Check for an existing relationship between the two users before creating a new one
$query = $db->prepare("SELECT RequestStatus FROM `Friends` WHERE (User1 = (SELECT UserID FROM `User` WHERE Name = ?) AND User2 = (SELECT UserID FROM `User` WHERE Name = ?)) OR (User2 = (SELECT UserID FROM `User` WHERE Name = ?) AND User1 = (SELECT UserID FROM `User` WHERE Name = ?))");
$query->bind_param("ssss", $_POST["username"], $_POST["target"], $_POST["username"], $_POST["target"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows == 0, "Already friends or request already exists!");

// Insert message
$query = $db->prepare("INSERT INTO `Friends` (User1, User2, Chat, RequestStatus) SELECT a.UserID, b.UserID, ?, \"ACTIVE\" FROM User as a JOIN User as b WHERE a.Name = ? AND a.Password = ? AND b.Name = ?");
$query->bind_param("isss", $chatID, $_POST["username"], $_POST["password"], $_POST["target"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(array("response" => true, "error" => false));
