<?php

$KEY = null;

function result($message)
{
	global $KEY;
	if (is_null($KEY))
		die($message);

	die(base64_encode(openssl_encrypt($message, "aes-128-cbc", $KEY, OPENSSL_RAW_DATA, "hellochessbug!<3")));
}
