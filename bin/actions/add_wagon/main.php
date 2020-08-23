<?php
	//add_wagon
	global $ASUVRK;
	//idn,contract,repairType,wagonType,wagonBuildDate,repairStartDate,repairEndDate,modelId
	//buildNumber,countryCode
	//mileageIn
	if(!isset($_GET['idn'])||strlen($_GET['idn'])!=8||!is_numeric($_GET['idn']))die('{"success":false,"result":{"error":"wrongIDN"}}');
	if(!isset($_GET['contract'])||!is_numeric($_GET['contract']))die('{"success":false,"result":{"error":"wrongcontract"}}');
	if(!isset($_GET['model'])||!is_numeric($_GET['model']))die('{"success":false,"result":{"error":"wrongModel"}}');
	$contracts=$ASUVRK->GetContracts(intval($_GET['contract']),-1,-1,-1);//158008541
	$iContract=$contracts->GetContract(0);
	$iModel=$ASUVRK->GetModelInfo(intval($_GET['model']));//71
	$iVagons=$ASUVRK->GetVagons(intval($_GET['idn']),date('Y-m-d h:i:s',time()-2678400),'2100-01-01 00:00:00',1,0);//,'2000-01-01 00:00:00','2100-01-01 00:00:00'
	//var_dump($iVagons->Count);
	if($iVagons->Count>0){
		$iVagon=$iVagons->GetVagon($iVagons->Count-1);
		if($iVagon->Fixed!=0)$iVagon=false;
	}else{$iVagon=false;}
	if($iVagons->Count==0||$iVagon===false){
		try{
			$AddVagonIDN=$ASUVRK->AddVagon(intval($_GET['idn']),intval($_GET['repairType']),intval($_GET['wagonType']),$_GET['wagonBuildDate'],$iContract,$_GET['startDate'],$_GET['endDate'],$iModel);
		}
		catch (Exception $ex) {
			die('{"success":false,"result":{},"errorMessage":"'.iconv('CP1251',"UTF-8",(string)$ex->getMessage()).'"}');
		}
		$AddVagonIDN=(string)$AddVagonIDN;
		if(intval($AddVagonIDN)>0){
			$iVagon=$ASUVRK->GetVagonByIND($AddVagonIDN);
			$iVagon->IsMasterDone=0;
			if(isset($_GET['mileageIn'])){
				$iVagon->Mileage1=intval($_GET['mileageIn']);
			}
			$iVagon->Save();
		}else{
			die('{"success":false,"result":{},"errorMessage":"addVagonError"}');
		}
	}else{
		$AddVagonIDN=$iVagon->IND;
	}
	$iVU4M=$iVagon->GetVU4M();
	try{
		if($iVU4M->GOS_SOBSTVENNIK==0){
			$iVU4M->ZAVOD=0;
			$iVU4M->NOMER_TYP=0;
			$iVU4M->VOSDUH=0;
			$iVU4M->AUTORPEREDACH=0;
			$iVU4M->RPEREDACH = 0;
			$iVU4M->BUFFERS = 0;
			$iVU4M->MANUAL_BRAKE = 0;
			$iVU4M->UBALKI = 0;
			$iVU4M->AUTOREG=2;
			$iVU4M->GOS_SOBSTVENNIK=20;
			if(isset($_GET['countryCode']))$iVU4M->GOS_SOBSTVENNIK=intval(trim($_GET['countryCode']));
			if($iVU4M->PRIPISKA_ID==0)$iVU4M->PRIPISKA_ID=-1;
			if(isset($_GET['buildNumber'])&&trim($_GET['buildNumber'])!=''&&trim($_GET['buildNumber'])!=0)$iVU4M->ZAVOD_NOMER=(string)trim($_GET['buildNumber']);
			$iVU4M->Save();
		}else{
			$do=false;
			if($iVU4M->PRIPISKA_ID==0){$iVU4M->PRIPISKA_ID=-1;$do=true;}
			if(isset($_GET['buildNumber'])&&trim($_GET['buildNumber'])!=''&&trim($_GET['buildNumber'])!=0&&$iVU4M->ZAVOD_NOMER!=(string)trim($_GET['buildNumber'])){
				$iVU4M->ZAVOD_NOMER=(string)trim($_GET['buildNumber']);
				$do=true;
			}
			if($do){
				$iVU4M->ZAVOD=0;
				$iVU4M->NOMER_TYP=0;
				$iVU4M->VOSDUH=0;
				$iVU4M->AUTORPEREDACH=0;
				$iVU4M->RPEREDACH = 0;
				$iVU4M->BUFFERS = 0;
				$iVU4M->MANUAL_BRAKE = 0;
				$iVU4M->UBALKI = 0;
				$iVU4M->AUTOREG=2;
			}
			if($do)$iVU4M->Save();
		}
	}
	catch (Exception $err) {
		die('{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');
	}
	echo '{"success":true,"result":{"IND":'.$AddVagonIDN.'}}';
	
?>