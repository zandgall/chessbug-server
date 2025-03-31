<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided white player id
checkpoint(isset($_POST["white"]), "Provide white player ID integer!");

// X Wasn't provided black player id
checkpoint(isset($_POST["black"]), "Provide black player ID integer!");

// Create new chat
$db->query("INSERT INTO `Chat` VALUES (NULL)");
$chatID = $db->insert_id;

// Insert new chessmatch
$query = $db->prepare("INSERT INTO `ChessMatch` (`WhitePlayer`, `BlackPlayer`, `Chat`) VALUES (?, ?, ?)");
$query->bind_param("iii", $_POST["white"], $_POST["black"], $chatID);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

$matchID = $query->insert_id;

// Return necessary data for the client
result(json_encode(array("response" => array("match" => $matchID, "chat" => $chatID), "error" => false)));
