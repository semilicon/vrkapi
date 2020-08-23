<?php
	//add_wagon
	
	global $ASUVRK;
	if(!isset($_GET['IND']))die('{"success":false,"result":{"error":"wrongIND"}}');
	if(!isset($_GET['details']))die('{"success":false,"result":{"error":"wrongDetails"}}');
	$IND=trim($_GET['IND']);
	$details=json_decode(trim($_GET['details']), true);
	//[{"z":5,"n":999993,"g":1966,"stal":1,"typKp":2,"obod1":62,"obod2":61,"greben1":28,"greben2":27,"prokat1":2,"prokat2":2},{"z":5,"n":999991,"g":1966,"stal":1,"typKp":1,"obod1":70,"obod2":70,"greben1":28,"greben2":27,"prokat1":2,"prokat2":2},{"z":5,"n":999994,"g":1966,"stal":1,"typKp":1,"obod1":56,"obod2":56,"greben1":28,"greben2":28,"prokat1":2,"prokat2":2},{"z":5,"n":999992,"g":1966,"stal":1,"typKp":2,"obod1":66,"obod2":65,"greben1":29,"greben2":29,"prokat1":2,"prokat2":2}]
	if($details==NULL)die('{"success":false,"result":{"error":"wrongDetails"}}');
	$iVagon=$ASUVRK->GetVagonByIND($IND);
	$iVagonDetails=$iVagon->VagonComplekt()->IN;
	//new details
	$new_KP=array();
	$list_KP=array();
	foreach($details as $key=>$val){
		$list_KP[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
		$new_KP[$val['z'].'-'.$val['n'].'-'.$val['g']]=$val;
	}
	try{
		//get current details
		$currents=array();
		for($i=0;$i<$iVagonDetails->KP_Count;$i++)$currents[$i]=$iVagonDetails->GetKP($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_KP)) {
				unset($new_KP[$zng]);
			}else{
				$currents[$key]->Delete();
			}
		}
		/////////////////////////////////////
		if(count($new_KP)==0){
			echo '{"success":true,"result":{"details":"ok"}}';
			exit;
		}
		foreach($new_KP as $key=>$val){
			//echo $val['z'],',',$val['n'],',',$val['g'],',',$val['stal'],',',$val['typKp'],',',$val['obod1'],',',$val['obod2'],',',$val['greben1'],',',$val['greben2'],',',$val['prokat1'],',',$val['prokat2'],'<br>';
			$hIND=$iVagonDetails->AddKP($val['z'],$val['n'],$val['g'],$val['stal'],$val['typKp'],$val['obod1'],$val['obod2'],$val['greben1'],$val['greben2'],$val['prokat1'],$val['prokat2']);
		}
	}catch(Exception $err){
		echo '{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}';
		exit;
	}
	echo '{"success":true,"result":{"details":"ok"}}';
?>