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
		if (isset($_POST['add']) and isset($_POST['departmentname']) and isset($_POST['departmentlink']) and isset($_POST['mcaptcha'])) {
			$depname = mysqli_real_escape_string($mysqli_connecting, $_POST['departmentname']);
			$deplink = mysqli_real_escape_string($mysqli_connecting, $_POST['departmentlink']);
			$captcha = mysqli_real_escape_string($mysqli_connecting, $_POST['mcaptcha']);
			$depname = htmlspecialchars($depname, ENT_QUOTES);
			$deplink = htmlspecialchars($deplink, ENT_QUOTES);
			$captcha = htmlspecialchars($captcha, ENT_QUOTES);
			
			if ($depname == NULL) {
				$_SESSION['departmenterrorlog'] = '請輸入系所名稱';
			} else if ($deplink == NULL) {
				$_SESSION['departmenterrorlog'] = '請輸入系版連結';
			} else if (preg_match("/[0-9]{16}/", $deplink) and !preg_match("/groups\/[a-zA-Z\.]+\/{0,1}$/", $deplink)) {
				$_SESSION['departmenterrorlog'] = '系版連結格式錯誤，目前僅支援FB社團連結';
			} else if (!preg_match("/[0-9]{15}/", $deplink) and !preg_match("/groups\/[a-zA-Z\.]+\/{0,1}$/", $deplink)) {
				$_SESSION['departmenterrorlog'] = '系版連結格式錯誤，目前僅支援FB社團連結';
			} else if ($captcha != $_SESSION['captcha_code']) {
				$_SESSION['departmenterrorlog'] = '驗證碼錯誤，請重新輸入';
			} else {
				if (preg_match("/[0-9]{15}/", $deplink)) {
					$length = strlen($deplink);
					$deplink = str_split($deplink);
					for ($i=0;$i<$length;$i++) {
						for ($m=0;$m<15;$m++) {
							if ($deplink[$i+$m] < '0' or $deplink[$i+$m] > '9') {
								break;
							}
						}
						if ($m == 15) {
							$temp = "";
							for ($n=0;$n<15;$n++) {
								$temp = $temp.$deplink[$i+$n];
							}
							unset($deplink);
							$deplink = $temp;
							if (preg_match("/[0-9]{15}/", $deplink)) {
								$deplink = "https://www.facebook.com/groups/".$deplink."/";
								break;
							} else {
								$_SESSION['departmenterrorlog'] = '系版連結格式錯誤，目前僅支援FB社團連結';
								break;
							}
						}
					}
				} else {
					$deplink = preg_replace("/.*groups\//", '', $deplink);
					$deplink = preg_replace("/[^a-zA-Z0-9\.]/", '', $deplink);
					if ($deplink == NULL) {
						$_SESSION['departmenterrorlog'] = '系版連結格式錯誤，目前僅支援FB社團連結';
					} else {
						$deplink = "https://www.facebook.com/groups/".$deplink."/";
					}
				}
			}
			
			if (!isset($_SESSION['departmenterrorlog'])) {
				$sql = "INSERT INTO `freshman_departments` (`grade`, `department_name`, `department_link`, `ip_address`) VALUES ('".$_SESSION['grade'][$_SESSION['mygrade']]."', '".$depname."', '".$deplink."', '".$ip."')";
				$result = mysqli_query($mysqli_connecting, $sql);
				if ($result) {
					$success = '1';
				} else {
					$success = '-1';
				}
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>系版資訊</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
		<link rel="stylesheet" type="text/css" href="./css/departmentinfo.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='departmentinfo'>
<?php
	if (!isset($_POST['send']) and !isset($_SESSION['departmenterrorlog'])) { 
?>
			<p>目前所在級別：<?php echo $_SESSION['gradename'][$_SESSION['mygrade']]; ?></p>
			<div class='infoshow'>
<?php
	if ($success == '1') {
		echo "<p>\t\t\t\t新增成功</p>";
	} else if ($success == '-1') {
		echo "<p>\t\t\t\t資料庫連線錯誤，請通知網站管理員</p>";
	}
?>
				<table>
					<th>系所名稱</th>
					<th>系版連結</th>
<?php
	$sql = "SELECT `department_name`, `department_link` FROM `freshman_departments` WHERE `grade` = '".$_SESSION['grade'][$_SESSION['mygrade']]."' ORDER BY `id` ASC";
	$dblink = mysqli_query($mysqli_connecting, $sql);
	while ($result = mysqli_fetch_array($dblink, MYSQLI_ASSOC)) {
?>
					<tr>
						<td><?php echo $result['department_name']; ?></td>
						<td><?php echo "<a href='".$result['department_link']."' target='_blank'>"; ?>點我前往</a></td>
					</tr>
<?php
	}
?>
				</table>
			</div>
			<div class='infoadd'>
				<form method='POST'>
					<input type='submit' name='send' value='新增系所'>
				</form>
			</div>
<?php
	} else {
?>
			<div class='adddepartment'>
				<form method='POST'>
					<div class='name'>
						系所名稱：
						<?php
							if (isset($_SESSION['departmenterrorlog'])) {
								echo "<input type='text' name='departmentname' value='".$depname."' maxlength='32' autocomplete='off'>";
							} else {
								echo "<input type='text' name='departmentname' maxlength='32' autocomplete='off'>";
							}
						?>
						
					</div>
					<br>
					<div class='link'>
						系版連結：
						<?php
							if (isset($_SESSION['departmenterrorlog'])) {
								echo "<input type='text' name='departmentlink' value='".$deplink."' maxlength='128' autocomplete='off'>";
							} else {
								echo "<input type='text' name='departmentlink' maxlength='128' autocomplete='off'>";
							}
						?>
						
					</div>
					<br>
					<div class='authenticate'>
						驗證碼：
						<input type='text' name='mcaptcha' maxlength='5' autocomplete='off'>
						<img src='../scripts/captcha/captcha.php' alt='Captcha'>
					</div>
					<br>
					<div class='button'>
						<input type='submit' name='add' value='新增系所'>
					</div>
				</form>
				<?php
					if (isset($_SESSION['departmenterrorlog'])) {
						echo "<p>".$_SESSION['departmenterrorlog']."</p>";
						unset($_SESSION['departmenterrorlog']);
					}
				?>
				
			</div>
<?php
	}
?>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>