<?php
	global $ASUVRK;
	$docInd=(isset($_GET['docInd']))?intval(trim($_GET['docInd'])):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;docInd&quot;"}');
	if(!isset($_GET['details']))die('{"success":false,"result":{},"errorMessage":"wrongDetails"}');
	$details=json_decode(trim($_GET['details']), true);
	if($details==NULL)die('{"success":false,"result":{},"errorMessage":"wrongDetails"}');
	//
try{
	$iSkladEx=$ASUVRK->GetSkladExDocByID($docInd);
	if($iSkladEx==NULL)die('{"success":false,"result":{},"errorMessage":"Ошибка: Документ с указанным ИД не существует}');
	$ndList=array();
	$new=array();
	foreach($details as $keyI=>$val){
		if(!isset($val['t'])||($val['t']!='br'&&$val['t']!='nb'&&$val['t']!='kp'&&$val['t']!='as'&&$val['t']!='pa'&&$val['t']!='tx'))continue;
		if(!isset($val['z'])||!isset($val['n'])||!isset($val['g']))continue;
		$key=$val['t'].'='.$val['z'].'-'.$val['n'].'-'.$val['g'];
		$ndList[]=$key;
		$new[$key]=$val;
	}
	$exists=0;
	$added=0;
	$deleted=0;
	for($i=0;$i<$iSkladEx->Count;$i++){
		$detail=$iSkladEx->GetMaterial($i);
		$type='';
		switch($detail->MaterialId){
			case 100000:$type='br';break;
			case 100001:$type='nb';break;
			case 100002:$type='kp';break;
			case 100003:$type='as';break;
			case 100004:$type='pa';break;
			case 100006:$type='tx';break;
			default:continue;
		}
		$key=$type.'='.$detail->Zavod.'-'.$detail->Nomer.'-'.$detail->God;
		if(in_array($key,$ndList)){
			unset($new[$key]);
			$exists++;
		}else{
			$iSkladEx->DeleteMaterial($i);
			$deleted++;
		}
	}
	foreach($new as $key=>$val){
		if(!isset($val['p']))$val['p']=0;
		if(!isset($val['comment']))$val['comment']='';
		switch($val['t']){
			case 'br':
				$iSkladEx->AddBR($val['z'],$val['n'],$val['g'],$val['p'],$val['comment']);
			break;
			case 'nb':
				$iSkladEx->AddNB($val['z'],$val['n'],$val['g'],$val['p'],$val['comment']);
			break;
			case 'kp':
				if(!isset($val['stal']))$val['stal']=1;
				if(!isset($val['obod1'])||!isset($val['obod2'])||!isset($val['greben1'])||!isset($val['greben2'])||!isset($val['prokat1'])||!isset($val['prokat2'])||!isset($val['typKp']))continue;
				$iSkladEx->AddKP($val['z'],$val['n'],$val['g'],$val['p'],$val['comment'],$val['stal'],$val['obod1'],$val['obod2'],$val['greben1'],$val['greben2'],$val['prokat1'],$val['prokat2'],$val['typKp']);
			break;
			case 'as':
				$iSkladEx->AddAS($val['z'],$val['n'],$val['g'],$val['p'],$val['comment'],$val['m']);
			break;
			case 'pa':
				$iSkladEx->AddPA($val['z'],$val['n'],$val['g'],$val['p'],$val['comment'],$val['m']);
			break;
			case 'tx':
				$iSkladEx->AddTX($val['z'],$val['n'],$val['g'],$val['p'],$val['comment']);
			break;
		}
		$added++;
	}
}catch (Exception $ex) {
	die('{"success":false,"result":{},"errorMessage":"'.iconv('CP1251',"UTF-8",(string)$ex->getMessage()).'"}');
}
	$return=(object)array(
		'success'=>true,
		'result'=>array(
			"exists"=>$exists,
			"added"=>$added,
			"deleted"=>$deleted
		)
	);
	echo json_encode($return);
?>