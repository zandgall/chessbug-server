<?php
include "main.php";

$db = mainCheck();

// Update the result of a match
$query = $db->prepare("UPDATE `ChessMatch` SET Result=? WHERE MatchID = ?");
$query->bind_param("si", $_POST["result"], $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(array("response" => true, "error" => false));
