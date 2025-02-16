<?php

function error_json(...$messages)
{
	$data = array("response" => array(), "error" => true);
	foreach ($messages as $msg) {
		array_push($data["response"], $msg);
	}
	if (count($data["response"]) == 1)
		$data["response"] = $data["response"][0];
	return json_encode($data);
}

// Simple function that dies if a condition fails
function checkpoint($condition, ...$messages)
{
	if (!$condition) {
		die(error_json($messages));
	}
}
