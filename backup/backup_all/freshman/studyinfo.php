<?php
	session_start();
	require_once("../global_config/globalbannedlist.php");
	
	if (!isset($_SESSION['mygrade'])) {
		$_SESSION['gradeerrorlog'] = '您尚未選擇級別';
		header("Location: ./index.php");
		exit();
	} else {
		$_SESSION['navigation'] = 'success';
		$_SESSION['footer'] = 'success';
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>學業資訊</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='studyinfo'>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>