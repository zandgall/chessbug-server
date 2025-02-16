<?php
include "main.php";
$db = mainCheck();

// Gather all friends current user has
$query = $db->prepare(
	"SELECT u2.UserID, u2.Name, f.Chat FROM `Friends` AS f
	INNER JOIN `User` AS u ON (f.User1 = u.UserID OR f.User2 = u.UserID) AND u.Name = ?
	INNER JOIN `User` AS u2 ON (f.User1 = u2.UserID OR f.User2 = u2.UserID) AND u2.UserID != u.UserID
	WHERE f.RequestStatus = \"ACCEPTED\""
);

$query->bind_param("s", $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all clubs this user is a member of
echo json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC), "error" => false));
