<?php

include "main.php";
$db = mainCheck();

// Count number of won matches, lost matches, matches drawn
$query = $db->prepare(
	"SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID AND m.Status = \"WhiteWin\") OR (m.BlackPlayer = u.UserID AND m.Status = \"BlackWin\")
	WHERE u.Name = ?
	UNION
	SELECT Count(*) AS Stat FROM `ChessMatch` AS l
	INNER JOIN `User` AS u ON (l.WhitePlayer = u.UserID AND l.Status = \"BlackWin\") OR (l.BlackPlayer = u.UserID AND l.Status = \"WhiteWin\")
	WHERE u.Name = ?
	UNION
	SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID OR m.BlackPlayer = u.UserID) AND m.Status = \"Draw\"
	WHERE u.Name = ?
	UNION
	SELECT Count(*) AS Stat FROM `ChessMatch` AS m
	INNER JOIN `User` AS u ON (m.WhitePlayer = u.UserID OR m.BlackPlayer = u.UserID) AND (m.Status = \"WhiteTurn\" OR m.Status = \"BlackTurn\")
	WHERE u.Name = ?"
);

$query->bind_param("ssss", $_POST["username"], $_POST["username"], $_POST["username"], $_POST["username"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return the query result, being match statistics
$res = $query->get_result()->fetch_all(MYSQLI_ASSOC);
result(json_encode(array("response" => array(
	"Won" => $res[0]["Stat"],
	"Lost" => $res[1]["Stat"],
	"Draw" => $res[2]["Stat"],
	"Current" => $res[3]["Stat"],
	"Total" => $res[0]["Stat"] + $res[1]["Stat"] + $res[2]["Stat"] + $res[3]["Stat"]
), "error" => false)));
