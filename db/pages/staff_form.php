<?php 

	include "../global.php";
	include "../functions.php";
	include "../includes/password.php";
	include "../security_config.php";
	
	$headtags="";
	$headtags .= '<link rel="stylesheet" type="text/css" media="all" href="../js/jscalendar-1.0/calendar-win2k-cold-1.css" title="win2k-cold-1" />';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/calendar.js"></script>';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/lang/calendar-en.js"></script>';
	$headtags .= '<script type="text/javascript" src="../js/jscalendar-1.0/calendar-setup.js"></script>';
	 
	$strBodyTitle  = "Staff";
	$strPageTitle  = $strBodyTitle . " :: " . $strServerName;
	
	$strAction = Trim($_GET["a"]);
  $intID     = Trim($_GET["id"]);
  
  $strSubmitted = $_POST["strSubmitted"];
  
  If ($intID == "" || !is_numeric($intID)) { $intID = 0; }
	
	switch ($strAction) {
		case "":
			$strBodyTitle="New Staff";
			$strSectTitle = "New Staff";
			$strPageDescription = "Create a new Staff to administrate Cordlife Website. Click on the <b>Save</b> button to finalize the new Staff.";
			break;
		case "edit":
			$strBodyTitle="Update Staff";
			$strSectTitle = "Update Staff";
			$strPageDescription = "Update the details of this Staff. Click on the <b>Save</b> button to finalize your changes.";
			break;
		case "view":
			$strBodyTitle="View Staff";
			$strSectTitle = "View Staff";
			$strPageDescription = "View the details of this Staff. Click on the <b>Update</b> Button to edit the details of this Staff.";
			break;
	}
   
  
  If ($strSubmitted == "") {
  	if ($strAction=="") {
  		$strFullName="";
  		$strEmail="";
  		$strPassword="";
  		
  	}	elseif ($strAction=="edit" || $strAction=="view") {
  		
  		$SQLQuery="select * from staffs where staff_id=".$intID;
  		
  		if ($stmt = mysql_query($SQLQuery)) {
  			
  			$row = mysql_fetch_assoc($stmt);
  			
  			$strFullName = $row['strFullName'];
				$strEmail = $row['strEmail'];
				$dtDOB = $row['dtDOB'];
				$strHomeAddress = $row['strHomeAddress'];
				$strLocalAddress = $row['strLocalAddress'];
				$strCity = $row['strCity'];
				$strState = $row['strState'];
				$country_id = $row['country_id'];
				$strMobileNo = $row['strMobileNo'];
				$strHomeTel = $row['strHomeTel'];
				$strRemarks = $row['strRemarks'];
				$dtDateJoined = $row['dtDateJoined'];
				$dtDateLeft = $row['dtDateLeft'];
				
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
  		$strFullName = trim($_POST['strFullName']);
  		$strEmail = trim($_POST['strEmail']);
  		$country_id = trim($_POST['country_id']);
  		$strPassword = trim($_POST['strPassword']);
  		$strConPassword = trim($_POST['strConPassword']);
  		
			$dtDOB = trim($_POST['dtDOB']);
			$strHomeAddress = trim($_POST['strHomeAddress']);
			$strLocalAddress = trim($_POST['strLocalAddress']);
			$strCity = trim($_POST['strCity']);
			$strState = trim($_POST['strState']);
			$strMobileNo = trim($_POST['strMobileNo']);
			$strHomeTel = trim($_POST['strHomeTel']);
			$strRemarks = trim($_POST['strRemarks']);
			$dtDateJoined = trim($_POST['dtDateJoined']);
			$dtDateLeft = trim($_POST['dtDateLeft']);
  		
  		if ($strFullName=="") {
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
		  			$SQLQuery="select * from staffs where strEmail='".$strEmail;
		  			$stmt = mysql_query($SQLQuery);
		  			if (mysql_num_rows($stmt)>0) {
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
  			if ($strConPassword!=$strPassword) {
  				$blnApplErr=true;
  				$blnPasswordinv=true;	
  			}
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
  $SQLQuery = "SELECT * from sys_country where blnactive=1";
  $stmt = mysql_query($SQLQuery);
  
?>

<?php If ($strSubmitted == "" || $blnApplErr) { ?>

<?php 
	include "../includes/admin_inc_header.php";
?>

<form action="<?php echo $strPostScript; ?>" method="POST" name="cordlife">
	<table width="100%" cellspacing="0" cellpadding="3" border="0" class="subtable">
		<tr><td width="25%"></td><td width="75%"></td></tr>
		<tr>
	    <td colspan="2" height="5"></td>
	  </tr>
	  <tr>
	    <td colspan="2" class="navheadgreen">Staff Login Properties</td>
	  </tr>
	  <tr>
	    <td colspan="2" height="1"></td>
	  </tr>		  
	  <?php if ($strAction=="" or $strAction=="new") { ?>
		<tr class="cellcolor">
      <td align="right"><font color="#FF0000"><b>*</b>&nbsp;</font><b>Staff Email:</b></td>
      <td ><Input type="text" name="strEmail" size="40" maxlength="250" value="<?php echo $strEmail; ?>"  />
				<?php 
					If ($blnApplErr && $blnEmailEmpty) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please enter an Email address.</span>';
					} elseif ($blnApplErr && $blnEmailinv) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please enter a valid Email address.</span>';
					} elseif ($blnApplErr && $blnEmaildup) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">This Emaill address has been used. Please try again.</span>';
					}
				?>
  		</td>
		</tr>
		<tr >
      <td align="right"><font color="#FF0000"><b>*</b>&nbsp;</font><b>Staff Password:</b></td>
      <td ><Input type="password" name="strPassword" size="20" maxlength="250" value="<?php echo $strPassword; ?>"  />
				<?php 
					If ($blnApplErr && $blnPassword) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please enter a Password.</span>';
					} 
				?>
  		</td>
		</tr>
		<tr class="cellcolor">
      <td align="right"><font color="#FF0000"><b>*</b>&nbsp;</font><b>Confirm Staff Password:</b></td>
      <td ><Input type="password" name="strConPassword" size="20" maxlength="250" value="<?php echo $strConPassword; ?>"  />
				<?php 
					If ($blnApplErr && $blnConPassword) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please confirm your Password.</span>';
					} elseif ($blnApplErr && $blnPasswordinv) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Your passwords do not match. Please try again.</span>';
					}
				?>
  		</td>
		</tr>
		<?php } else { ?>
		<tr class="cellcolor" >
			<td colspan="2" align="center"><input type="button" value="Click here to Change Email Address" onclick="window.location.href='changeemail_form.php?id=<?php echo $intID; ?>';">&nbsp;&nbsp;&nbsp;<input type="button" value="Click here to Change Password" onclick="window.location.href='changepassword_form.php?id=<?php echo $intID; ?>';"></td>
		</tr>
		<?php } ?>
		<tr>
	    <td colspan="2" height="5"></td>
	  </tr>
	  <tr>
	    <td colspan="2" class="navheadgreen">Staff Profile</td>
	  </tr>
	  <tr>
	    <td colspan="2" height="1"></td>
	  </tr>		  
		<tr class="cellcolor">
      <td align="right"><font color="#FF0000"><b>*</b>&nbsp;</font><b>Staff Name:</b></td>
      <td ><Input type="text" name="strFullName" size="40" maxlength="250" value="<?php echo $strFullName; ?>"  />
				<?php 
					If ($blnApplErr && $blnName) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please enter a Category Name.</span>';
					}
				?>
  		</td>
		</tr>
    <tr >
	  	<td align="right">&nbsp;<b>Date of Birth:</b></td>
	    <td ><input type="text" id="dtDOB" name="dtDOB" size="20" maxlength="250" value="<?php echo $dtDOB; ?>" readonly >&nbsp;<button type="reset" id="f_trigger_a" class="button">...</button>
	       <script type="text/javascript">
	        Calendar.setup({
	            inputField     :    "dtDOB",      // id of the input field
	            //ifFormat       :    "%m/%d/%Y",       // format of the input field
	            button         :    "f_trigger_a",   // trigger for the calendar (button ID)
	            weekNumbers : false, 
	            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	        });
	    	</script>
	    </td>
	  </tr>
	  <tr class="cellcolor">
	  	<td align="right">&nbsp;<b>Home Address:</b></td>
	    <td ><textarea name="strHomeAddress" cols="40" rows="5"><?php echo $strHomeAddress; ?></textarea></td>
	  </tr>
	  <tr >
	  	<td align="right">&nbsp;<b>Local Address:</b></td>
	    <td ><textarea name="strLocalAddress" cols="40" rows="5"><?php echo $strLocalAddress; ?></textarea></td>
	  </tr>
	  <tr class="cellcolor">
      <td align="right"><b>City:</b></td>
      <td ><Input type="text" name="strCity" size="40" maxlength="250" value="<?php echo $strCity; ?>"  />
  		</td>
		</tr>
		<tr >
      <td align="right"><b>State:</b></td>
      <td ><Input type="text" name="strState" size="40" maxlength="250" value="<?php echo $strState; ?>"  />
  		</td>
		</tr>
		<tr class="cellcolor">
      <td align="right"><font color="#FF0000"><b>*</b>&nbsp;</font><b>Country:</b></td>
      <td >
      	<select name="country_id">
      		<option value="">Select a Country</option>
      		<?php 
      			While ($ps = mysql_fetch_assoc($stmt) ) {
      				echo '<option value="'.$ps["country_id"].'" ';
      				if ((int)$country_id==(int)$ps["country_id"]) {
      					echo "selected";
      				}
      				echo " >".$ps["strName"]."</option>";
      			}
      		?>
      	</select>
      	<?php
      		If ($blnApplErr && $blncountry) {
						echo '<img src="../images/exclaim_mark.gif" height="16" width="16" />';
						echo '<br /><span class="errorText">Error: Please select your country.</span>';
					}
      	?>
      </td>
    </tr>
    <tr >
      <td align="right"><b>Mobile:</b></td>
      <td ><Input type="text" name="strMobileNo" size="20" maxlength="250" value="<?php echo $strMobileNo; ?>"  />
  		</td>
		</tr>
		<tr class="cellcolor">
      <td align="right"><b>Home:</b></td>
      <td ><Input type="text" name="strHomeTel" size="20" maxlength="250" value="<?php echo $strHomeTel; ?>"  />
  		</td>
		</tr>
		<tr>
	    <td colspan="2" height="5"></td>
	  </tr>
	  <tr>
	    <td colspan="2" class="navheadgreen">Employment Details</td>
	  </tr>
	  <tr>
	    <td colspan="2" height="1"></td>
	  </tr>
		<tr class="cellcolor">
	  	<td align="right">&nbsp;<b>Date Joined:</b></td>
	    <td ><input type="text" id="dtDateJoined" name="dtDateJoined" size="20" maxlength="250" value="<?php echo $dtDateJoined; ?>" readonly >&nbsp;<button type="reset" id="f_trigger_b" class="button">...</button>
	       <script type="text/javascript">
	        Calendar.setup({
	            inputField     :    "dtDateJoined",      // id of the input field
	            //ifFormat       :    "%m/%d/%Y",       // format of the input field
	            button         :    "f_trigger_b",   // trigger for the calendar (button ID)
	            weekNumbers : false, 
	            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	        });
	    	</script>
	    </td>
	  </tr>
	  <tr >
	  	<td align="right">&nbsp;<b>Date Left:</b></td>
	    <td ><input type="text" id="dtDateLeft" name="dtDateLeft" size="20" maxlength="250" value="<?php echo $dtDateLeft; ?>" readonly >&nbsp;<button type="reset" id="f_trigger_c" class="button">...</button>
	       <script type="text/javascript">
	        Calendar.setup({
	            inputField     :    "dtDateLeft",      // id of the input field
	            //ifFormat       :    "%m/%d/%Y",       // format of the input field
	            button         :    "f_trigger_c",   // trigger for the calendar (button ID)
	            weekNumbers : false, 
	            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	        });
	    	</script>
	    </td>
	  </tr>
	  <tr>
	    <td colspan="2" height="5"></td>
	  </tr>
	  <tr>
	    <td colspan="2" class="navheadgreen">Remarks</td>
	  </tr>
	  <tr>
	    <td colspan="2" height="1"></td>
	  </tr>
	  <tr class="cellcolor">
	  	<td colspan="2" align="center"><textarea name="strRemarks" cols="80" rows="5"><?php echo $strRemarks; ?></textarea></td>
	  </tr>
	  <tr>
	    <td colspan="2" height="5"></td>
	  </tr>
	  <tr>
    <td class="endformcell">&nbsp;</td>
    <td class="endformcell">
    	<input type="hidden" name="strSubmitted" value="strSubmitted">
      <input type="submit" class="inputButton" Name="submit" value="Save">
      <input type="button" class="inputButton" value="Cancel" onclick="history.go(-1);">
    </td>
  </tr>
	</table>
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

