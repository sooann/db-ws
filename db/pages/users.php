<?php

	require_once "../global.php";
	require_once "../security_config.php";

	//##### Global Variables #####
	$strBodyTitle   = "Manage Users";
	$strPageTitle  = $strBodyTitle . " :: " . $strServerName;

	// ##### Request QueryString Variables #####
	$blnDeleted = Trim($_GET['del']);
	$blnActivated  = Trim($_GET['upd']);

	//' ##### Request Form Variables #####
	$strAction = Trim($_POST['submit']);

	$strSubmitted   = Trim($_REQUEST["sm"]);
	$strSortField   = Trim($_REQUEST["sf"]);
	$strSortOrder   = Trim($_REQUEST["so"]);
	$intPageSize    = Trim($_REQUEST["ps"]);
	$strDeleteIds   = Trim($_POST["strDeleteIds"]);

	// ##### Process QueryString/Form Variables #####
	If (!is_numeric($strSortField) || $strSortField == "") { $strSortField   = 0; }
	If (!is_numeric($strSortOrder) || $strSortOrder == "") { $strSortOrder   = 0; }
	If (!is_numeric($intPageSize) || $intPageSize == "")   { $intPageSize    = 0; }
	If (!is_numeric($intAccessId) || $intAccessId == "")   { $intAccessId    = 0; }

  //##### Other variable declarations #####
  $fldName      = array("User Name","Email","Last Logged In", "Login Count");
  $fldValue     = array("NAME","EMAIL","dtlogindate", "intlogincount");
  $fldSort      = array("NAME","EMAIL","dtlogindate", "intlogincount");
  $fldSortName  = array("User Name","Email","Last Logged In", "Login Count");
  $fldSortOrder = array("Ascending", "Descending");
  $fldSortOVal  = array("ASC", "DESC");
  $fldRecords   = array("20","50","100","200","500");

  // ##### Formulate new querystring #####
  $strQueryString = "&a=" . $intAccessId . "&sf=" . $strSortField . "&so=" . $strSortOrder . "&ps=" . $intPageSize . "&sm=" . $strSubmitted;

  $strSortUrl   = $strPostScript . "?a=" . $intAccessId . "&ps=" . $intPageSize . "&sm=" . $strSubmitted ;
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
        $strSQLQuery = $strSQLQuery . "staff_id = " . $arrDeleteIds[$i] . " ";
        If (UBound($arrDeleteIds) > 0 && UBound($arrDeleteIds) != $i) {
        	$strSQLQuery = $strSQLQuery . "OR " ;
        }
      }

		 //delete registrants
		 $SQLQuery = "DELETE FROM staff".$globallanguagedb_id." WHERE " . $strSQLQuery;
		 mysql_query($SQLQuery);

     // ##### Commit transaction #####
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
	
      $SQLQuery = "SELECT staff_id, blnactive FROM staffs WHERE ";
      for ($i=0;$i<=UBound($arrDeleteIds);$i++) {
        $SQLQuery = $SQLQuery . "staff_id = " . $arrDeleteIds[$i] . " ";
        If (UBound($arrDeleteIds) > 0 && UBound($arrDeleteIds) != $i) {
          $SQLQuery = $SQLQuery . "OR ";
        }
      }

			$stmt = mysql_query($SQLQuery);
      $numrows = mysql_num_rows($stmt);

      If ($numrows>0) {

      	while ($row = mysql_fetch_assoc($stmt)) {
	        // ##### Deactivate records #####

	        if ((int)$row["blnactive"]==1) {
	        	$blnactive=0;
	        } else {
	        	$blnactive=1;
	        }
					
	        $SQLQuery = "Update staffs set blnactive=".$blnactive." WHERE staff_id = " . $row["staff_id"];	        
	        mysql_query($SQLQuery);
      	}
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

  $SQLQuery = "SELECT count(*) as intcount FROM staffs";
  $stmt = mysql_query($SQLQuery);
  $ps = mysql_fetch_assoc($stmt);
  $numrows = $ps["intcount"];

  if ($numrows!=0) {
  	$intpagecount = ceil($numrows/$fldRecords[$intPageSize]);

  	$SQLQuery = "select * from staffs left join (select max(dtlogoutdate) as dtlogoutdate,max(dtcreatedDate) as dtlogindate, count(*) as intlogincount,staff_id from staffloginlog group by staff_id) as table1 on staffs.staff_id = table1.staff_id ORDER BY " . $fldSort[$strSortField] . " " . $fldSortOVal[$strSortOrder] . " limit " . ($p-1)*((int)$fldRecords[$intPageSize]). "," . $fldRecords[$intPageSize];
  	$stmt = mysql_query($SQLQuery);

  } else {
  	$intpagecount = 0;
  }

   // ##### End of initialization #############################################

   include "../includes/admin_inc_header.php";
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

<?php
	 // ##### Show column data #####
   If ($numrows>0) {
     $intRowsCount    = 0;
?>
	    <form method="post" name="cordlife" action="<?php echo"$strPostScript"; ?>" style="margin-bottom:0px;margin-top:0px;">
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
     While ($ps = mysql_fetch_assoc($stmt) ) {

       If ((int)($ps['blnActive']) == 0) {
         $strColColor = "#FAE8CB";
       } Else {
         $strColColor = "#FFFFFF";
       }

       echo '<tr bgcolor="' . $strColColor . '">';

       echo "<td>";

       echo '<a class="default" href="staff_form.php?a=edit&id=' . Trim($ps['staff_id']) . '" onmouseover="window.status=\'Update this Staff\\\'s details\'; return true" onmouseout="window.status=\'' . $strPageTitle . '\'; return true" title="Update this Staff\'s details"><img src="../images/buttons/edititem.gif" HEIGHT="15" WIDTH="13" BORDER="0" ALT="Update this Staff\'s details"></a>';

       echo "</td>";
       echo "<td>";

       // ##### Cannot DELETE Master User Account #####
       echo '<input class="checkbox" type="checkbox" onclick="checkRow(this)" name="strDeleteIds" value="' . Trim($ps["staff_id"]) .'" title="Select or de-select this item">';

       echo "</td>";

			 for ($i=0;$i<=UBound($fldName);$i++) {
         echo "<td>";
       	 if (is_null($ps[$fldValue[$i]])) {
       	 	 echo "-";
       	 } Elseif ($ps[$fldValue[$i]]=="") {
       	 	 echo "-";
       	 } else {
       	 	 echo Trim($ps[$fldValue[$i]]);
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
                               <td><input type="button" class="inputButton" Name="submit" value="New Staff" onclick="parent.location='staff_form.php'">&nbsp;</td>
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
                <td vAlign=top width="100%" class="darkgrey2">There are no Staffs available. Please check back later.</td>
              </tr>
              <tr>
                <td height="10"></td>
              </tr>
              <tr class="endformcell">
                <td><input type="button" class="inputButton" Name="submit" value="New Staff" onclick="parent.location='staff_form.php'"></td>
              </tr>
            </table>

<?php } ?>

<?php // ##### End of web form #################################################
?>
<script language=javascript>
<!--
  var frm = document.cordlife;
//-->
</script>
<?php
	If ($blnDeleted) {
		echo '<script language="JavaScript">';
		echo 'window.alert("The Staff(s) that you selected have been deleted.");';
		echo '</script>';
	} ElseIf ($blnActivated) {
		echo '<script language="JavaScript">';
		echo 'window.alert("The Staff(s) that you selected have been Enabled/Disabled.");';
		echo '</script>';
	}

	include "../includes/admin_inc_footer.php";
?>



