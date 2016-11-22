<?php
	require_once("database_connect.php");
	
	if (!($ip == '140.123.220.135')) {
		//使用者訪問之目錄
		$request_url_address = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REQUEST_URI']);
		$sql = "INSERT INTO `global_request_url` (`request_url`, `ip_address`) VALUES ('".$request_url_address."', '".$ip."')";
		mysqli_query($mysqli_connecting, $sql);
	
		//使用者作業系統及瀏覽器資訊
		$user_browser = mysqli_real_escape_string($mysqli_connecting, $_SERVER['HTTP_USER_AGENT']);
		$sql = "SELECT `id` FROM `global_user_agent` WHERE `user_agent` = '".$user_browser."' AND `ip_address` = '".$ip."'";
		$result = mysqli_query($mysqli_connecting, $sql);
		if (mysqli_num_rows($result) == 0) {
			$sql = "INSERT INTO `global_user_agent` (`user_agent`, `ip_address`) VALUES ('".$user_browser."', '".$ip."')";
			mysqli_query($mysqli_connecting, $sql);
		}
	}
	
	require_once("global_visit_check.php");
	
	$sql = "SELECT `ip_address` FROM `global_bannedlist` WHERE `ip_address` = '".$ip."'";
	$result = mysqli_query($mysqli_connecting, $sql);
	$quantity = mysqli_num_rows($result);
	
	if ($quantity != 0 or isset($global_visit_deny)) { ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>禁止訪問</title>
	</head>
	<body>
		<center>
<?php if (isset($global_visit_deny)) { ?>
			<p>很抱歉，目前伺服器處於無法訪問狀態</p>
<?php } else { ?>
			<p>IP位址位於封鎖名單中</p>
			<p>封鎖原因：
			<?php
				$sql = "SELECT `reason` FROM `global_bannedlist` WHERE `ip_address` = '".$ip."'";
				$result = mysqli_query($mysqli_connecting, $sql);
				$reason = mysqli_fetch_array($result, MYSQLI_ASSOC);
				echo $reason['reason'];
			?></p>
<?php } ?>
		</center>
	</body>
</html>
<?php
		exit();
	}
?>