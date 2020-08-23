<?php
  class url{
    public static function getActionValues(){
		$insideLocation=str_replace('//','/','/'.trim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])),'/').'/');
		$URI=getenv("REQUEST_URI");
		$doubleSlash=(strpos($URI,'//')!==false)?true:false;
		$URI=preg_replace('/\/{2,}/','/',$URI);
		$URI=substr($URI, strlen($insideLocation));
		$GET='';
		
		if(strpos($URI,'?')!==false){
			$needle=explode('?', $URI);
			$URI=$needle[0];
			if(isset($needle[1]))$GET='?'.$needle[1];
		}
		if(strlen($URI)==0){
			define('__URI', '');
			define('__GET', $GET);
			return array();
		}
		if(strpos($URI,'*')!==false){
			header('HTTP/1.1 404 NotFound');
			exit;
		}
		$URI=trim($URI,'/');
		$items=explode('/', $URI);
		define('__URI', $URI);
		define('__GET', $GET);
		return $items;
	}
  }
?>