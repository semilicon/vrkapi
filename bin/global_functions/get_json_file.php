<?php
function get_json_file($file){
	$contents = file_get_contents($file);
	$array = json_decode($contents, true);
	return $array;
}
?>