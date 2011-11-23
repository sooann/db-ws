<?php 	
	if (isset($WebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","SCRIPTLOAD",now());
		$node->where = "webtracking_id = " . $WebpageTrackingID;
		$db->update($node);
	}
?>	
<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<title><?php echo $strPageTitle; ?> - <?php echo $strServerName; ?></title>
	<meta charset="UTF-8">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- CSS Styles -->
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/colors.css">
	<link rel="stylesheet" href="../css/jquery.tipsy.css">
  <link rel="stylesheet" href="../css/jquery.dd.css">

	<!-- Google WebFonts -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
    
	<script src="../js/libs/modernizr-1.7.min.js"></script>
	
	<script language="javascript" src="../js/cordlife.js"></script>
	
	<?php echo $strHeadTags; ?>
	
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

<!-- Add class .fixed for fixed layout. You would need also edit CSS file for width -->
<body onload="pageload();" >

	<!-- Fixed Layout Wrapper -->
	<div class="fixed-wraper">

	<!-- Aside Block -->
	<section role="navigation">
		<!-- Header with logo and headline -->
		<header>
        	<!--
			<a href="/" title="Back to Homepage"></a>
			<h1>Lorem ipsum dolor sit amet!</h1>
            -->
            <div id="headCRM" >CRM</div>
            <div Id="headDBA" >Database Administrator</div>
		</header>
		
		<!-- User Info -->
		<section id="user-info">
        	<!--
			<img src="img/sample_user.png" alt="Sample User Avatar">
            -->
			<div>
				<a href="#" title="Account Settings and Profile Page"><?php echo $_SESSION['strUserName'] ?></a>
				<em>Administrator</em>
				<ul>
					<li><a class="button-link" href="#" title="Update Profile" rel="tooltip">Update Profile</a></li>
					<li><a class="button-link" href="#" title="Change Password" rel="tooltip">Change Password</a></li>
					<li><a class="button-link" href="logout.php" title="Logout" rel="tooltip">logout</a></li>
				</ul>
			</div>
		</section>
		<!-- /User Info -->
		
		<!-- Main Navigation -->
		<nav id="main-nav">
			<ul>
				<?php
					$showUserstmt =$db->query("select count(*) as intcount from USERS")->fetch();
					$intCountUser = $showUserstmt["intcount"];
				?>
				<li><a href="users.php" title="Manage Users" class="dashboard no-submenu">Users</a>
				<span class="counttip" title="You have <?php echo $intCountUser; ?> users"><?php echo $intCountUser; ?></span></li> 
				<!-- Use class .no-submenu to open link instead of a sub menu-->
				<!-- Use class .current to open submenu on page load -->

				<li>
          <div class="projects">
						<?php
							$showDBstmt =$db->query("select count(*) as intcount from DATABASES")->fetch();
							$intCountDB = $showDBstmt["intcount"];
							$showDBstmt = $db->query("select * from DATABASES");
						?>
            <select name="showDataBase" class="showDataBase" >
            	<option value="">Select a Database</option>
            	<option value="showALL">(Show All Databases)</option>
            	<?php
            		while ($showDBrow = $showDBstmt->fetch()) {
            			echo ('<option value="'.$showDBrow["DATABASE_ID"].'" ');
            			if ($_SESSION["showDATABASE_ID"] == $showDBrow["DATABASE_ID"] ) {
            				echo "selected";
            			}
            			echo " >".$showDBrow["LABEL"]."</option>";
            		} 
            	?>
            </select>
          </div>
          <span class="counttip" title="You have <?php echo $intCountDB; ?> Databases"><?php echo $intCountDB; ?></span>
				</li>
				<?php 
					if (is_numeric($_SESSION["showDATABASE_ID"])) {

						$paramArray = array($_SESSION["showDATABASE_ID"]);
						$showTablestmt = $db->query("select count(*) as intcount from TABLES where database_id = ?",NULL,NULL,NULL,$paramArray);
						$intCountTable = $showTablestmt["intcount"];
						
						$paramArray = array($_SESSION["showDATABASE_ID"]);
						$showTablestmt = $db->query("select * from TABLES where database_id = ?",NULL,NULL,NULL,$paramArray);
						
				?>
				
					<li class="current">
						<a href="" title="List of Tables" class="products">Tables</a>
						<span class="counttip" title="You have <?php echo $intCountTable; ?> Tables"><?php echo $intCountTable; ?></span>
						<ul class="current">
							<?php
            		while ($showTablerow = $showTablestmt->fetch()) {
            			echo ('<li><a href="" title="'.$showTablerow["TABLE_ID"].'">');
            			echo " >".$showTablerow["LABEL"]."</a></li>";
            		} 
            	?>
						</ul>
					</li>
				<?php } ?>
			</ul>
		</nav>
		<!-- /Main Navigation -->
	</section>
	<!-- /Aside Block -->
	
	<!-- Main Content -->
	<section role="main">
		
		<!-- Breadcumbs -->
		<ul id="breadcrumbs">
			<li><a href="#" title="Back to Homepage">Back to Home</a></li>
			<?php
				//build breadcrumb
				for ($i=0; $i<count($arrBreadCrumb); $i++) {
					echo '<li><a href="'.$arrBreadCrumb[$i]["url"].'">'.$arrBreadCrumb[$i]["name"].'</a></li>';
				}
			?>
			<li><?php echo $strPageTitle; ?></li>
		</ul>
		<!-- /Breadcumbs -->
		
        <article class="custom-block" >
        	<div class="article-container">
            	<header>
                	<h2><?php echo $strPageTitle; ?></h2>
                </header>
                <section>
                	<?php 
                		if ($strPageDescription!="") {
                			echo "<h5>".$strPageDescription."</h5>";
                		} 
                	?>
