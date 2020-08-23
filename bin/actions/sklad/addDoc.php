<?php
	//add_wagon
	global $ASUVRK;
	$nomer=(isset($_GET['nomer']))?trim($_GET['nomer']):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;nomer&quot;"}');
	$type=(isset($_GET['type']))?intval(trim($_GET['type'])):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;type&quot;"}');
	if($type!=46&&$type!=48&&$type!=39&&$type!=80)die('{"success":false,"result":{},"errorMessage":"Ошибка: Некорректное значение &quot;type&quot;"}');
	if($type!=46){
		$contractId=(isset($_GET['contractId']))?intval(trim($_GET['contractId'])):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;contractId&quot;"}');
		if($contractId<1)die('{"success":false,"result":{},"errorMessage":"Ошибка: Некорректное значение &quot;contractId&quot;"}');
		$contracts=$ASUVRK->GetContracts($contractId,-1,-1,-1);
		$iContract=$contracts->GetContract(0);
		$SID=$iContract->SID;
	}
	$Fdate=(isset($_GET['Fdate']))?strtotime(trim($_GET['Fdate'])):time();
	$comment=(isset($_GET['comment']))?iconv("UTF-8",'CP1251',trim($_GET['comment'])):'';
	//dop
	$transport=(isset($_GET['transport']))?intval(trim($_GET['transport'])):0;
	$driver=(isset($_GET['driver']))?iconv("UTF-8",'CP1251',trim($_GET['driver'])):NULL;
	$dover=(isset($_GET['dover']))?iconv("UTF-8",'CP1251',trim($_GET['dover'])):NULL;
	$auto_nomer=(isset($_GET['auto_nomer']))?iconv("UTF-8",'CP1251',trim($_GET['auto_nomer'])):NULL;
	//only today dosc
	$FromDate=date("d.m.Y");
	$ToDate=date("d.m.Y");
	$DocFilter='0';
	$HideClearDocs=1;
try{	
		$skladDocs=$ASUVRK->GetSkladExDocs($FromDate,$ToDate,$DocFilter,$HideClearDocs);
		switch($type){
			case 46:
				$iSkladEx=$skladDocs->AddDoc46($nomer,date("d.m.Y",$Fdate),$comment);
			break;
			case 48:
				$iSkladEx=$skladDocs->AddDoc48($nomer,date("d.m.Y",$Fdate),$comment,$SID,$iContract);
			break;
			case 39:
				$iSkladEx=$skladDocs->AddDoc39($nomer,date("d.m.Y",$Fdate),$comment,$SID,$iContract);
			break;
			case 80:
				$iSkladEx=$skladDocs->AddDoc80($nomer,date("d.m.Y",$Fdate),$comment,$SID,$iContract);
			break;	
		}
		$IND=(string)$iSkladEx->IND;
		$save=false;
		if($transport>0){
			$iSkladEx->TRANSPORT=1;
			$save=true;
		}
		if($driver!=NULL){
			$iSkladEx->VODITEL=$driver;
			$save=true;
		}
		if($dover!=NULL){
			$iSkladEx->DOVER=$dover;
			$save=true;
		}
		if($auto_nomer!=NULL){
			$iSkladEx->MACHINENUM=$auto_nomer;
			$save=true;
		}
		if($save){
			$iSkladEx->Save();
		}
}catch (Exception $ex) {
	die('{"success":false,"result":{},"errorMessage":"'.iconv('CP1251',"UTF-8",(string)$ex->getMessage()).'"}');
}
	$return=(object)array(
		'success'=>true,
		'result'=>$IND
	);
	echo json_encode($return);
?>