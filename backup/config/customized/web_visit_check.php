<?php
	$sql = "SELECT `time_unix` FROM `web_visit_deny`";
	$result = mysqli_query($mysqli_connecting, $sql);
	if (!$result) {
		handle_database_error($web_url, mysqli_error($mysqli_connecting));
		exit();
	} else {
		$quantity = mysqli_num_rows($result);
		$sql = "SELECT `time_unix` FROM `web_visit_deny` WHERE `id` = '".$quantity."'";
		$result = mysqli_query($mysqli_connecting, $sql);
		if (!$result) {
			handle_database_error($web_url, mysqli_error($mysqli_connecting));
			exit();
		} else {
			if ($time_unix = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				if ($time_unix['time_unix'] - time() > 0) {
					handle_error($web_url, '很抱歉，網站目前暫時無法訪問');
					exit();
				}
			}
		}
	}
	if (IP_FILTER == 2) {
		if (strncmp($ip, "140.123", 7) < 0 or strncmp($ip, "140.123", 7) > 0) {
			handle_error($web_url, '很抱歉，網站目前只允許位於中正大學的使用者訪問');
			exit();
		}
	} else if (IP_FILTER == 1) {
		if (strncmp($ip, "140.123.220", 11) < 0 or (strncmp($ip, "140.123.225", 11) > 0 and strncmp($ip, "140.123.232", 11) < 0) or strncmp($ip, "140.123.240", 11) > 0) {
			handle_error($web_url, '很抱歉，網站目前只允許位於中正大學大學部宿舍的使用者訪問');
			exit();
		}
	} else if (IP_FILTER == 0) {
		handle_error($web_url, '很抱歉，網站目前暫時無法訪問');
		exit();
	}
?>