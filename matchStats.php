<?php

include "main.php";
$db = mainCheck();

// Count number of won matches, lost matches, matches drawn
$query = $db->prepare(
	"SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID AND m.Status = \"WhiteWin\") OR (m.BlackPlayer = u.UserID AND m.Status = \"BlackWin\")
	WHERE u.Name = ?"
);
$query->bind_param("s", $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
$won = $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Stat"];
$query = $db->prepare(
	"SELECT Count(*) AS Stat FROM `ChessMatch` AS l
	INNER JOIN `User` AS u ON (l.WhitePlayer = u.UserID AND l.Status = \"BlackWin\") OR (l.BlackPlayer = u.UserID AND l.Status = \"WhiteWin\")
	WHERE u.Name = ?"
);
$query->bind_param("s", $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
$lost = $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Stat"];
$query = $db->prepare(
	"SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID OR m.BlackPlayer = u.UserID) AND m.Status = \"Draw\"
	WHERE u.Name = ?"
);
$query->bind_param("s", $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
$draw = $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Stat"];
$query = $db->prepare(
	"SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID OR m.BlackPlayer = u.UserID) AND (m.Status = \"WhiteTurn\" OR m.Status = \"BlackTurn\")
	WHERE u.Name = ?"
);
$query->bind_param("s", $_POST["username"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
$current = $query->get_result()->fetch_all(MYSQLI_ASSOC)[0]["Stat"];

// Return the query result, being match statistics
result(json_encode(array("response" => array(
	"Won" => $won,
	"Lost" => $lost,
	"Draw" => $draw,
	"Current" => $current,
	"Total" => $won + $lost + $draw + $current,
), "error" => false)));
