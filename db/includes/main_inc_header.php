
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
		
											