<?php
	session_start();
	require_once("global_config/globalbannedlist.php");
	$_SESSION['navigation'] = 'success';
	$_SESSION['footer'] = 'success';
	
	if (isset($_POST['agreerule']) and isset($_POST['agree'])) {
		if ($_POST['agreerule'] == 'on' and $_POST['agree'] == '下載') {
			$_SESSION['permission']= 'access';
			header ("Location: ./sources/download.php?file=DormFlows_1.0.1.zip");
			exit();
		} else {
			$sql = "INSERT INTO `dormflows_downloadlog` (`download_log`, `ip_address`) VALUES ('Option_Agree Failure!', '".$ip."')";
			$result = mysqli_query($mysqli_connecting, $sql);
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>檔案下載</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='download'>
			<p>本程式無法保證使用後即一定不會超流，使用者仍應養成良好的網路使用習慣，本網站及本程式不負起超流斷網的責任</p>
			<p>此程式僅供學術上的交流，請勿用於包括但不限於營利、非法行為，任何包括但不限於後果、法律責任由當事人自行負責，本網站及本程式不負起任何責任</p>
			<form method='POST'>
				<input type='checkbox' class='checkbutton' name='agreerule' id='agreerule' onchange="javascript:document.getElementById('agree').disabled = ! document.getElementById('agreerule').checked;">
				我同意<br>版本號：1.0.1
				<input type='submit' class='sendbutton' name='agree' id='agree' value='下載' disabled>
			</form>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>