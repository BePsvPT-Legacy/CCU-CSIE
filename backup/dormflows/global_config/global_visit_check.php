<?php
	$sql = "SELECT `time_unix` FROM `global_bannedlist`";
	$result = mysqli_query($mysqli_connecting, $sql);
	$quantity = mysqli_num_rows($result) - 3;
	$sql = "SELECT `time_unix` FROM `global_bannedlist` WHERE `id` = '".$quantity."'";
	$result = mysqli_query($mysqli_connecting, $sql);
	
	if ($time_unix = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if (time() - $time_unix['time_unix'] <= 1800) {
			$global_visit_deny = 1;
		}
	}
?>