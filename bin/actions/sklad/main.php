<?php
	global $ASUVRK;
	$FromDate=(isset($_GET['FromDate']))?strtotime(trim($_GET['FromDate'])):time();
	$ToDate=(isset($_GET['ToDate']))?strtotime(trim($_GET['ToDate'])):time();	
	$DocFilter='0,40,41,46,45,47,48,49,50,75,80,81,82,83,90,100';
	$HideClearDocs=0;
	$skladDocs=$ASUVRK->GetSkladExDocs($FromDate,date("d.m.Y",$ToDate),$DocFilter,$HideClearDocs);
	$Docs=[];
	for($i=0;$i<$skladDocs->Count;$i++){
		$doc=$skladDocs->GetDoc($i);
		$Docs[]=array(
			"IND"=>$doc->IND,
			"NOMER"=>$doc->DocNum, 
			"DocDate"=>$doc->DocDate,
			"DocType"=>$doc->DocType,
			"Fixed"=>$doc->Fixed ,
			"ECP"=>$doc->ECP,
			"VagonId"=>$doc->VagonId,
			"SId"=>$doc->SId,
			"VODITEL"=>$doc->VODITEL,
			"DOVER"=>$doc->DOVER,
			"MACHINENUM"=>$doc->MACHINENUM,
			"TRANSPORT"=>$doc->TRANSPORT,
			"DetailsCount"=>$doc->Count
		);
	}
	$return=(object)array(
		'success'=>true,
		'result'=>$Docs
	);
	echo json_encode($return);
?>