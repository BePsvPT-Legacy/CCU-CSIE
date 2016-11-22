<?php
	require_once("globalbannedlist.php");
	
	$sql = "INSERT INTO `global_bannedlist` (`ip_address`, `reason`) VALUES ('".$ip."', '非法存取目錄')";
	mysqli_query($mysqli_connecting, $sql);
	
	header("Location:../index.php");
	exit();
?>