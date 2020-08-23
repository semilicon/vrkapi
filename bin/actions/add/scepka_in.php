<?php
	//add_wagon
	//http://83.174.201.138:1259/add_br_in/?free&IND=15800072187&details=[{"t":"nb","z":5,"n":19996,"g":1991},{"t":"nb","z":5,"n":19995,"g":1991}]
	global $ASUVRK;
	if(!isset($_GET['IND']))die('{"success":false,"result":{},"errorMessage":"wrongIND"}');
	if(!isset($_GET['details']))die('{"success":false,"result":{},"errorMessage":"wrongDetails"}');
	$IND=trim($_GET['IND']);
	$details=json_decode(trim($_GET['details']), true);
	//$details=[{"t":"nb","z":5,"n":19996,"g":1991,"m":3},{"t":"br","z":5,"n":19995,"g":1991}]
	if($details==NULL)die('{"success":false,"result":{},"errorMessage":"wrongDetails"}');
	$iVagon=$ASUVRK->GetVagonByIND($IND);
	$iVagonDetails=$iVagon->VagonComplekt()->IN;
	
	//new details
	$new_AS=array();
	$new_PA=array();
	$new_TX=array();
	$list_AS=array();
	$list_PA=array();
	$list_TX=array();
	foreach($details as $key=>$val){
		if($val['t']=='as'){
			$list_AS[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
			$new_AS[$val['z'].'-'.$val['n'].'-'.$val['g']]=$val;
		}else if($val['t']=='pa'){
			$list_PA[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
			$new_PA[]=$val;
		}else if($val['t']=='tx'){
			$list_TX[]=$val['z'].'-'.$val['n'].'-'.$val['g'];
			$new_TX[]=$val;
		}
	}
	try{
		//get current details
		$currents=array();
		for($i=0;$i<$iVagonDetails->AS_Count;$i++)$currents[$i]=$iVagonDetails->GetAS($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_AS)){
				unset($new_AS[$zng]);
			}else{
				//echo $zng.'<br>';
				$currents[$key]->Delete();
			}
		}
		$currents=array();
		for($i=0;$i<$iVagonDetails->PA_Count;$i++)$currents[$i]=$iVagonDetails->GetPA($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_PA)){
				foreach($list_PA as $key1=>$val1){
					if($val1==$zng){
						unset($list_PA[$key1]);
						unset($new_PA[$key1]);
						break;
					}
				}
			}else{
				//echo $zng.'<br>';
				$currents[$key]->Delete();
			}
		}
		$currents=array();
		for($i=0;$i<$iVagonDetails->TX_Count;$i++)$currents[$i]=$iVagonDetails->GetTX($i);
		foreach($currents as $key=>$val){
			$zng=$currents[$key]->Zavod.'-'.$currents[$key]->Nomer.'-'.$currents[$key]->God;
			if (in_array($zng, $list_TX)){
				foreach($list_TX as $key1=>$val1){
					if($val1==$zng){
						unset($list_TX[$key1]);
						unset($new_TX[$key1]);
						break;
					}
				}
			}else{
				//echo $zng.'<br>';
				$currents[$key]->Delete();
			}
		}
		//var_dump($new_TX);
		//echo '1';exit;
		//array_shift($new_AS);
		foreach($new_AS as $key=>$val){
			//echo $val['z'],'-',$val['n'],'-',$val['g'],'-',$val['m'];
			$hIND=$iVagonDetails->AddAS($val['z'],$val['n'],$val['g'],$val['m']);
		}
		foreach($new_PA as $key=>$val){
			$result=$iVagonDetails->AddPA($val['z'],$val['n'],$val['g'],$val['m']);
		}
		
		foreach($new_TX as $key=>$val){
			$result=$iVagonDetails->AddTX($val['z'],$val['n'],$val['g']);
		}
	}catch(Exception $err){
		echo '{"success":false,"result":{},"errorMessage":"'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}';
		exit;
	}
	echo '{"success":true,"result":{"details":"ok"}}';
?>