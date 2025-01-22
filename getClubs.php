<?php
include "main.php";
$db = mainCheck();

// Gather all clubs current user is a part of
$query = $db->prepare(
	"SELECT c.ClubID, c.Name, c.Coach, c.Chat FROM Club AS c
INNER JOIN UserClub AS uc ON uc.ClubID = c.ClubID
INNER JOIN User AS u ON u.UserID = uc.UserID
WHERE u.Name = ?"
);

$query->bind_param("s", $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all clubs this user is a member of
echo json_encode($query->get_result()->fetch_all(MYSQLI_ASSOC));
