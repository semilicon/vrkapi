<?php
	global $ASUVRK;
	$result=(object)array(
		'version'=>(string)$ASUVRK->Version,
		'dllfile'=>(string)$ASUVRK->SelfDllFile
	);
	$return=(object)array(
		'success'=>true,
		'result'=>$result
	);
	echo json_encode($return);
?>