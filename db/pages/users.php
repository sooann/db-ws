<?php

	require_once "../global.php";
	require_once "../security_config.php";

	//##### Global Variables #####
	$strDisplayTerm = "User";
	$strDisplayTermPural = "Users";
	$strMainTable= "USERS";
	$strMainTableId= "USER_ID";
	$strNewDisplayPage = "user_form.php";
	
	$strBodyTitle   = "Manage ". $strDisplayTerm;
	$strPageTitle  = $strBodyTitle . " :: " . $strServerName;
	
	//##### Form variable declarations #####
	$fldName      = array("User Name","Email","Last Logged In", "Login Count","Created Date");
	$fldValue     = array("NAME","EMAIL","dtlogindate", "intlogincount","CREATEDDATE");
	$fldSort      = array("NAME","EMAIL","dtlogindate", "intlogincount","CREATEDDATE");
	$fldSortName  = array("User Name","Email","Last Logged In", "Login Count","Created Date");
	$fldSortOrder = array("Ascending", "Descending");
	$fldSortOVal  = array("ASC", "DESC");
	$fldRecords   = array("20","50","100","200","500");
	
	$fldFilterName = array("User Name","Email","Last Logged In", "Login Count","Created Date");
	$fldFilterValue = array("NAME","EMAIL","dtlogindate", "intlogincount","CREATEDDATE");
	$fldFilterDataType= array("text","text","date","int");

	// ##### Request QueryString Variables #####
	$blnDeleted = trim($_GET['del']);
	$blnActivated  = trim($_GET['upd']);

	//' ##### Request Form Variables #####
	$strAction = trim($_POST['submit']);

	$strSubmitted   = trim($_REQUEST["sm"]);
	$strSortField   = trim($_REQUEST["sf"]);
	$strSortOrder   = trim($_REQUEST["so"]);
	$intPageSize    = trim($_REQUEST["ps"]);
	$strDeleteIds   = trim($_POST["strDeleteIds"]);
	
	$intFilterField = trim($_REQUEST["iff"]);
	$strFilterValue = trim($_REQUEST["sfv"]);

	// ##### Process QueryString/Form Variables #####
	If (!is_numeric($strSortField) || $strSortField == "") { $strSortField   = 0; }
	If (!is_numeric($strSortOrder) || $strSortOrder == "") { $strSortOrder   = 0; }
	If (!is_numeric($intPageSize) || $intPageSize == "")   { $intPageSize    = 0; }
	If (!is_numeric($intAccessId) || $intAccessId == "")   { $intAccessId    = 0; }

  // ##### Formulate new querystring #####
  $strQueryString = "&a=" . $intAccessId . "&sf=" . $strSortField . "&so=" . $strSortOrder . "&ps=" . $intPageSize . "&sm=" . $strSubmitted . "&iff=" . $intFilterField . "&sfv=" . $strFilterValue;

  $strSortUrl   = $strPostScript . "?ps=" . $intPageSize . "&sm=" . $strSubmitted . "&iff=" . $intFilterField . "&sfv=" . $strFilterValue ;
  
  $fldSortUrl   = array($strSortUrl . "&sf=0", $strSortUrl . "&sf=1", $strSortUrl . "&sf=2", $strSortUrl . "&sf=3", $strSortUrl . "&sf=4", $strSortUrl . "&sf=5", $strSortUrl . "&sf=6", $strSortUrl . "&sf=7");
  $strSortImg = array("", "", "", "");
  for ($i=0; $i<=count($fldSortUrl)-1; $i++) {
    If ( $i == ((int)($strSortField))) {
      If ((int)($strSortOrder) == 0) {
        $fldSortUrl[$i] = $fldSortUrl[$i] . "&so=1";
        $strSortImg[$i] = '<img src="../images/sortascd.gif" width="7" height="7" border="0">';
      } else {
        $fldSortUrl[$i] = $fldSortUrl[$i] . "&so=0";
        $strSortImg[$i] = '<img src="../images/sortdesc.gif" width="7" height="7" border="0">';
      }
    } else {
      $fldSortUrl[$i] = $fldSortUrl[$i] . "&so=0";
      $strSortImg[$i] = "";
    }
  }

  // ##### Display activated/deleted status? #####
  If ($blnDeleted == "1") {
    $blnDeleted = True;
  }  Else {
    $blnDeleted = False;
  }

  If ($blnActivated == "1") {
    $blnActivated = True;
  } Else {
    $blnActivated = False;
  }

  // ##### Delete/Activate/Deactivate records #####

  If ($strAction == "Delete") {

    // ##### Delete records #####
    If ($strDeleteIds != "") {
      $arrDeleteIds   = split(', ', $strDeleteIds);

		  // ##### Begin Transaction #####

      // ##### Delete administrators #####
      $strSQLQuery="";
      for ($i=0;$i<=count($arrDeleteIds)-1;$i++) {
        $strSQLQuery = $strSQLQuery . $strMainTableId . " = " . $arrDeleteIds[$i] . " ";
        If (UBound($arrDeleteIds) > 0 && UBound($arrDeleteIds) != $i) {
        	$strSQLQuery = $strSQLQuery . "OR " ;
        }
      }

		  //delete registrants
      $db->delete($strMainTable, $strSQLQuery);

			//##### Commit transaction #####
			//header("Location: " . $strPostScript . "?p=" . Trim($_POST['pg']) . "&del=1" . $strQueryString);
			redirect($strPostScript . "?p=" . Trim($_POST['pg']) . "&del=1" . $strQueryString);
      
    } Else {
    	//header("Location: " . $strPostScript . "?p=" . Trim($_POST['pg']) . $strQueryString);
    	redirect($strPostScript . "?p=" . Trim($_POST['pg']) . $strQueryString);
    }
  } ElseIf ($strAction == "Enable/Disable") {
  	
    // ##### Activate/Deactivate records #####
    If ($strDeleteIds != "") {

      // ##### Begin Transaction #####
		  $arrDeleteIds   = split(", ", $strDeleteIds);
	
      $SQLQuery = "SELECT ".$strMainTableId.", ACTIVE FROM ".$strMainTable." WHERE ";
      for ($i=0;$i<=UBound($arrDeleteIds);$i++) {
        $SQLQuery = $SQLQuery . $strMainTableId." = " . $arrDeleteIds[$i] . " ";
        If (UBound($arrDeleteIds) > 0 && UBound($arrDeleteIds) != $i) {
          $SQLQuery = $SQLQuery . "OR ";
        }
      }

      $stmt = $db->query($SQLQuery);

      while ($row = $stmt->fetch()) {
	      // ##### Deactivate records #####
        if ($row["ACTIVE"]==1) {
        	$blnactive=0;
        } else {
        	$blnactive=1;
        }
				
        $node = new sqlNode();
        $node->table = $strMainTable;
        $node->push("int","ACTIVE",$blnactive);
        $node->where = $strMainTableId." = " . $row[$strMainTableId];
        $db->update($node);
        
      }

			//header("Location: " . $strPostScript . "?p=" . Trim($_POST['pg']) . "&upd=1" . $strQueryString);
			redirect($strPostScript . "?p=" . Trim($_POST['pg']) . "&upd=1" . $strQueryString);
		} Else {
      //header("Location: " . $strPostScript . "?p=" . Trim($_POST['pg']) . $strQueryString);
      redirect($strPostScript . "?p=" . Trim($_POST['pg']) . $strQueryString);
    }
  }

  // ##### Formulate query and Table pagination #####

	$p = Trim($_GET["p"]);
	If ($p == "") { $p = 1; }
	
	$paramArray=null;
	
	$SQLQuery = "select USERS.*, table1.intcount as intlogincount, table2.dtlogindate from USERS left join (select count(*) as intcount, user_id from loginlog group by user_id) as table1 on USERS.user_id = table1.user_id left outer join (select max(logindate) as dtlogindate,user_id from loginlog group by user_id ) as table2 on users.user_id = table2.user_id";
	
	if ($strFilterValue!="") {
		$SQLQuery = "select * from (".$SQLQuery.") as table1 ";
		
		$strFilterDataType=strtolower($fldFilterDataType[$intFilterField]);
		
		if ($strFilterDataType == "text") {
				$SQLQuery .= "Where " . $fldFilterValue[$intFilterField] ." like ? ";
				$paramArray = array($strFilterValue); 
		}
		
		if ($strFilterDataType == "int" || $strFilterDataType == "dbl") {
			$SQLQuery .= "Where " . $fldFilterValue[$intFilterField] ." = ? ";
			$paramArray = array($strFilterValue);
		}
		
		if (($strFilterDataType == "date" || $strFilterDataType == "datetime") && is_date($strFilterValue)) {
			
			$SQLQuery .= "Where " . $fldFilterValue[$intFilterField] ." > ? and " . $fldFilterValue[$intFilterField] . " < ? ";
			$paramArray = array($strFilterValue." 00:00:00",$strFilterValue." 23:59:59");
		}
			
	}


  $stmt = $db->query("SELECT count(*) as intcount FROM (".$SQLQuery.") as table1");
  $row = $stmt->fetch(); 
  $numrows = $row["intcount"];
  
	
  if ($numrows!=0) {
  
  	$intpagecount = ceil($numrows/$fldRecords[$intPageSize]);
   	
  	$stmt = $db->query($SQLQuery, $fldSort[$strSortField] . " " . $fldSortOVal[$strSortOrder], $fldRecords[$intPageSize], ($p-1)*((int)$fldRecords[$intPageSize]), $paramArray); 
  	
  } else {
  	$intpagecount = 0;
  }
	
   // ##### End of initialization #############################################

   require_once "../includes/admin_inc_header.php";
?>

<script language="javascript" src="../js/tablesupport2.js"></script>
<script language="javascript" >
<!--
function onSubmitForm(sButton) {
  if (sButton=="Enable/Disable")
    sButton = "Are you sure you wish to Enable/Disable the selected Staffs?"
  else
    sButton = "Are you sure you wish to delete the selected Staffs?"

  var intAnswer = confirm (sButton)
    if (intAnswer)
      return true;
    else
      return false;
}

//--> </script>

<?php // ##### Start of web form ###############################################
?>

<?php // ##### Simple Filter Form ###############################################
?>

<form method="post" name="filterForm" action="<?php echo"$strPostScript"; ?>" style="margin-bottom:0px;margin-top:0px;">
	<table cellspacing="0" cellpadding="3" border="0">
		<tr>
			<td>
				Filter: 
				<select name="intFilterField" >
					<?php 
						for ($i=0;$i<count($fldFilterName);$i++) {
							echo '<option value="$i" ';
							if ($intFilterField==$i) {
								echo "selected";
							}
							echo " >".$fldFilterName[$i]."</option>";
						}
					?>
				</select>
			</td>
			<td>
				<input type="text" name="strFilterValue" size="40" id="plhsearchtext" maxlength="250" value="<?php echo $strFilterValue; ?>" />
			</td>
			<td>
				<input type="reset" name="resetsearch" value="Clear" />
				<input type="submit" name="submitsearch" value="Go" />
			</td>
		</tr>
	</table>
</form><br />

<?php
	 // ##### Show column data #####
   If ($numrows>0) {
     $intRowsCount = 0;
?>
	    <form method="post" name="mainForm" action="<?php echo"$strPostScript"; ?>" style="margin-bottom:0px;margin-top:0px;">
	      <table class="tableBorderWhite" cellSpacing="0" cellPadding="0" width="100%" border="0">
	        <tr>
	          <td vAlign=top width="100%">
	            <table class="tableRowWhite" cellSpacing=0 cellPadding=0 width="100%" border="0">
	              <tr bgcolor="#DDDDDD">
	                <td>&nbsp;</td>
	                <td><input class="checkbox" type="checkbox" name="allboxes" title="Select or de-select all items" onclick="checkAll();"></td>

<?php
		 // ##### Show column header #####
     for ($i=0; $i<=UBound($fldName);$i++) {
		 		echo '<td noWrap><b><a class="controlBold" href="'.$fldSortUrl[$i].'" title="Sort '.$fldName[$i].'">'.$fldName[$i].'</a>&nbsp;'.$strSortImg[$i].'&nbsp;</b></td>';
		 }
?>
                </tr>
<?php
     While ($ps = $stmt->fetch() ) {

       If ((int)($ps['ACTIVE']) == 0) {
         $strColColor = "#FAE8CB";
       } Else {
         $strColColor = "#FFFFFF";
       }

       echo '<tr bgcolor="' . $strColColor . '">';

       echo "<td>";

       echo '<a class="default" href="'.$strNewDisplayPage.'?a=edit&id=' . Trim($ps[$strMainTableId]) . '" onmouseover="window.status=\'Update this '.$strDisplayTerm.'\\\'s details\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true" title="Update this '.$strDisplayTerm.'\'s details"><img src="../images/buttons/edititem.gif" HEIGHT="15" WIDTH="13" BORDER="0" ALT="Update this '.$strDisplayTerm.'\'s details"></a>';

       echo "</td>";
       echo "<td>";

       // ##### Cannot DELETE Master User Account #####
       echo '<input class="checkbox" type="checkbox" onclick="checkRow(this)" name="strDeleteIds" value="' . Trim($ps[$strMainTableId]) .'" title="Select or de-select this item">';

       echo "</td>";

			 for ($i=0;$i<=UBound($fldName);$i++) {
         echo "<td>";
         if ($i==0) {
         	 echo '<a class="default" href="' . $strNewDisplayPage . '?a=view&id=' . trim($ps[$strMainTableId]) . '" onmouseover="window.status=\'View this '. $strDisplayTerm . '\\\'s details\'; return true" onmouseout="window.status=\'' . $strPageTitle .'\'; return true" title="View this '. $strDisplayTerm . '\'s details" >' . $ps[$fldValue[$i]] . '</a>';
         } elseif (is_null($ps[$fldValue[$i]])) {
       	 	 echo "-";
       	 } Elseif ($ps[$fldValue[$i]]=="") {
       	 	 echo "-";
       	 } else {
       	 	 echo trim($ps[$fldValue[$i]]);
       	 }
         echo "</td>";
			 }
       echo "</tr>";

       $intRowsCount++;
     }
?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td vAlign=top width="100%">
                   <tr>
                     <td width="100%" colspan="<?php echo UBound($fldName)+3 ?>"><br>
                       <table border="0" class="subMenu" cellSpacing="0" cellPadding="0" width="100%">
                         <tr class="endformcell" align="left">
                           <td valign="top" >
                             <table cellspacing="0" cellpadding="1" border="0">
                              <tr>
                               <td><input type="button" class="inputButton" Name="submit" value="New <?php echo $strDisplayTerm; ?>" onclick="parent.location='<?php echo $strNewDisplayPage; ?>'">&nbsp;</td>
                               <td><input type="submit" class="inputButton" Name="submit" value="Enable/Disable" onclick="return onSubmitForm('Enable/Disable');">&nbsp;</td>
                               <!---
                               <td><input type="submit" class="inputButton" Name="submit" value="Delete"  onclick="return onSubmitForm('Delete');">&nbsp;</td>
                               --->
                               <td valign="top"><img src="../images/buttons/inactiveitem.gif" HEIGHT="15" WIDTH="70" BORDER="0" ALT="Denotes Inactive users"></a></td>
                              </tr>
                            </table>
                           </td>
                           <td width="30%">&nbsp;</td>
                           <td noWrap align="right">
                             Page <?php echo $p ?> of <?php echo $intpagecount ?> :

<?php   // ##### Show paging and navigation #####
     If ($p > 1) {
       echo '<a class="default" href="' . $strPostScript . '?p=1' . $strQueryString . '" onmouseover="window.status=\'Go to first page\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true">First</a>&nbsp;|&nbsp;';
       echo '<a class="default" href="' . $strPostScript . '?p=' . ($p-1) . $strQueryString . '" onmouseover="window.status=\'Go to previous page\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true">Prev</a>&nbsp;';
     }

     $pCount = $intpagecount;

     If ($pCount <= $intPageIndex) {
       $pMinCount = 1;
       $pMaxCount = $pCount;
     } Else {
       $pMinCount = $p;
       $pMaxCount = $p + ($intPageIndex-1);

       If ($pMaxCount >= $pCount) {
         $pMaxCount = $pCount;
         $pMinCount = $pMaxCount - ($intPageIndex-1);
       }
     }

		 for ($intcount=$pMinCount;$intcount<=$pMaxCount;$intcount++) {
       If ($intcount == (int)($p)) {
         echo $intcount;
         If ($intcount < $pCount) { echo ",&nbsp;"; }
       } Else {
         echo '<a class="default" href="' . $strPostScript . '?p=' . $intcount . $strQueryString . '" onmouseover="window.status=\'Go to page ' . $intcount . '\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true">' . $intcount . "</a>";
         If ($intcount < $intpagecount) { echo ",&nbsp;"; }
         If ($intcount == $intpagecount) { echo "&nbsp;"; }
       }
     }

     If ((int)($p) != (int)($intpagecount)) {
        echo '<a class="default" href="' . $strPostScript . '?p=' . ($p+1) . $strQueryString . '" onmouseover="window.status=\'Go to next page\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true">Next</a>&nbsp;|&nbsp;';
        echo '<a class="default" href="' . $strPostScript . '?p=' . $intpagecount . $strQueryString . '" onmouseover="window.status=\'Go to last page\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true">Last</a>';
     }
?>
                           </td>
                         </tr>
                       </table>
                       <input type="hidden" name="sm" value="strSubmitted">
                       <input type="hidden" name="pg" value="<?php echo $p ?>">
                       <input type="hidden" name="sf" value="<?php echo $strSortField ?>">
                       <input type="hidden" name="so" value="<?php echo $strSortOrder ?>">
                       <input type="hidden" name="ps" value="<?php echo $intPageSize ?>">
                       <input type="hidden" name="a" value="<?php echo $intAccessId ?>">
                       <input type="hidden" name="strText" value="<?php echo $strText ?>">
                       <input type="hidden" name="intfield" value="<?php echo $intfield ?>">
                     </td>
                   </tr>
                </td>
              </tr>
            </table>
            </form>

<?php } Else { ?>

            <table class="tableBorderWhite" cellSpacing="0" cellPadding="0" width="100%" border="0">
              <tr>
                <td vAlign=top width="100%" class="darkgrey2">There are no <?php echo $strDisplayTerm; ?> available. Please check back later.</td>
              </tr>
              <tr>
                <td height="10"></td>
              </tr>
              <tr class="endformcell">
                <td><input type="button" class="inputButton" Name="submit" value="New <?php echo $strDisplayTerm; ?>" onclick="parent.location='<?php echo $strNewDisplayPage; ?>'"></td>
              </tr>
            </table>

<?php } ?>

<?php // ##### End of web form #################################################
?>
<script language=javascript>
<!--
  var frm = document.mainForm;
//-->
</script>
<?php
	If ($blnDeleted) {
		echo '<script language="JavaScript">';
		echo 'window.alert("The '.$strDisplayTerm.'(s) that you selected have been deleted.");';
		echo '</script>';
	} ElseIf ($blnActivated) {
		echo '<script language="JavaScript">';
		echo 'window.alert("The '.$strDisplayTerm.'(s) that you selected have been Enabled/Disabled.");';
		echo '</script>';
	}

	include "../includes/admin_inc_footer.php";
?>



