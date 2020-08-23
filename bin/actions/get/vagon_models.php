<?php
	//add_wagon
	global $ASUVRK;
	$MODELS=array();
	try{
		$iVagonModels=$ASUVRK->GetVagonModels('');
		//echo $iVagonModels->Count;
		for($i=0;$i<$iVagonModels->Count;$i++){
			$iModel=$iVagonModels->GetModel($i);
			$MODELS[]=(object)array(
				'IND'=>(string)$iModel->IND,
				'MODEL'=>iconv('CP1251',"UTF-8",(string)$iModel->MODEL),
				'Tara1'=>(float)$iModel->Tara1,
				'Tara2'=>(float)$iModel->Tara2,
				'Kotel'=>(string)$iModel->Kotel,
				'Gruz'=>(float)$iModel->Gruz,
				'Gabarit'=>(int)$iModel->Gabarit,
				'BeginGod'=>(int)$iModel->BeginGod,
				'EndGod'=>(int)$iModel->EndGod,
			);
		}
	}catch(Exception $err){		
		die('{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');
	}
	$return=(object)array(
		'success'=>true,
		'result'=>$MODELS
	);
	echo json_encode($return);
?>