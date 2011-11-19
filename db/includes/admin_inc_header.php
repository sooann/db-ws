<?php 	
	if (isset($WebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","SCRIPTLOAD",now());
		$node->where = "webtracking_id = " . $WebpageTrackingID;
		$db->update($node);
	}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $strPageTitle; ?> - <?php echo $strServerName; ?></title>
		<?php echo $strHeadTags; ?>
		<link href="../css/main.css" rel="stylesheet" type="text/css" />
		<script language="javascript" src="../js/cordlife.js"></script>
		<script language="javascript" src="../js/cookie.js"></script>
		<script type="text/javaScript">
			
			function pageload() 
			{
				
				<?php echo $evtBodyonload; ?>
				
				<?php if (isset($intWebpageTrackingID)) { ?>

					doAJAX("../data/loaddata.php?id=<?php echo $intWebpageTrackingID; ?>", "");
					
				<?php } ?>
		  }
			
		</script>
	</head>
	<body onload="pageload();">
		<script type="text/javascript" src="../js/wz_tooltip.js"></script>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainheaderarea">
						<tr>
							<td colspan="3" id="maincontentrow1">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" id="headercontentarea" >
									<tr id="headerrow1">
										<td valign="middle" align="left" height="60" id="headerrow1cell1" >
											<h1>CRM Database Administrator</h1>
										</td>
										<td valign="bottom" id="headerrow1cell2" align="right">
										</td>
									</tr>
									<tr>
										<td colspan="3" id="headerdivider" style="border-bottom:1px solid #999999" ></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" id="maincontentarea" >
						<tr height="400">
							<td width="180" align="center" valign="top" id="tdLeftNaviTable">
								<table width="180" cellpadding="4" cellspacing="0" border="0" id="leftnavitable">
									<tr>
										<td>
											<b>Logged in as: <?php echo $_SESSION['strUserName'] ?></b><br/><br/>
											<a href="../pages/logout.php" >Click here to Logout</a>
										</td>
									</tr>
									<tr>
										<td>
											<b>Database:</b>
											<?php
												$showDBstmt = $db->query("select * from DATABASES");
											?>
											<select name="selDatabase" id="selDatabase" onchange="" >
												<option value="">Please Select Database</option>
												<option value="showAll">&lt;&lt;Show All&gt;&gt;</Show></option>
												<?php
													while ($showDBrow = $showDBstmt->fetch()) {
														echo ('<option value="'.$showDBrow["DATABASE_ID"].'" ');
														if ($_SESSION["showDATABASE_ID"] == $showDBrow["DATABASE_ID"] ) {
															echo "selected";
														}
														echo " >".$showDBrow["LABEL"]." (".$showDBrow["NAME"].")</option>";
													} 
												?>	
											</select>
										</td>
									</tr>
									<tr>
										<td><a href="users.php" >Manage Users</a></td>
									</tr>
								</table>
							</td>
							<td id="contentarea" valign="top" height="400">
								<table width="100%" border="0" cellspacing="0" cellpadding="4" >
									<tr><td><i><b>You are in: </b></i> <b><?php echo $strBodyTitle; ?></b></td></tr>
									<tr><td height=5"></td></tr>
									<tr><td><span id="pagetitle"><?php echo $strBodyTitle; ?></span></td></tr>
									<tr><td><?php echo $strPageDescription; ?></td></tr>
									<tr><td height="8"></td></tr>
									<tr>
										<td>
											