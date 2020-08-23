<?php
function __autoload($class_name){
	$filename = strtolower($class_name).'.class.php';
	$file = __path.'bin/lib/'.$filename;
	if (file_exists($file) == false){return false;}
	require_once($file);
}
?>