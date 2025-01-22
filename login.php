<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "main.php";

mainCheck();

echo json_encode(true);
