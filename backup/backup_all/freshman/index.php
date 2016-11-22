<?php
	session_start();
	require_once("../global_config/globalbannedlist.php");
	$_SESSION['navigation'] = 'success';
	$_SESSION['footer'] = 'success';
	
	$_SESSION['grade'] = array("106", "107", "108", "109");
	$_SESSION['gradename'] = array("106級", "107級", "108級", "109級");
	$_SESSION['quantity'] = count($_SESSION['grade']);
	
	if (isset($_POST['mygrade']) and !isset($_SESSION['gradeerrorlog'])) {
		$mygrade = mysqli_real_escape_string($mysqli_connecting, $_POST['mygrade']);
		
		if ($mygrade == NULL or $mygrade < 0 or $mygrade >= $_SESSION['quantity']) {
			$_SESSION['gradeerrorlog'] = '級別錯誤，請重新選擇';
			if (isset($_SESSION['mygrade'])) {
				unset($_SESSION['mygrade']);
			}
		}
		
		if (!isset($_SESSION['gradeerrorlog'])) {
			unset($_SESSION['mygrade']);
			$_SESSION['mygrade'] = $mygrade;
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>新鮮人導航系統</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
		<link rel="stylesheet" type="text/css" href="./css/grade.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='grade'>
			<form method='POST'>
				<div>
					級別：
					<select name='mygrade'>
<?php
	for ($i=0;$i<$_SESSION['quantity'];$i++) {
		if ($i == $_SESSION['mygrade']) {
			echo "\t\t\t\t\t\t<option value=".$i." selected>".$_SESSION['gradename'][$i]."</option>\n";
		} else {
			echo "\t\t\t\t\t\t<option value=".$i.">".$_SESSION['gradename'][$i]."</option>\n";
		}
	}
?>
					</select>
				</div>
				<br>
				<div>
					<input type='submit' name='send' value='提交'>
				</div>
			</form>
<?php if (isset($_SESSION['gradeerrorlog'])) { ?>
			<p><?php echo $_SESSION['gradeerrorlog']; unset($_SESSION['gradeerrorlog']); ?></p>
<?php } else if (isset($_SESSION['mygrade'])) { ?>
			<p>目前選擇級別：<?php echo $_SESSION['gradename'][$_SESSION['mygrade']]; ?></p>
<?php } ?>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>