<?php

	require_once "../global.php";
	require_once "../includes/password.php";

	$strPageTitle="Admin Login";
	$strBodyTitle="Admin Login";
	$strPageDescription="";
	
	$strHeadTags="";
	$evtBodyonload="";

	$strUrl = Trim($_GET["url"]);
	If (is_null ($strUrl) || empty($strUrl)) {
		$strUrl = "";
	}

	$strPostScript = $strPostScript . "?url=" . URLEncode($strUrl);
	
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

	require_once '../includes/main_inc_header.php';
?>
		<form method="post" action="<?php echo $strPostScript; ?>">
			<table width="80%" border="1" align=center cellspacing="0" cellpadding="3" >
				<tr>
              <td colspan="2" class="navheadgreen">Admin Login</td>
                </tr>
                <tr>
                       <td colspan="2" width="50%" valign="top" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" class="subtable">
                                 <tr class="cellcolor">
                                     <td width="30%" align="right"><font color="#FF0000"><b>*</b></font>&nbsp;<b>Email:&nbsp;</b></td>
                                     <td width="60%"><input type="text" name="strUsername" size="30" maxlength="250" value="<?php echo $strUsername; ?>">
                                     <?php
                                       		if ($blnApplErr && $blnUsernmErr) {
                          			    echo '<img src="../images/exclaim_mark.gif" height="16" width="16">';
            		                     	    echo '<br><span class="errorText">Error: Please enter your Email.</span>';
            		                        }
            		                        ?>
                                     </td>
                                 </tr>
                                 <tr>
                                      <td width="30%" align="right"><font color="#FF0000"><b>*</b></font>&nbsp;<b>Password:&nbsp;</b></td>
                                      <td width="60%"><input type="password" name="strPassword" size="30" maxlength="250" value="<?php echo "$strPassword"; ?>">
                                           <?php
                                       		if ($blnApplErr && $blnPasswdErr) {
                          			    echo '<img src="../images/exclaim_mark.gif" height="16" width="16">';
            		                     	    echo '<br><span class="errorText">Error: Please enter your password.</span>';
            		                        } else if ( $blnApplErr && $blnlogininv ) {
            			                    echo '<img src="../images/exclaim_mark.gif" height="16" width="16">';
            		                            echo '<br><span class="errorText">Invalid username and password combination. Please try again.</span>';
            		                        }
            	                           ?>
                                     </td>
                                 </tr>
                                 <tr>
		                      <td colspan="2" height="10"></td>
	                         </tr>
                                 <tr class="cellcolor">
		                      <td colspan="2" align="center"><input class="inputButton" type="submit" Name="submit" value=" Login "></td>
	                         </tr>
                            </table>
                      </td>
                 </tr>
			</table>
		</form>

<?php require_once '../includes/main_inc_footer.php'; ?>


