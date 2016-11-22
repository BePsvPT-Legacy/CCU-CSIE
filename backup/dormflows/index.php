<?php
	session_start();
	require_once("global_config/globalbannedlist.php");
	$_SESSION['navigation'] = 'success';
	$_SESSION['footer'] = 'success';
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>宿網流量守護天使</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='main'>
			<p>
				作者的話：
				<?
					if(rand(0,1)) {
						echo "NullPointerException";
					} else {
						echo "Segmentation Fault";
					}
				?>
			
			</p>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>