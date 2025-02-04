<?php
include "main.php";

$db = mainCheck();

// Gather the result of a match
$query = $db->prepare("SELECT Result FROM `ChessMatch` WHERE `MatchID` = ?");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Result"], "error" => false));
