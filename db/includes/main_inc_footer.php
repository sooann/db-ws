									
	</body>
</html>
<?php 

	//Log Web Load Time
	if (isset($WebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","HTMLTRANSFERED",now());
		$node->where = "webtracking_id = " . $WebpageTrackingID;
		$db->update($node);
	}
	
	//Disconnect Data Connection
	$db->disconnect();

?>