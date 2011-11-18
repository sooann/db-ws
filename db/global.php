<?php

	require_once 'function.php';
	require_once 'includes/pdo_database.class.php';
	
	//configuring db
	$db = new wArLeY_DBMS("sqlsrv", "192.168.0.103", "CORE", "crmuser", "cordlife11!!", "");
	if($db==false){
		echo "Error: Cant connect to database.";
	} 
	global $db;

	//Global Variables
	$strServerName= "DB Support System";

	session_start();
	session_cache_expire(30);
	
	global $session_timeout;
	$session_timeout = 30;
	
	date_default_timezone_set("Asia/Singapore");
	
	$strPostScript = $_SERVER['SCRIPT_NAME'];

	//log webpage
	$node = new sqlNode();
	$node->table = "webpagetracking";
	if (isset($_SESSION["intUserURN"])) {
		$node->push("int","user_id",$_SESSION["intUserURN"]);
	}
	$node->push("text","URL",$_SERVER['REQUEST_URI']);
	$node->push("text","ScriptName",$_SERVER['SCRIPT_NAME']);
	$node->push("text","IPAddress",$_SERVER['REMOTE_ADDR']);
	$node->push("text","Browser",$_SERVER['HTTP_USER_AGENT']);
	$node->push("text","RequestMethod",$_SERVER['REQUEST_METHOD']);
	$node->push("date","CreatedDate",now());
	$intWebpageTrackingID = $db->insert($node);
	
	global $intWebpageTrackingID;
	
?>