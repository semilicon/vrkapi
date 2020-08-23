<?php
	//add_wagon
	global $ASUVRK;
	$IND=(isset($_GET['docInd']))?intval(trim($_GET['docInd'])):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;docInd&quot;"}');
try{	
	$iSkladEx=$ASUVRK->GetSkladExDocByID($IND);
	if($iSkladEx==NULL)die('{"success":false,"result":{},"errorMessage":"Ошибка: Документ с указанным ИД не существует}');
	$iSkladEx->DeleteAllMaterial();
}catch (Exception $ex) {
	die('{"success":false,"result":{},"errorMessage":"'.iconv('CP1251',"UTF-8",(string)$ex->getMessage()).'"}');
}
	$return=(object)array(
		'success'=>true,
		'result'=>array("deleted"=>$iSkladEx->Count)
	);
	echo json_encode($return);
?>