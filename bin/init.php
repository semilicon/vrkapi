<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//// init.php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//PHP version compare
	//if(version_compare(phpversion(),'7.1.0','<')==true){die('Need PHP7.1> Only');}
	//Path to root in fs
	define('__path', getcwd().'/');
	//PROTOCOL of reqest
	define('POST', ($_SERVER['REQUEST_METHOD'] === 'POST')?true:false);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////autoloader
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	require_once(__path.'bin/lib/autoloader.class.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////load base functions
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	foreach (glob(__path.'bin/global_functions/*.php') as $function) {require_once($function);}unset($function);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////config
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$config=get_json_file(__path.'config.json');
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////debug mode
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($config['debug']){
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}else{
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
		ini_set('display_errors', '0');
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////get json post data
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(empty($_POST))post::getJsonPost();
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////actions values
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$VALUES=url::getActionValues();
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////default timezone
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	date_default_timezone_set((isset($config['default_timezone']))?$config['default_timezone']:'Europe/Moscow');
/////////////////////////////////////////////////////////////////////////////////////////////////////////
?>