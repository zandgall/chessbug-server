<?php
include "main.php";
$db = mainCheck();

// Gather all friends current user has
$query = $db->prepare(
	"SELECT u2.UserID, u2.Name, u2.pfp, f.Chat FROM `Friends` AS f
	INNER JOIN `User` AS u ON f.User2 = u.UserID AND u.Name = ?
	INNER JOIN `User` AS u2 ON f.User1 = u2.UserID AND u2.UserID != u.UserID
	WHERE f.RequestStatus = \"ACTIVE\""
);

$query->bind_param("s", $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all clubs this user is a member of
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC), "error" => false)));
