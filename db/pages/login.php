<?php

	require_once "../global.php";
	require_once "../includes/password.php";

	$strUsername="";
	$strPassword="";
	$blnApplErr=false;

	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$blnApplErr    = False;
		$blnUsernmErr  = False;
		$blnPasswdErr  = False;
	
		$strUsername = Trim($_POST['strUsername']);
		$strPassword = Trim($_POST['strPassword']);
	
		if ($strUsername == "") {
			$blnApplErr   = True;
			$blnUsernmErr = True;
		}
	
		if ($strPassword == "") {
			$blnApplErr   = True;
			$blnPasswdErr = True;
		}
	
		If (!$blnApplErr) {
			// ##### Check username and password #####
	
			$stmt = $db->con->prepare("select * from USERS where EMAIL like ? and Active=1");
			$stmt->bindParam(1, $strUsername);
			$stmt->execute();
	
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	
				if (validate_password($strPassword,$row['PASSWORD'])) {
					//setting session
	
					$_SESSION['intUserURN']=$row["USER_ID"];
					$_SESSION['dtLastAccess']=time();
					$_SESSION['strUserName']=$row["NAME"];
					$_SESSION['strUserEmail']=$row["EMAIL"];
						
					$node = new sqlNode();
					$node->table = "LOGINLOG";
					$node->push("int","USER_ID",$row["USER_ID"]);
					$node->push("date","LOGINDATE",now());
					$LOGINLOG_ID = $db->insert($node);
	
					$_SESSION['intLoginLogId']=$LOGINLOG_ID;
						
					If ($strUrl != "") {
						redirect(urldecode($strUrl));
	
					} else {
						//header('Location: default.php');
						redirect('index.php');
					}
				} else {
					$blnApplErr=true;
					$blnlogininv=true;
				}
			}	else {
				$blnApplErr=true;
				$blnlogininv=true;
			}
		}
	}
?>

<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<title>Login | <?php echo $strServerName; ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!--
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	-->
	
	<!-- CSS Styles -->
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/colors.css">
	<link rel="stylesheet" href="../css/jquery.tipsy.css">
	
	<!-- Google WebFonts -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
	
	<script src="../js/libs/modernizr-1.7.min.js"></script>
	
	<script src="../js/jquery/jquery-1.5.1.min.js"></script>
	<script src="../js/libs/selectivizr.js"></script>
	<script src="../js/jquery/jquery.tipsy.js"></script>
	<script src="../js/login.js"></script>
	
</head>
<body class="login">
	<section role="main">
	
    	<!--
		<a href="/" title="Back to Homepage"></a>
		-->
        
        <div id="headCRM" >CRM</div>
        <div Id="headDBA" >Database<br />Administrator</div>
        
		<!-- Login box -->
		<article id="login-box">
		
			<div class="article-container">
				
				<!-- Notification -->
				<?php 
					if ($blnApplErr && $blnUsernmErr) {
						addErrorNotification("Please enter your Email.");
					}
					if ($blnApplErr && $blnPasswdErr) {
						addErrorNotification("Please enter your password.");
					} else if ( $blnApplErr && $blnlogininv ) {
						addErrorNotification("Invalid username and password combination.");
					}
				?>
				<!-- /Notification -->
			
				<form action="<?php echo $strPostScript; ?>" method="POST" >
					<fieldset>
						<dl>
							<dt>
								<label>Login</label>
							</dt>
							<dd>
								<input type="text" class="large " name="strUsername" size="30" maxlength="250" value="<?php echo $strUsername; ?>" >
							</dd>
							<dt>
								<label>Password</label>
							</dt>
							<dd>
								<input type="password" class="large " name="strPassword" size="30" maxlength="250" value="<?php echo "$strPassword"; ?>" >
							</dd>
						</dl>
					</fieldset>
					<input class="button right" type="submit" Name="submit" value="Log in">
				</form>
			
			</div>
		
		</article>
		<!-- /Login box -->
        <!--
		<ul class="login-links">
			<li><a href="#">Lost password?</a></li>
			<li><a href="#">Wiki</a></li>
			<li><a href="#">Back to page</a></li>
		</ul>
		-->
	</section>

	<!-- JS Libs at the end for faster loading -->
	<!-- 
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="../js/jquery/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
	-->
		
</body>
</html>