<?php

	$date_temp = time();
	$date_temp = dateadd('n',-30,$date_temp);	
	if ($_SESSION['intUserURN']=="" || $_SESSION['dtLastAccess'] <= $date_temp ) {
		//redirect
		
		If ($_SERVER['QUERY_STRING'] != '') {
			$LoginFailRedirect = "login.php?url=" . urlencode($PostScript . "?" . $_SERVER['QUERY_STRING']);
		} else {
			$LoginFailRedirect = "login.php?url=" . urlencode($PostScript);
		}
		
		session_unset();
		session_destroy();
		session_regenerate_id();
		
		redirect($LoginFailRedirect);
	} else {
		$_SESSION['dtLastAccess']=time();
	}
	
	//rights managed
	
?>