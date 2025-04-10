<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to check
checkpoint(isset($_POST["chat"]), "Provide chat ID integer to get messages from!");

$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE Chat = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["chat"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in chat requested!");

// Gather all messages from given chat
$query = $db->prepare("SELECT COUNT(*) FROM `Message` WHERE `Chat` = ?");
$query->bind_param("i", $_POST["chat"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["COUNT(*)"], "error" => false)));
