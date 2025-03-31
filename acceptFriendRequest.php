<?php
include "main.php";

$db = mainCheck();

// X Wasn't provided a person to add as a friend
checkpoint(isset($_POST["target"]), "Provide a target to accept as a friend!");

// Insert message
$query = $db->prepare("UPDATE `Friends` SET `RequestStatus`='ACCEPTED' WHERE User2 = (SELECT UserID FROM User WHERE Name = ? AND Password = ?) AND User1 = (SELECT UserID FROM User WHERE Name = ?) AND `RequestStatus` = 'ACTIVE'");
$query->bind_param("sss", $_POST["username"], $_POST["password"], $_POST["target"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return all chats from region
result(json_encode(array("response" => ($query->affected_rows > 0), "error" => false)));
