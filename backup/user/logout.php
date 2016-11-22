<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once($prefix . "config/visitor_check.php");
	
	session_unset();
	
	header("Location: " . $prefix . "index.php");
	exit();
?>