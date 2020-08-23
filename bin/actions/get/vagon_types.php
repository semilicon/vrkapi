<?php
	//vagon_types
	global $ASUVRK;
	$TYPES=array();
	try{
		$iVagonTypes=$ASUVRK->GetVagonTypes();
		//echo $iVagonTypes->Count;
		for($i=0;$i<$iVagonTypes->Count;$i++){
			$TYPES[]=(object)array(
				'IND'=>(string)$iVagonTypes->GetIND($i),
				'NAME'=>iconv('CP1251',"UTF-8",(string)$iVagonTypes->GetName($i))
			);
		}
	}catch(Exception $err){		
		die('{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');
	}
	$return=(object)array(
		'success'=>true,
		'result'=>$TYPES
	);
	echo json_encode($return);
?>