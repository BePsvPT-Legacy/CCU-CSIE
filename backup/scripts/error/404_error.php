<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once($prefix . "config/database_connect.php");
	
	$server_protocol = mysqli_real_escape_string($mysqli_connecting, $_SERVER['SERVER_PROTOCOL']);
	$request_method = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REQUEST_METHOD']);
	$remote_port = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REMOTE_PORT']);
	$http_referer = mysqli_real_escape_string($mysqli_connecting, $_SERVER['HTTP_REFERER']);
	$http_user_agent = mysqli_real_escape_string($mysqli_connecting, $_SERVER['HTTP_USER_AGENT']);

	$sql = "INSERT INTO `visitor_visit_404` (`ip`, `SERVER_PROTOCOL`, `REQUEST_METHOD`, `REMOTE_PORT`, `HTTP_REFERER`, `HTTP_USER_AGENT`, `time_unix`) VALUES ('".$ip."', '".$server_protocol."', '".$request_method."', '".$remote_port."', '".$http_referer."', '".$http_user_agent."', '".time()."')";
	if (!mysqli_query($mysqli_connecting, $sql)) {
		handle_database_error($web_url, mysqli_error($mysqli_connecting));
		exit();
	} else {
		$_SESSION['error_data'] = '很抱歉，您訪問的頁面不存在';
		handle_error($web_url, $_SESSION['error_data']);
		exit();
	}
?>