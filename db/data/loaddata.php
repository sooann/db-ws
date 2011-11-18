<?php

	require_once '../global.php';

	$intid = trim($_GET["id"]);
	
	if ($intid!="" && is_numeric($intid)) {
		
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","PAGECOMPLETE",now());
		$node->where = "webtracking_id = " . $intid;
		$db->update($node);
		
	}
	
?>