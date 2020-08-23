<?php
	//add_wagon
	//http://83.174.201.138:1259/add_br_in/?free&IND=15800072187&details=[{"t":"nb","z":5,"n":19996,"g":1991},{"t":"nb","z":5,"n":19995,"g":1991}]
	global $ASUVRK;
	if(!isset($_GET['IND']))die('{"success":false,"result":{"error":"wrongIND"}}');
	if(!isset($_GET['details']))die('{"success":false,"result":{"error":"wrongDetails"}}');
	$IND=trim($_GET['IND']);
	$details=json_decode(trim($_GET['details']), true);
	//$details=[{"t":"nb","z":5,"n":19996,"g":1991,"m":3},{"t":"br","z":5,"n":19995,"g":1991}]
	if($details==NULL)die('{"success":false,"result":{"error":"wrongDetails"}}');
	$iVagon=$ASUVRK->GetVagonByIND($IND);
	$iVagonDetails=$iVagon->VagonComplekt()->OUT;
	//new details
	$new_NB=array();
	$new_BR=array();
	$list_NB=array();
	$list_BR=array();
	foreach($details as $key=>$val){
		if($val['t']=='nb'){
			$list_NB[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
			$new_NB[$val['z'].'-'.$val['n'].'-'.$val['g']]=$val;
		}else if($val['t']=='br'){
			$list_BR[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
			$new_BR[$val['z'].'-'.$val['n'].'-'.$val['g']]=$val;
		}
	}
	try{
		//get current details
		$currents=array();
		for($i=0;$i<$iVagonDetails->NB_Count;$i++)$currents[$i]=$iVagonDetails->GetNB($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_NB)) {
				unset($new_NB[$zng]);
			}else{
				$currents[$key]->Delete();
			}
		}
		$currents=array();
		for($i=0;$i<$iVagonDetails->BR_Count;$i++)$currents[$i]=$iVagonDetails->GetBR($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_BR)) {
				unset($new_BR[$zng]);
			}else{
				$currents[$key]->Delete();
			}
		}
		/////////////////////////////////////
		if(count($new_NB)==0&&count($new_BR)==0){
			echo '{"success":true,"result":{"details":"ok"}}';
			exit;
		}
		
		foreach($new_NB as $key=>$val){
			//echo $val['z'],'-',$val['n'],'-',$val['g'];
			$iVagonDetails->AddNB($val['z'],$val['n'],$val['g']);
		}
		//var_dump($new_NB);
		//exit;
		foreach($new_BR as $key=>$val){
			$iVagonDetails->AddBR($val['z'],$val['n'],$val['g']);
		}
		//$result=$iVagonDetails->AddNB(5,715867,2006);
	}catch(Exception $err){
		echo '{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}';
		exit;
	}
	echo '{"success":true,"result":{"details":"ok"}}';
?>