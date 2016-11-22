<?php
	session_start();
	if (!isset($_SESSION['error_data'])) {
		header("Location: http://www.cs.ccu.edu.tw/~cys102u/index.php");
		exit();
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>From The New World</title>
	</head>
	<body>
		<center>
			<p><?php echo $_SESSION['error_data']; ?></p>
			<a href="http://www.cs.ccu.edu.tw/~cys102u/index.php">回首頁</a>
		</center>
	</body>
</html>
<?php
	unset($_SESSION['error_data']);
?>