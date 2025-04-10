<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to check
checkpoint(isset($_POST["match"]), "Provide match ID integer to get messages from!");

// X Wasn't provided number of moves to retrieve
checkpoint(isset($_POST["num"]), "Provide number of moves to retrieve!");

// Ensure user in match requested
$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE MatchID = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["match"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in match requested!");

// Gather all messages from given chat
$query = $db->prepare("SELECT MoveNum, Move FROM `ChessMatchMoves` WHERE ChessMatchID = ? ORDER BY MoveNum");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
$arr = $query->get_result()->fetch_all(MYSQLI_ASSOC);
result(json_encode(array("response" => array_slice($arr, max(count($arr) - $_POST["num"], 0)), "error" => false)));
