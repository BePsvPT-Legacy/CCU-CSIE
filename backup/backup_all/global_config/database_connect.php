<?php
	require_once("database_config.php");
	$mysqli_connecting = mysqli_connect(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD, DATABASE_NAME);
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	$ip = mysqli_real_escape_string($mysqli_connecting, $ip);
?>