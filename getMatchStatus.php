<?php
include "main.php";

$db = mainCheck();

$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE MatchID = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["match"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in match requested!");

// Gather the status of a match
$query = $db->prepare("SELECT Status FROM `ChessMatch` WHERE `MatchID` = ?");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Status"], "error" => false)));
