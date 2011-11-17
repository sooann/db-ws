<?php

	require_once "../global.php";
	
	
	if (isset($_SESSION['intLoginLogId'])) {
		
		$node = new sqlNode();
		$node->table = "LOGINLOG";
		$node->push("date","LOGOUTDATE",now());
		$node->where = "loginlog_id = " . $_SESSION['intLoginLogId'];
		$db->update($node);

	}
	
	session_unset();
	session_destroy();
	session_regenerate_id();
	
	redirect("login.php");
	
?>