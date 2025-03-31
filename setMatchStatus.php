<?php
include "main.php";

$db = mainCheck();

// Update the status of a match
$query = $db->prepare("UPDATE `ChessMatch` SET Status=? WHERE MatchID = ?");
$query->bind_param("si", $_POST["status"], $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => true, "error" => false)));
