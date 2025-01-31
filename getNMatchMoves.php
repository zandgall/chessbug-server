<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided chat to check
checkpoint(isset($_POST["match"]), "Provide match ID integer to get messages from!");

// X Wasn't provided number of moves to retrieve
checkpoint(isset($_POST["num"]), "Provide number of moves to retrieve!");

// Gather all messages from given chat
$query = $db->prepare("SELECT MoveNum, Move FROM `ChessMatchMoves` ORDER BY MoveNum WHERE ChessMatchID = ?");
$query->bind_param("i", $_POST["match"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
$arr = $query->get_result()->fetch_all(MYSQLI_ASSOC);
echo json_encode(array("response" => array_slice($arr, max(count($arr) - $_POST["num"], 0)), "error" => false));
