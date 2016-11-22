<?php 
	session_start();	
	define('__DIR__', pathinfo(__FILE__, PATHINFO_DIRNAME));
	require(__DIR__."/classes/class.captcha.php");
	$Captcha = new SecurityImage();
	$Captcha->Create();
?>