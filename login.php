<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "main.php";

mainCheck();

result(json_encode(array("response" => true, "error" => false, "message" => "Logged In Successfully!")));
