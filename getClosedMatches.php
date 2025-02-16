<?php
include "main.php";
$db = mainCheck();

// Gather all friends current user has
$query = $db->prepare(
	"SELECT m.MatchID, m.Chat, m.WhitePlayer, u1.Name as WhiteName, m.BlackPlayer, u2.Name as BlackName, m.Result FROM `ChessMatch` AS m
	INNER JOIN `User` AS u1 ON (m.WhitePlayer = u1.UserID)
	INNER JOIN `User` AS u2 ON (m.BlackPlayer = u2.UserID)
	WHERE (u1.Name = ? OR u2.Name = ?) AND m.Result != \"InProgress\""
);

$query->bind_param("ss", $_POST["username"], $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all clubs this user is a member of
echo json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC), "error" => false));
