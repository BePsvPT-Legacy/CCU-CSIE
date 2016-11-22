<?php
	session_start();
	require_once("global_config/globalbannedlist.php");
	$_SESSION['navigation'] = 'success';
	$_SESSION['footer'] = 'success';
	
	if (isset($_POST['contenttype']) and isset($_POST['content']) and isset($_POST['email']) and isset($_POST['mcaptcha'])) {
		$contenttype = htmlspecialchars($_POST['contenttype'], ENT_QUOTES);
		$content = htmlspecialchars($_POST['content'], ENT_QUOTES);
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
		$contenttype = mysqli_real_escape_string($mysqli_connecting, $contenttype);
		$content = mysqli_real_escape_string($mysqli_connecting, $content);
		$email = mysqli_real_escape_string($mysqli_connecting, $email);
		
		if ($contenttype == NULL) {
			$_SESSION['contactuserrorlog'] = '類型錯誤，請重新選擇';
		} else if (!preg_match("/^[0-4]$/", $contenttype)) {
			$_SESSION['contactuserrorlog'] = '類型錯誤，請重新選擇';
		} else if ($content == NULL) {
			$_SESSION['contactuserrorlog'] = '請輸入內容';
		} else if ($email == NULL) {
			$_SESSION['contactuserrorlog'] = '請輸入 E-mail';
		} else if (!preg_match("/^([a-zA-Z0-9]+)@(([a-zA-Z0-9]+\.)+[a-z]{2,})$/", $email)) {
			$_SESSION['contactuserrorlog'] = 'E-mail 格式錯誤';
		} else if ($_POST['mcaptcha'] != $_SESSION['captcha_code']) {
			$_SESSION['contactuserrorlog'] = '驗證碼錯誤，請重新輸入';
		}
		
		if (!isset($_SESSION['contactuserrorlog'])) {
			$sql = "INSERT INTO `dormflows_contactus` (`contenttype`, `content`, `email`, `ip_address`) VALUES ('".$contenttype."', '".$content."', '".$email."', '".$ip."')";
			$result = mysqli_query($mysqli_connecting, $sql);
			if ($result) {
				$success = '1';
			} else {
				$success = '-1';
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>聯絡我們</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='contactus'>
			<form method='POST'>
				<div class='contenttype'>
					類型：
					<select name='contenttype'>
<?php
	$description = array("程式問題回報", "程式功能建議", "網頁問題回報", "網頁頁面建議", "其他");
	$quantity = count($description);
	if (isset($_SESSION['contactuserrorlog']) and preg_match("/^[0-4]$/", $contenttype)) {
		for ($i=0;$i<$quantity;$i++) {
			if ($i == $contenttype) {
				echo "\t\t\t\t\t\t<option value=".$i." selected>".$description[$i]."</option>\n";
			} else {
				echo "\t\t\t\t\t\t<option value=".$i.">".$description[$i]."</option>\n";
			}
		}
	} else { 
		for ($i=0;$i<5;$i++) {
			echo "\t\t\t\t\t\t<option value=".$i.">".$description[$i]."</option>\n";
		}
	}
?>
					</select>
				</div>
				<br>
				<div class='content'>
					內容：
					<?php if (isset($_SESSION['contactuserrorlog']) and $content != NULL) {
						echo "<textarea rows='8' cols='60' name='content'>".$content."</textarea>";
					} else { 
						echo "<textarea rows='8' cols='60' name='content'></textarea>";
					} ?>
					
				</div>
				<br>
				<div class='email'>
					E-mail：
					<?php if (isset($_SESSION['contactuserrorlog']) and $email != NULL) {
						echo "<input type='text' name='email' value='".$email."' maxlength='128' autocomplete='off'>";
					} else { 
						echo "<input type='text' name='email' maxlength='128' autocomplete='off'>";
					} ?>
					
				</div>
				<br>
				<div class='authenticate'>
					驗證碼：
					<input type='text' name='mcaptcha' maxlength='5' autocomplete='off'>
					<img src='../scripts/captcha/captcha.php' alt='Captcha'>
				</div>
				<br>
				<div class='button'>
					<input type='submit' name='send' value='提交'>
					<input type='reset' name='reset' value='清除'>
				</div>
			</form>
			<?php
				if ($success == '1') {
					echo "<p>提交成功，將於收到後盡快回覆您</p>";
				} else if ($success == '-1') {
					echo "<p>提交資料時發生錯誤，請通知網站管理員</p>";
				} else if (isset($_SESSION['contactuserrorlog'])) {
					echo "<p>".$_SESSION['contactuserrorlog']."</p>";
					unset($_SESSION['contactuserrorlog']);
				}
			?>
			
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>