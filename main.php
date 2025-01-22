<?php

include "secret/dbconn.php";
include "error.php";

function mainCheck()
{
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	header('Content-Type: application/json; charset=utf-8');

	$db = dbConnect();

	$_POST = json_decode(file_get_contents("php://input"), true);

	// X Couldn't connect
	checkpoint(!$db->connect_error, "Database Connection Failed", $db->connect_error);

	// X Wasn't provided login details
	checkpoint(isset($_POST["username"]) && isset($_POST["password"]), "Provide login details in order to retrieve data!");

	// Check for user...
	$login_check = $db->prepare("SELECT * FROM `User` WHERE `Name` = ? AND `Password` = ?");
	$login_check->bind_param("ss", $_POST["username"], $_POST["password"]);

	// X Login check query failed
	checkpoint($login_check->execute(), "Could not check database for given user details.");

	// X No user with given username and password (sleep so that spamming this query takes too long to brute force things)
	if ($login_check->get_result()->num_rows != 1) {
		sleep(5);
		die(error_json("Could not verify user details. Please try again."));
	}

	return $db;
}
