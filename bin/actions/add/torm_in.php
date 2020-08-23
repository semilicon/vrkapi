<?php
	//add_wagon
	//http://83.174.201.138:1259/add/torm_in/?free&IND=15800072187&details=000000
	global $ASUVRK;
	if(!isset($_GET['IND']))die('{"success":false,"result":{"error":"wrongIND"}}');
	if(!isset($_GET['details']))die('{"success":false,"result":{"error":"wrongDetails"}}');
	$IND=trim($_GET['IND']);
	$details=trim($_GET['details']);//string:111111
	if(strlen($details)!=6)die('{"success":false,"result":{"error":"wrongDetails"}}');
	$iVagon=$ASUVRK->GetVagonByIND($IND);
	$iVagonDetails=$iVagon->VagonComplekt()->IN;
	try{
		$iVagonDetails->Tormoza=$details;
	}catch(Exception $err){
		echo '{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}';
		exit;
	}
	echo '{"success":true,"result":{"details":"ok"}}';
?>