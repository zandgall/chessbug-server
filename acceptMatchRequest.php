<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided a match to accept
checkpoint(isset($_POST["match"]), "Provide a match to accept!");

// Update match to accept request
$query = $db->prepare("UPDATE `ChessMatch` SET `Status`='InProgress' WHERE MatchID = ? AND ((Status = 'WhiteRequested' AND BlackPlayer = (SELECT UserID FROM User WHERE Name = ? AND Password = ?)) OR (Status = 'BlackRequested' AND WhitePlayer = (SELECT UserID FROM User WHERE Name = ? AND Password = ?)))");
$query->bind_param("issss", $_POST["match"], $_POST["username"], $_POST["password"], $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => ($query->affected_rows > 0), "error" => false)));
