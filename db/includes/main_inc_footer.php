									
	</body>
</html>
<?php 

	//Log Web Load Time
	if (isset($intWebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","HTMLTRANSFERED",now());
		$node->where = "webtracking_id = " . $intWebpageTrackingID;
		$db->update($node);
	}
	
	//Disconnect Data Connection
	$db->disconnect();

?>