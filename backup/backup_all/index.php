<?php
	session_start();
	require_once("./global_config/globalbannedlist.php");
	$_SESSION['navigation'] = 'success';
	//$_SESSION['footer'] = 'success';
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>From the New World</title>
		<link rel="stylesheet" type="text/css" href="./main/css/main.css">
	</head>
	<body>
<?php require_once("./main/sources/navigation.php"); ?>
	</body>
</html>