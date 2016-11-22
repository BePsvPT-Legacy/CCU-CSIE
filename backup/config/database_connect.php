<?php
	ini_set("session.cookie_httponly", 1);
	//ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_domain", "www.cs.ccu.edu.tw");
	header("X-XSS-Protection: 1; mode=block");
	header("X-Frame-Options: http://www.cs.ccu.edu.tw/");
	header("Cache-Control: no-cache, must-revalidate");
	date_default_timezone_set("Asia/Taipei");
	session_start();
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once($prefix . "config/database_config.php");
	require_once($prefix . "config/web_config.php");
	require_once($prefix . "config/web_view.php");
	$mysqli_connecting = mysqli_connect(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD, DATABASE_NAME);
	$mysqli_object_connecting = new mysqli(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD, DATABASE_NAME);
	
	if (mysqli_connect_errno()) {
		handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
	}
	if (!$mysqli_connecting) {
		handle_database_error($web_url, mysqli_error($mysqli_connecting));
		exit();
	} else {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$ip = input_escape_string($mysqli_connecting, $ip);
		$current_time_unix = time();
	}
?>