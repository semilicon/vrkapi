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
	$VagonComplekt=$iVagon->VagonComplekt();
	//echo '1';exit;
	$iVagonDetailsIN=$VagonComplekt->IN;
	$iVagonDetails=$VagonComplekt->OUT;
	
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
				$currents[$key]->Delete();
			}
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно удалить (неверную) деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	$in_PA=array();
	$in_TX=array();
	$in_list_PA=array();
	$in_list_TX=array();
	for($i=0;$i<$iVagonDetailsIN->PA_Count;$i++){
		$in=$iVagonDetailsIN->GetPA($i);
		$zng=$in->Zavod.'-'.$in->Nomer.'-'.$in->God;
		$in_PA[]=$in;
		$in_list_PA[]=$zng;
	}
	for($i=0;$i<$iVagonDetailsIN->TX_Count;$i++){
		$in=$iVagonDetailsIN->GetTX($i);
		$zng=$in->Zavod.'-'.$in->Nomer.'-'.$in->God;
		$in_TX[]=$in;
		$in_list_TX[]=$zng;
	}
	$out_PA=array();
	$out_TX=array();
	for($i=0;$i<$iVagonDetails->PA_Count;$i++)$out_PA[$i]=$iVagonDetails->GetPA($i);
	for($i=0;$i<$iVagonDetails->TX_Count;$i++)$out_TX[$i]=$iVagonDetails->GetTX($i);
	try{
		foreach($out_PA as $key=>$val){
			$zng=$out_PA[$key]->Zavod.'-'.$out_PA[$key]->Nomer.'-'.$out_PA[$key]->God;
			if (!in_array($zng, $list_PA))$out_PA[$key]->Delete();
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно удалить (неверную) деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	try{
		foreach($out_TX as $key=>$val){
			$zng=$out_TX[$key]->Zavod.'-'.$out_TX[$key]->Nomer.'-'.$out_TX[$key]->God;
			if (!in_array($zng, $list_TX))$out_TX[$key]->Delete();
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно удалить (неверную) деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	try{
		foreach($new_AS as $key=>$val){
			$hIND=$iVagonDetails->AddAS($val['z'],$val['n'],$val['g'],$val['m']);
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Установка автосцепки не удалась:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	//////////////////////////////////////////////////////////////////////////////////
	try{
		foreach($in_PA as $key=>$val){
			$zng=$in_PA[$key]->Zavod.'-'.$in_PA[$key]->Nomer.'-'.$in_PA[$key]->God;
			$id=$in_PA[$key]->GlobalDetalId;
			//echo $zng.'='.$id.'<br>';
			foreach($list_PA as $key1=>$val1){
				//echo $zng.'=='.$val1.'<br>';
				if($val1==$zng){
					//echo $zng.'=='.$val.'<br>';
					$exists=false;
					foreach($out_PA as $key2=>$val2){
						$id2=$out_PA[$key2]->GlobalDetalId;
						if($id==$id2)$exists=true;
					}
					
					if($exists==false){
						//echo 'add';
						$iVagonDetails->AddPAbyID($id);
					}
					unset($list_PA[$key]);
					unset($new_PA[$key]);
					break;
				}
			}
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно установить деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	try{
		foreach($new_PA as $key=>$val){
			$result=$iVagonDetails->AddPA($val['z'],$val['n'],$val['g'],$val['m']);
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно установить деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	//////////////////////////////////////////////////////////////////////////////////
	try{
		foreach($in_TX as $key=>$val){
			$zng=$in_TX[$key]->Zavod.'-'.$in_TX[$key]->Nomer.'-'.$in_TX[$key]->God;
			$id=$in_TX[$key]->GlobalDetalId;
			//echo $zng.'='.$id.'<br>';
			foreach($list_TX as $key1=>$val1){
				//echo $zng.'=='.$val1.'<br>';
				if($val1==$zng){
					//echo $zng.'=='.$val.'<br>';
					$exists=false;
					foreach($out_TX as $key2=>$val2){
						$id2=$out_TX[$key2]->GlobalDetalId;
						if($id==$id2)$exists=true;
					}
					
					if($exists==false){
						//echo 'add';
						$iVagonDetails->AddTXbyID($id);
					}
					unset($list_TX[$key]);
					unset($new_TX[$key]);
					break;
				}
			}
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно установить деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	try{
		foreach($new_TX as $key=>$val){
			$result=$iVagonDetails->AddTX($val['z'],$val['n'],$val['g']);
		}
	}catch(Exception $err){die('{"success":false,"result":{},"errorMessage":"Невозможно установить деталь:<br>'.str_replace("\n",'<br>',str_replace("\r",'<br>',str_replace("\r\n",'<br>',iconv('CP1251',"UTF-8",(string)$err->getMessage())))).'"}');}
	//////////////////////////////////////////////////////////////////////////////////

	echo '{"success":true,"result":{"details":"ok"}}';
?>