<?php
		//currentdate function
	function now() {
		return date("Y-m-d H:i:s",time());
	}
	
	function redirect($strURL) {
		echo '<html><body onload="window.location.href=\''.$strURL.'\';"><body></html>';
		exit();
	}
	
	function dateadd($interval, $number, $date) {
	
		if ($date=="") {
			$date_time_array = getdate($date);
		} else {
			$date_time_array = getdate($date);
		}
		$hours = $date_time_array["hours"];
		$minutes = $date_time_array["minutes"];
		$seconds = $date_time_array["seconds"];
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
	
		switch ($interval) {
	
			case "yyyy":
				$year+=$number;
				break;
			case "q":
				$year+=($number*3);
				break;
			case "m":
				$month+=$number;
				break;
			case "y":
			case "d":
			case "w":
				$day+=$number;
				break;
			case "ww":
				$day+=($number*7);
				break;
			case "h":
				$hours+=$number;
				break;
			case "n":
				$minutes+=$number;
				break;
			case "s":
				$seconds+=$number;
				break;
		}
		$timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
		return $timestamp;
	}
	
	
	Function datediff ($interval,$date1,$date2) {
		// Gets the amount of seconds between two dates
		$timedifference = $date2 - $date1;
	
		switch ($interval) {
			case "w":
				$retval = bcdiv($timedifference,604800);
				break;
			case "d":
				$retval = bcdiv($timedifference,86400);
				break;
			case "h":
				$retval =bcdiv($timedifference,3600);
				break;
			case "n":
				$retval = bcdiv($timedifference,60);
				break;
			case "s":
				$retval = $timedifference;
				break;
	
		}
		return $retval;
	
	}
	
?>
