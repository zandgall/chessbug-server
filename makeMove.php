<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to send to
checkpoint(isset($_POST["match"]), "Provide match ID integer to make a move in!");

// X Wasn't provided a message
checkpoint(isset($_POST["move"]), "Provide a move!");

$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE MatchID = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["match"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in match requested!");

// Insert message
$query = $db->prepare("INSERT INTO `ChessMatchMoves` (`ChessMatchID`, `MoveNum`, `Move`) SELECT ?, COUNT(C.Move), ? FROM `ChessMatchMoves` AS C WHERE C.`ChessMatchID` = ?");
$query->bind_param("isi", $_POST["match"], $_POST["move"], $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => true, "error" => false)));
