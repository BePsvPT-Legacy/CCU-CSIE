<?php
	require_once('config.php');
	if($_SERVER['REMOTE_ADDR']=="140.123.101.139"){
		echo "<p align='center'>請勿使用系上VPN。</p>";
		exit();
	}
	mysql_connect(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD)
		or die("<p>Error connecting to database : " . mysql_error() . "</p>");
	mysql_select_db(DATABASE_NAME)
		or die("<p>Error selecting the database : " . mysql_error() . "</p>");
?>