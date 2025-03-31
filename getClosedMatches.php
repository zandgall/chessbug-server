<?php
include "main.php";
$db = mainCheck();

// Gather all friends current user has
$query = $db->prepare(
	"SELECT m.MatchID, m.Chat, m.WhitePlayer, u1.Name as WhiteName, u1.pfp as WhitePfp, m.BlackPlayer, u2.Name as BlackName, u2.pfp as BlackPfp, m.Status FROM `ChessMatch` AS m
	INNER JOIN `User` AS u1 ON (m.WhitePlayer = u1.UserID)
	INNER JOIN `User` AS u2 ON (m.BlackPlayer = u2.UserID)
	WHERE (u1.Name = ? OR u2.Name = ?) AND (m.Status = \"WhiteWin\" OR m.Status = \"BlackWin\" OR m.Status = \"Draw\")"
);

$query->bind_param("ss", $_POST["username"], $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all clubs this user is a member of
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC), "error" => false)));
