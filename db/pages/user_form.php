<?php 

	require_once "../global.php";
	require_once "../security_config.php";
	require_once "../includes/password.php";
	
	$strDisplayTerm = "User";
	$strDisplayTermPural = "Users";
	$strMainTable= "USERS";
	$strMainTableId= "user_id";
	
	$headtags="";
	$headtags .= '<link rel="stylesheet" type="text/css" media="all" href="../js/jscalendar-1.0/calendar-win2k-cold-1.css" title="win2k-cold-1" />';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/calendar.js"></script>';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/lang/calendar-en.js"></script>';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/calendar-setup.js"></script>';	
	
	$strHeadTags = '<link rel="stylesheet" href="../css/postform.css">';
	
	$arrBreadCrumb = array(array("url"=>"users.php","name"=>"Users"));
	
	$strAction = Trim($_GET["a"]);
  $intID     = Trim($_GET["id"]);
  
  $strSubmitted = $_POST["strSubmitted"];
  
  If ($intID == "" || !is_numeric($intID)) { $intID = 0; }
	
	switch ($strAction) {
		case "":
			$strBodyTitle="New ".$strDisplayTerm;
			$strPageTitle = "New ".$strDisplayTerm;
			$strPageDescription = "Create a new ".$strDisplayTerm.". Click on the <b>Save</b> button to finalize the new ".$strDisplayTerm.".";
			break;
		case "edit":
			$strBodyTitle="Update Staff";
			$strPageTitle = "Update Staff";
			$strPageDescription = "Update the details of this ".$strDisplayTerm.". Click on the <b>Save</b> button to finalize your changes.";
			break;
		case "view":
			$strBodyTitle="View Staff";
			$strPageTitle = "View Staff";
			$strPageDescription = "View the details of this ".$strDisplayTerm.". Click on the <b>Update</b> Button to edit the details of this ".$strDisplayTerm.".";
			break;
	}
   
  
  If ($strSubmitted == "") {
  	if ($strAction=="") {
  		$strFullName="";
  		$strEmail="";
  		$strPassword="";
  		
  	}	elseif ($strAction=="edit" || $strAction=="view") {
  		
  		$SQLQuery="select " . $strMainTable . ".*, table1.Name as CreatedByName, table2.Name as ModifiedByName from " . $strMainTable . " left outer join USERS as table1 on " . $strMainTable . ".createdby = table1.user_id left outer join USERS as table2 on " . $strMainTable . ".ModifiedBy = table2.user_id where " . $strMainTable . "." . $strMainTableId . "= ?";
  		$row = $db->query($SQLQuery,NULL,NULL,NULL,array($intID))->fetch();
  		
  		if (is_array($row)) {
  			
  			$strName = $row['NAME'];
				$strEmail = $row['EMAIL'];
				
				$dtCreatedDate = $row['CREATEDDATE'];
				$dtModifiedDate = $row['MODIFIEDDATE'];
				$strCreatedByName = $row['CreatedByName'];
				$strModifiedByName = $row['ModifiedByName'];
				
				If ($dtCreatedDate != "") {
					If (Trim($strCreatedByName) != "") {
						$strCreatedByName = $strCreatedByName . ", " . $dtCreatedDate;
					} else {
						$strCreatedByName = "System, " . $dtCreatedDate;
					}
				} else {
					$strCreatedByName = "System";
				}
				
				If ($dtModifiedDate != "") {
					If (Trim($strModifiedByName) != "") {
						$strModifiedByName = $strModifiedByName . ", " . $dtModifiedDate;
					} else {
						$strModifiedByName = "System, " . $dtModifiedDate;
					}
				} else {
					$strModifiedByName = "-";
				}
				
  		} else {
  			echo "Record not found";
  			exit();
  		}
  		
  	}
  	
  	If ($strAction == "edit") {
  		$strPostScript  = $strPostScript . "?a=edit&id=" . $intID;
		} ElseIf ($strAction == "view") {
			$strSubmitted   = "view";
			$strPostScript  = $strPostScript . "?a=edit&id=" . $intID;
		}
		
  } else {
  	
  	If ($strAction == "edit" ||  $strAction == "") {
  		$strName = trim($_POST['strName']);
  		$strEmail = trim($_POST['strEmail']);
  		
  		$strPassword = trim($_POST['strPassword']);
  		$strConPassword = trim($_POST['strConPassword']);	
  		
  		if ($strName=="") {
  			$blnApplErr=true;
  			$blnName=true;	
  		}
  		
  		if ($strAction=="") {
  			if ($strEmail=="") {
	  			$blnApplErr=true;
	  			$blnEmailEmpty=true;	
	  		} else {
	  			if (isEmailValid($strEmail)==0) {
		  			$blnApplErr=true;
		  			$blnEmailinv=true;	
		  		} else {
		  			
		  			$SQLQuery="select * from ".$strMainTable." where EMAIL like ?";
		  			if (is_array($db->query($SQLQuery,NULL,NULL,NULL,array($strEmail))->fetch())) {
		  				$blnApplErr=true;
		  				$blnEmaildup=true;	
		  			}
		  		}
	  		}
  		
  			if($strPassword=="") {
  				$blnApplErr=true;
  				$blnPassword=true;	
  			}
  			if($strConPassword=="") {
  				$blnApplErr=true;
  				$blnConPassword=true;	
  			}
  			
  		}
  		
  		if ($strConPassword!=$strPassword && $strPassword!="") {
  			$blnApplErr=true;
  			$blnPasswordinv=true;
  		}
  		
  		if ($country_id=="") {
  			$blnApplErr=true;
  			$blncountry=true;	
  		}
  		
  		If (!$blnApplErr) {
        $strSubmitted = "process";
      } else {
	      If ($strAction == "") {
	        $strPostScript = $strPostScript . "?id=" . $intID;
	      } ElseIf ($strAction == "edit") {
	        $strPostScript = $strPostScript . "?a=edit&id=" . $intID;
	      }
      }
    }
  }
  
?>

<?php If ($strSubmitted == "" || $blnApplErr) { ?>

<?php 
	include "../includes/admin_inc_header.php";
?>
<form action="<?php echo $strPostScript; ?>" method="POST" name="postform-container" class="postform-container">
	<div id="form-notifications">
	</div>
	<div id="postform">
		<fieldset >
			<legend><?php echo $strDisplayTerm ?> Properties</legend>
			<div class="mandatory">
				<label><em>*</em>Name</label>
				<Input type="text" name="strName" size="40" maxlength="250" value="<?php echo $strName; ?>"  />
			</div>
			<div class="mandatory">
				<label><em>*</em>Email</label>
				<Input type="text" name="strEmail" size="40" maxlength="250" value="<?php echo $strEmail; ?>" />
				<script>
					<?php If ($blnApplErr && $blnEmailEmpty) { ?>
						addJNotification("Error",1,"strName","Please enter an Email address.");
					<?php } elseif ($blnApplErr && $blnEmailinv) { ?>
						addJNotification("Error",1,"strName","Please enter a valid Email address.");
					<?php } elseif ($blnApplErr && $blnEmaildup) { ?>
						addJNotification("Error",2,"strName","This Emaill address has been used. Please try again.");
					<?php } ?>
				</script>
			</div>
		</fieldset>
		<fieldset>
			<legend>Login Properties</legend>
			<div <?php if ($strAction=="") echo 'class="mandatory"'; ?> >
				<label><em>*</em>Password</label>
				<Input type="text" name="$strPassword" size="40" maxlength="250" value="<?php echo $strPassword; ?>"  />
				<script>
					<?php If ($blnApplErr && $blnPassword) { ?>
						addJNotification("Error",1,"strName","Please enter a Password.");
					<?php } ?>
				</script>
			</div>
			<div <?php if ($strAction=="") echo 'class="mandatory"'; ?> >
				<label><em>*</em>Confirm Password</label>
				<Input type="text" name="strConPassword" size="40" maxlength="250" value="<?php echo $strConPassword; ?>" />
				<script>
					<?php If ($blnApplErr && $blnConPassword) { ?>
						addJNotification("Error",1,"strName","Please confirm your Password.");
					<?php } elseif ($blnApplErr && $blnPasswordinv) { ?>	
						addJNotification("Error",2,"strName","Your passwords do not match. Please try again.");
					<?php }	?>
				</script>
			</div>
		</fieldset>
	</div>
	<div id="postform-action" >  
		<input type="hidden" name="strSubmitted" value="strSubmitted">
		<input type="submit" class="button" Name="submit" value="Save">
		<input type="button" class="button" value="Cancel" onclick="history.go(-1);">
	</div>
</form> 

<?php 
	include "../includes/admin_inc_footer.php";
?>

<?php } Elseif ($strSubmitted == "process") { 
	
	if ($strAction=="") {
		
		$mysql = new mysql();
		$node = new sqlNode();
		$node->table = "staffs";
		$node->push("text","strFullName",$strFullName);
		$node->push("text","strEmail",$strEmail);
		$node->push("text","strPassword",encrypt_password($strPassword));
		$node->push("int","country_id",$country_id);
		
		$node->push("date","dtDOB",$dtDOB);
		$node->push("text","strHomeAddress",$strHomeAddress);
		$node->push("text","strLocalAddress",$strLocalAddress);
		$node->push("text","strCity",$strCity);
		$node->push("text","strState",$strState);
		$node->push("text","strMobileNo",$strMobileNo);
		$node->push("text","strHomeTel",$strHomeTel);
		$node->push("text","strRemarks",$strRemarks);
		$node->push("text","dtDateJoined",$dtDateJoined);
		$node->push("text","dtDateLeft",$dtDateLeft);
		
		$node->push("int","blnActive",1);
		$node->push("int","intCreatedBy",$_SESSION["intUserURN"]);
		$node->push("date","dtCreatedDate",date("Y-m-d H:i:s",time()));
		
		if(($results = $mysql->insert($node)) === false )
					die($mysql->debugPrint());
					
		$intID = mysql_insert_id();
		
		//header("Location: status.php?t=suc&a=" . $strAction . "&no=1&code=");
		$strURL = "status.php?t=suc&a=" . $strAction . "&no=1&code=";
		redirect($strURL);
		
		
	} elseif ($strAction=="edit") {
		
		$mysql = new mysql();
		$node = new sqlNode();
		$node->table = "staffs";
		$node->push("text","strFullName",$strFullName);
		$node->push("int","country_id",$country_id);
		
		$node->push("date","dtDOB",$dtDOB);
		$node->push("text","strHomeAddress",$strHomeAddress);
		$node->push("text","strLocalAddress",$strLocalAddress);
		$node->push("text","strCity",$strCity);
		$node->push("text","strState",$strState);
		$node->push("text","strMobileNo",$strMobileNo);
		$node->push("text","strHomeTel",$strHomeTel);
		$node->push("text","strRemarks",$strRemarks);
		$node->push("text","dtDateJoined",$dtDateJoined);
		$node->push("text","dtDateLeft",$dtDateLeft);
		
		$node->push("int","intModifiedBy",$_SESSION["intUserURN"]);
		$node->push("date","dtModifiedDate",date("Y-m-d H:i:s",time()));
		$node->where = "where staff_id = " . $intID;
		
		if(($results = $mysql->update($node)) === false )
		die($mysql->debugPrint());
		
		//header("Location: status.php?t=suc&a=" . $strAction . "&no=1&code=");
		$strURL = "status.php?t=suc&a=" . $strAction . "&no=1&code=";
		redirect($strURL);
		
	}
	
?>
	
<?php } Elseif ($strSubmitted == "view") { ?>

<?php } ?>


