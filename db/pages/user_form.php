<?php 

	require_once "../global.php";
	require_once "../security_config.php";
	require_once "../includes/password.php";
	
	$strDisplayTerm = "User";
	$strDisplayTermPural = "Users";
	$strMainTable= "USERS";
	$strMainTableId= "user_id";
	$strStatusPage = "users.php";
	
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
			$strBodyTitle="Update ".$strDisplayTerm;
			$strPageTitle = "Update ".$strDisplayTerm;
			$strPageDescription = "Update the details of this ".$strDisplayTerm.". Click on the <b>Save</b> button to finalize your changes.";
			break;
		case "view":
			$strBodyTitle="View ".$strDisplayTerm;
			$strPageTitle = "View ".$strDisplayTerm;
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
				
				$blnActive = $row['ACTIVE'];
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
	  			if (!isEmailValid($strEmail)) {
		  			$blnApplErr=true;
		  			$blnEmailinv=true;	
		  		} else {
		  			$SQLQuery="select * from ".$strMainTable." where EMAIL like ? and ".$strMainTableId." <> ? ";
		  			if (is_array($db->query($SQLQuery,NULL,NULL,NULL,array($strEmail,$intID))->fetch())) {
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
  		
  		If (!$blnApplErr) {
        
        if ($strAction=="") {
        	
        	$node = new sqlNode();
        	$node->table = $strMainTable;
        	$node->push("text","NAME",$strName);
        	$node->push("text","EMAIL",$strEmail);
        	$node->push("text","PASSWORD",encrypt_password($strPassword));
        	$node->push("int","ACTIVE",1);
        	$node->push("int","CREATEDBY",$_SESSION["intUserURN"]);
        	$node->push("date","CreatedDate",now());
        	$user_id = $db->insert($node);
        
        	if ($user_id!=false) {
	        	//header("Location: status.php?t=suc&a=" . $strAction . "&no=1&code=");
	        	$strURL = $strStatusPage."?t=suc&a=" . $strAction;
	        	redirect($strURL);
        	} else {
        		$blnApplErr=true;
        		$blnDBErr=true;
        	}
        
        } elseif ($strAction=="edit") {
        	
        	$node = new sqlNode();
        	$node->table = $strMainTable;
        	$node->push("text","NAME",$strName);
        	$node->push("text","EMAIL",$strEmail);
        	if ($strPassword!="") {
        		$node->push("text","PASSWORD",encrypt_password($strPassword));
        	}
        	$node->push("int","MODIFIEDBY",$_SESSION["intUserURN"]);
        	$node->push("date","MODIFIEDDATE",now());
        	$node->where = $strMainTableId." = " . $intID;
        	
        	if ($db->update($node)) {
	        	//header("Location: status.php?t=suc&a=" . $strAction . "&no=1&code=");
	        	$strURL = $strStatusPage."?t=suc&a=" . $strAction;
	        	redirect($strURL);
        	} else {
        		$blnApplErr=true;
        		$blnDBErr=true;
        	}
        	
        }
        
        If ($strAction == "") {
        	$strPostScript = $strPostScript . "?id=" . $intID;
        } ElseIf ($strAction == "edit") {
        	$strPostScript = $strPostScript . "?a=edit&id=" . $intID;
        }
        
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
	<script>
		<?php If ($blnApplErr && $blnDBErr) { ?>
			addNotification("Error", "Update Database. Message <?php echo $db->getErrorMessage(); ?> ");
		<?php } ?>
	</script>
	<div id="postform">
		<fieldset >
			<legend><?php echo $strDisplayTerm ?> Properties</legend>
			<div class="mandatory">
				<label><em>*</em>Name</label>
				<Input type="text" name="strName" maxlength="250" value="<?php echo $strName; ?>" /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnName) { ?>
						$('input[name="strName"]').jqnotify({type:"Error", level:1, text:"Please enter a Name."});
						<?php } ?>
				</script>
			</div>
			<div class="mandatory">
				<label><em>*</em>Email</label>
				<Input type="text" name="strEmail" maxlength="250" value="<?php echo $strEmail; ?>"  /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnEmailEmpty) { ?>
						$('input[name="strEmail"]').jqnotify({type:"Error", level:1, text:"Please enter an Email address."});
					<?php } elseif ($blnApplErr && $blnEmailinv) { ?>
						$('input[name="strEmail"]').jqnotify({type:"Error", level:1, text:"Please enter a valid Email address."});
					<?php } elseif ($blnApplErr && $blnEmaildup) { ?>
						$('input[name="strEmail"]').jqnotify({type:"Error", level:2, text:"This Emaill address has been used."});
					<?php } ?>
				</script>
			</div>
		</fieldset>
		<fieldset>
			<legend>Login Properties</legend>
			<div <?php if ($strAction=="") echo 'class="mandatory"'; ?> >
				<label><em>*</em>Password</label>
				<Input type="password" name="strPassword" maxlength="250" value="<?php echo $strPassword; ?>"  /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnPassword) { ?>
						$('input[name="strPassword"]').jqnotify({type:"Error", level:1, text:"Please enter a Password."});
					<?php } elseif ($blnApplErr && $blnPasswordinv) { ?>	
						$('input[name="strPassword"]').jqnotify({type:"Error", level:2, text:"Your passwords do not match."});
					<?php }	?>
				</script>
			</div>
			<div <?php if ($strAction=="") echo 'class="mandatory"'; ?> >
				<label><em>*</em>Confirm Password</label>
				<Input type="password" name="strConPassword" maxlength="250" value="<?php echo $strConPassword; ?>" /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnConPassword) { ?>
						$('input[name="strConPassword"]').jqnotify({type:"Error", level:1, text:"Please confirm your Password."});
					<?php } elseif ($blnApplErr && $blnPasswordinv) { ?>	
						$('input[name="strConPassword"]').jqnotify({type:"Error", level:2, text:"Your passwords do not match."});
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
	
<?php } Elseif ($strSubmitted == "view") { ?>

<?php include "../includes/admin_inc_header.php"; ?>

<form action="<?php echo $strPostScript; ?>" method="POST" name="postform-container" class="postform-container" >
	<div id="viewform">
		<article>
			<header>
				<h4><?php echo $strDisplayTerm ?> Properties</h4>
			</header>
			<section>
				<div>
					<label>Name</label><span><?php echo $strName; ?></span>
				</div>
				<div>
					<label>Email</label><span><?php echo $strEmail; ?></span>
				</div>
			</section>
		</article>
		<article>
			<header>
				<h4>Other Information</h4>
			</header>
			<section>
				<div>
					<label>Is <?php echo $strDisplayTerm; ?> Active?</label>
					<span>
						<?php
							if ($blnActive==1) {
								echo "Yes";
							} else {
								echo "No";
							}
						?>
					</span>
				</div>
				<div>
					<label>Created By</label><span><?php echo $strCreatedByName; ?></span>
				</div>
				<div>
					<label>Modified By</label><span><?php echo $strModifiedByName; ?></span>
				</div>
			</section>
		</article>
	</div>
	<div id="postform-action" >  
		<input type="submit" class="button" Name="submit" value="Update">
		<input type="button" class="button" value="Cancel" onclick="history.go(-1);">
	</div>
</form>

<?php include "../includes/admin_inc_footer.php"; ?>

<?php } ?>


