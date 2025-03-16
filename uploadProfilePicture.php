<?php
include "main.php";

$db = mainCheck();

// X No image data provided
checkpoint(isset($_POST["image"]), "Provide image data!");

// X Image under 10mb
checkpoint(strlen($_POST["image"]) * (3.0 / 4.0) < 10000000, "Image Too Big!", strlen($_POST["image"]) * (3.0 / 4.0), ">", "10000000");

// Decode image
$img = base64_decode($_POST["image"]);

// X Data provided doesn't pass the image sniff test
checkpoint(@is_array(getimagesizefromstring($img)), "Data provided is not an image!");

// Generate UUIDs, making sure we pick one that doesn't exist
do {
	$data = random_bytes(16);

	$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
} while (file_exists("./content/" . $uuid));

// Write file data
$imgFile = fopen("./content/" . $uuid, "wb");
fwrite($imgFile, $img);
fclose($imgFile);

// Check if there's a current profile picture for the user, and delete it from the content folder
$query = $db->prepare("SELECT `pfp` FROM `User` WHERE Name=? AND Password=?");
$query->bind_param("ss", $_POST["username"], $_POST["password"]);
checkpoint($query->execute(), "Database Query Failed", $query->error);
$lastPfp = $query->get_result()->fetch_all(MYSQLI_ASSOC);
if ($lastPfp[0]["pfp"] != null && file_exists("./content/" . $lastPfp[0]["pfp"])) {
	unlink("./content/" . $lastPfp[0]["pfp"]);
}

// Update player profile picture UUID in database
$query = $db->prepare("UPDATE `User` SET `pfp`=? WHERE Name=? AND Password=?");
$query->bind_param("sss", $uuid, $_POST["username"], $_POST["password"]);

// X Query failed
checkpoint($query->execute(), "Database Query Failed", $query->error);

// Return the UUID of the successful profile picture upload
echo json_encode(array("response" => $uuid, "error" => false));
