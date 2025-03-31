<?php

include "secret/dbconn.php";
include "error.php";
include "result.php";

function mainCheck()
{
	global $KEY;
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	header('Content-Type: application/json; charset=utf-8');

	$db = dbConnect();

	$in = file_get_contents("php://input");
	$_POST = json_decode($in, true);

	if (isset($_POST["key"])) {
		$privateKey = openssl_pkey_get_private(file_get_contents("/etc/apache2/ssl/chessbug.main.pem"));
		openssl_private_decrypt(base64_decode($_POST["key"]), $KEY, $privateKey, OPENSSL_RAW_DATA);
		$decrypted = openssl_decrypt(base64_decode($_POST["data"]), "aes-128-cbc", $KEY, OPENSSL_RAW_DATA, "hellochessbug!<3");
		$_POST = json_decode($decrypted, true);
		checkpoint(json_last_error() == JSON_ERROR_NONE, "JSON decoding error!", $decrypted, json_last_error_msg());
	}

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
		sleep(1);
		die(error_json("Could not verify user details. Please try again.", "Received", $_POST["username"], $_POST["password"]));
	}

	return $db;
}
