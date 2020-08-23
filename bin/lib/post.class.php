<?php
  class post{
    public static function getJsonPost(){
		if(isset($_SERVER["CONTENT_TYPE"])&&strpos(strtolower($_SERVER["CONTENT_TYPE"]),'application/json')!==false){
			$postData = file_get_contents('php://input');
			$json = json_decode($postData, true);
			if($json!==null)$_POST=$json;
		}
	}
  }
?>