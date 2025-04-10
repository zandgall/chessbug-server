<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided match to update
checkpoint(isset($_POST["match"]), "Provide match ID integer to set status!");
checkpoint(isset($_POST["status"]), "Provide status to set!");

$query = $db->prepare(
	"SELECT MatchID From `ChessMatch`
	INNER JOIN `User` ON (ChessMatch.WhitePlayer = User.UserID OR ChessMatch.BlackPlayer = User.UserID)
	WHERE MatchID = ? AND User.Name = ?"
);
$query->bind_param("is", $_POST["match"], $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
checkpoint($query->get_result()->num_rows > 0, "User not in match requested!");

// Update the status of a match
$query = $db->prepare("UPDATE `ChessMatch` SET Status=? WHERE MatchID = ?");
$query->bind_param("si", $_POST["status"], $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => true, "error" => false)));
