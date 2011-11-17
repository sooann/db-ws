
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $PageTitle; ?> - <?php echo $ServerName; ?></title>
		<?php echo $headtags; ?>
		<link href="../css/main.css" rel="stylesheet" type="text/css" />
		<script language="javascript" src="../js/cordlife.js"></script>
		<script language="javascript" src="../js/cookie.js"></script>
		<script type="text/javaScript">
			
			function pageload() 
			{
				
				<?php echo $evtBodyonload; ?>
				
				<?php if (isset($WebpageTrackingID)) { ?>

					doAJAX("../data/loaddata.php?id=<?php echo $WebpageTrackingID; ?>", "");
					
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
										<td valign="bottom" width="280" align="center" height="60" id="headerrow1cell1" ><img src="../images/logo2.jpg" width="213" height="46" /></td>
										<td valign="bottom" class="headerrow1cell2" align="right">
											<b>Logged in as: <?php echo $_SESSION['strUserName'] ?></b>&nbsp;&nbsp;|
											&nbsp;&nbsp;<a href="../pages/logout.php" class="headerhref" >Logout</a>
										</td>
									</tr>
									<tr id="headerrow2" height="29" bgcolor="#ffffff" >
										<td colspan="headerrow2cell1" valign="bottom" nowrap >
											<table cellspacing="0" cellpadding="0" border="0" id="topmenu" >
												<tr>
													<td>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="3" id="headerdivider" ></td>
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
							<td width="180" class="leftmenubg" align="center" valign="top" id="tdLeftNaviTable">
								<table width="180" cellpadding="4" cellspacing="0" border="0" ud="leftnavitable">
									<tr>
										<td>
											<br />
											<b>Database:</b>
											<select name="selDatabase" id="selDatabase">
												<option value="">Please Select Database</option>
												
											</select>
										</td>
									</tr>
								</table>

							</td>
							<td id="contentarea" valign="top" height="400">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" >
									<tr><td><i><b>You are in: </b></i> <b><?php echo $BodyTitle; ?></b></td></tr>
									<tr><td height=5"></td></tr>
									<tr><td><span class="pagetitle"><?php echo $BodyTitle; ?></span></td></tr>
									<tr><td><?php echo $PageDescription; ?></td></tr>
									<tr><td height="8"></td></tr>
									<tr>
										<td>
											