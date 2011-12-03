<?php 

	require_once "../global.php";
	require_once "../security_config.php";
	require_once "../includes/password.php";
	
	$strDisplayTerm = "Database";
	$strDisplayTermPural = "Databases";
	$strMainTable= "DATABASES";
	$strMainTableId= "DATABASE_ID";
	$strStatusPage = "databases.php";
	
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
  			
  			$strLabel = $row['LABEL'];
  			$strName = $row['NAME'];
				
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
  		
  		$strLabel = trim($_POST['strLabel']);
  		$strName = trim($_POST['strName']);
  		
  		if ($strName=="") {
  			$blnApplErr=true;
  			$blnName=true;	
  		} else {
  			//check if name is unique
  			$SQLQuery="select " . $strMainTable . ".* from " . $strMainTable . " where NAME like ? and ".$strMainTableId." <> ? ";
  			if (is_array($db->query($SQLQuery,NULL,NULL,NULL,array($strName, $intID))->fetch())) {
  				$blnApplErr=true;
  				$blnNameInv=true;
  			}
  		}
  		
  		if ($strLabel=="") {
  			$blnApplErr=true;
  			$blnLabel=true;
  		} else {
  			//check if label is unique  			
  			$SQLQuery="select " . $strMainTable . ".* from " . $strMainTable . " where LABEL like ? and ".$strMainTableId." <> ? ";
  			if (is_array($db->query($SQLQuery,NULL,NULL,NULL,array($strLabel, $intID))->fetch())) {
  				$blnApplErr=true;
  				$blnLabelInv=true;
  			}
  		}
  		  		
  		If (!$blnApplErr) {
        
        if ($strAction=="") {
        	
        	//add database to db server
        	if ($db->createDB($strName)) {
        		//add record to system
	        	$node = new sqlNode();
	        	$node->table = $strMainTable;
	        	$node->push("text","NAME",$strName);
	        	$node->push("text","LABEL",$strLabel);
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
        	} else {
        		$blnApplErr=true;
        		$blnDBErr=true;
        	}
        
        } elseif ($strAction=="edit") {
        	
        	//find old database name
        	$SQLQuery="select " . $strMainTable . ".* from " . $strMainTable . " where " . $strMainTable . "." . $strMainTableId . "= ?";
        	$row = $db->query($SQLQuery,NULL,NULL,NULL,array($intID))->fetch();
        	
        	//update database to db server
        	if ($db->updateDBName($row['NAME'], $strName)) {
	        	
	        	//add record to system
	        	$node = new sqlNode();
	        	$node->table = $strMainTable;
	        	$node->push("text","NAME",$strName);
	        	$node->push("text","LABEL",$strLabel);
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
				<label><em>*</em>Label</label>
				<Input type="text" name="strLabel" maxlength="250" value="<?php echo $strLabel; ?>" /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnLabel) { ?>
						$('input[name="strLabel"]').jqnotify({type:"Error", level:1, text:"Please enter a Label."});
					<?php } elseif ($blnApplErr && $blnLabelInv) { ?>
						$('input[name="strLabel"]').jqnotify({type:"Error", level:2, text:"Label Exist in System. Labels cannot be re-used."});
					<?php } ?>
				</script>
			</div>
			<div class="mandatory">
				<label><em>*</em>Name</label>
				<Input type="text" name="strName" maxlength="250" value="<?php echo $strName; ?>"  /><img src="../img/icons/icon_error.png" />
				<script>
					<?php If ($blnApplErr && $blnName) { ?>
						$('input[name="strName"]').jqnotify({type:"Error", level:1, text:"Please enter a Name."});
					<?php } elseif ($blnApplErr && $blnNameInv) { ?>
						$('input[name="strName"]').jqnotify({type:"Error", level:2, text:"Name Exist in System. Names cannot be re-used."});
					<?php } ?>
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
					<label>Label</label><span><?php echo $strLabel; ?></span>
				</div>
				<div>
					<label>Name</label><span><?php echo $strName; ?></span>
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


