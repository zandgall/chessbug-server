<?php

function error_json(...$messages)
{
	$data = array("response" => array(), "error"=>true);
	foreach ($messages as $msg) {
		array_push($data["response"], $msg);
	}
	return json_encode($data);
}

// Simple function that dies if a condition fails
function checkpoint($condition, ...$messages)
{
	if (!$condition) {
		die(error_json($messages));
	}
}
