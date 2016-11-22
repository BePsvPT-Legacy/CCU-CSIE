<?
	require_once("../global_config/database_connect.php");
	
	$ver = "";
	if (isset($_GET['ver'])) {
		$ver = mysqli_real_escape_string($mysqli_connecting, $_GET['ver']);
	}
	if (!preg_match("/^\d{3,}$/" ,$ver)) {
		$sql = "INSERT INTO `dormflows_errorlog` (`errorlog`, `ip_address`) VALUES ('嘗試訪問 /dormflows/sources/rule.php 頁面', '".$ip."')";
		mysqli_query($mysqli_connecting, $sql);
		header("Location: ../index.php");
		exit();
	} else {
		$sql = "INSERT INTO dormflows_usinglog (`version`,`ip_address`) VALUES ('".$ver."','".$ip."')";
		mysqli_query($mysqli_connecting, $sql);
	}
?>
Ver = 101
Enforce = 1
VerURL = http://www.cs.ccu.edu.tw/~cys102u/dormflows/downloads.php
DetectTime = 6000
RuleStart = 累積流量
RuleStartOffset = 29
RuleEnd = </font> MB</h2>
RuleEndOffset = 0