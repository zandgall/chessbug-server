<?php
include "main.php";

$db = mainCheck();

// Gather the status of a match
$query = $db->prepare("SELECT Status FROM `ChessMatch` WHERE `MatchID` = ?");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Status"], "error" => false)));
