<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided a person to add as a friend
checkpoint(isset($_POST["target"]), "Provide a person to request a match with!");

// X Wasn't provided a request type
checkpoint(isset($_POST["request"]), "Provide a request type to make!");

// X Request type is invalid
checkpoint($_POST["request"] == "WhiteRequested" || $_POST["request"] == "BlackRequested", "Invalid match request type");

// Create new chat
$db->query("INSERT INTO `Chat` VALUES (NULL)");
$chatID = $db->insert_id;

// Figure which player color is which
$white = $_POST["username"];
$black = $_POST["target"];
if ($_POST["request"] == "BlackRequested") {
	$white = $_POST["target"];
	$black = $_POST["username"];
}

// Insert new match
$query = $db->prepare("INSERT INTO `ChessMatch` (WhitePlayer, BlackPlayer, Status, Chat) SELECT a.UserID, b.UserID, ?, ? FROM User as a JOIN User as b WHERE a.Name = ? AND b.Name = ?");
$query->bind_param("siss", $_POST["request"], $chatID, $white, $black);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => true, "error" => false)));
