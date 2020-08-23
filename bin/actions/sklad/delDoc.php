<?php
	//add_wagon
	global $ASUVRK;
	$IND=(isset($_GET['docInd'])&&intval(trim($_GET['docInd']))>0)?intval(trim($_GET['docInd'])):die('{"success":false,"result":{},"errorMessage":"Ошибка: Незадано значение &quot;docInd&quot;"}');
try{	
	$iSkladEx=$ASUVRK->GetSkladExDocByID($IND);
	if($iSkladEx==NULL)die('{"success":false,"result":{},"errorMessage":"Ошибка: Документ с указанным ИД не существует}');
	//only today dosc
	$FromDate=date("d.m.Y");
	$ToDate=date("d.m.Y");
	$DocFilter='0';
	$HideClearDocs=1;
	$skladDocs=$ASUVRK->GetSkladExDocs($FromDate,$ToDate,$DocFilter,$HideClearDocs);
	$skladDocs->DeleteDoc($IND);
}catch (Exception $ex) {
	die('{"success":false,"result":{},"errorMessage":"'.iconv('CP1251',"UTF-8",(string)$ex->getMessage()).'"}');
}
	$return=(object)array(
		'success'=>true,
		'result'=>array("deleted"=>1)
	);
	echo json_encode($return);
?>