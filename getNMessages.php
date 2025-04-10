<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to check
checkpoint(isset($_POST["chat"]), "Provide chat ID integer to get messages from!");

// X Wasn't provided number of messages to retrieve
checkpoint(isset($_POST["num"]), "Provide number of chats to retrieve!");

$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE Chat = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["chat"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in chat requested!");

// Gather all messages from given chat
$query = $db->prepare("SELECT Message.MessageID, Message.Content, Message.Sender, Message.Time, Message.Chat, User.Name as Author FROM `Message` INNER JOIN `User` ON User.UserID = Message.Sender WHERE Message.Chat = ?");
$query->bind_param("i", $_POST["chat"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
$arr = $query->get_result()->fetch_all(MYSQLI_ASSOC);
result(json_encode(array("response" => array_slice($arr, max(count($arr) - $_POST["num"], 0)), "error" => false)));
