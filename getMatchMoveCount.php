<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided match to check
checkpoint(isset($_POST["match"]), "Provide match ID integer to get messages from!");

// Gather all messages from given chat
$query = $db->prepare("SELECT COUNT(*) FROM `ChessMatchMoves` WHERE `MatchID` = ?");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
echo json_encode(array("response" => $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["COUNT(*)"], "error" => false));
