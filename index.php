<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	require_once("bin/init.php");
	header('Content-type: text/html; charset=utf-8');//
	//header('Content-type: text/html; charset=cp1251');//utf-8
	/////////////////////////////////////////////////////////////////////////////////////////////////////////nonce
	if(!isset($_GET['free'])||$config['debug']==false){
		$nonceFile='./nonce';
		$nonce = file_get_contents($nonceFile);
		$nonce = floatval($nonce);
		if(!isset($_GET['nonce'])||floatval($_GET['nonce'])<=$nonce)die('{"success":false,"result":{"error":"depricatedNonce"}}');//<=
		if($_GET['publicKey']!=$config['public_key'])die('{"success":false,"result":{"error":"wrongPublicKey"}}');
		$signData='{"action":"'.__URI.'","publicKey":"'.$_GET['publicKey'].'","nonce":'.$_GET['nonce'].'}';
		$signature=hash_hmac('sha256',$signData,$config['secret_key']); 
		if($signature!=$_GET['signature'])die('{"success":false,"result":{"error":"wrongSignature"}}');
		file_put_contents($nonceFile, $_GET['nonce'], LOCK_EX);
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////AsuVrk
	$ASUVRK = new COM("asu_vrk_com.ASUVRK") or die('{"success":false,"result":{"error":"oleObjectOpenError"}}');
	require_once("AsuVrkUser.php");
	$res=$ASUVRK->ConnectToBase($AsuVrkUser->host,$AsuVrkUser->user,$AsuVrkUser->password);
	if($res<>0)die('{"success":false,"result":{"error":"connectToAsuVrkError"}}');
	$ASUVRK->ShowDialogs=false;
	/*function shutdown(){
		global $ASUVRK;
	}
	register_shutdown_function('shutdown');*/
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$action=action::getAction($VALUES);
	$result=action::exec($action);
	if($result===false||$result==404){
		die('{"success":false,"result":{"error":"404"}}');
	}
	echo $result;
	
?>