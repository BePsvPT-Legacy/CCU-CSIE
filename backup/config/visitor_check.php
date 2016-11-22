<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once($prefix . "config/database_connect.php");
	
	require_once($prefix . "config/customized/visitor_info_record.php");
	require_once($prefix . "config/customized/web_visit_check.php");
	require_once($prefix . "config/customized/visitor_banned_check.php");
	
	if (isset($_SESSION['error_data'])) {
		handle_error($web_url, $_SESSION['error_data']);
		exit();
	}
?>