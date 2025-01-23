<?php
include "main.php";

mysqli_report(MYSQLI_REPORT_ALL);
$db = mainCheck();

// X Wasn't provided chat to send to
checkpoint(isset($_POST["name"]), "Provide club name!");

// Create new chat
$db->query("INSERT INTO `Chat` VALUES (NULL)");
$chatID = $db->insert_id;

// Insert message
$query = $db->prepare("INSERT INTO `Club` (Name, Coach, Chat) SELECT ?, User.UserID, ? FROM User WHERE User.Name = ? AND User.Password = ?");
$query->bind_param("siss", $_POST["name"], $chatID, $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

$clubID = $query->insert_id;

// Return inserted club
$query = $db->prepare("SELECT * FROM `Club` WHERE ClubID=?");
$query->bind_param("i", $clubID);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode($query->get_result()->fetch_all(MYSQLI_ASSOC));
