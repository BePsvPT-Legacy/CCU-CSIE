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
		
		$sql = "SELECT `searchlog` FROM `freshman_dormroommatesearchlog` WHERE `ip_address` = '".$ip."' AND `searchlog` = 'Failure!'";
		$result = mysqli_query($mysqli_connecting, $sql);
		$quantity = mysqli_num_rows($result);
		if ($quantity > 3) {
			$_SESSION['roommatedeny'] = '查詢紀錄異常，位於禁止訪問名單中';
		}
		
		if (isset($_POST['addroom']) and isset($_POST['myname']) and isset($_POST['roomid']) and isset($_POST['roombed']) and isset($_POST['mcaptcha']) and !isset($_SESSION['roommatedeny'])) {
			$myname = htmlspecialchars($_POST['myname'], ENT_QUOTES);
			$roomid = htmlspecialchars($_POST['roomid'], ENT_QUOTES);
			$roombed = htmlspecialchars($_POST['roombed'], ENT_QUOTES);
			$captcha = htmlspecialchars($_POST['mcaptcha'], ENT_QUOTES);
			$myname = mysqli_real_escape_string($mysqli_connecting, $myname);
			$roomid = mysqli_real_escape_string($mysqli_connecting, $roomid);
			$roombed = mysqli_real_escape_string($mysqli_connecting, $roombed);
			$captcha = mysqli_real_escape_string($mysqli_connecting, $captcha);
			
			if ($myname == NULL) {
				$_SESSION['roommateerrorlog'] = '請輸入姓名';
			} else if (strlen($myname) < 6 or strlen($myname) > 12) {
				$_SESSION['roommateerrorlog'] = '姓名格式錯誤，請重新輸入';
			} else if ($roomid == NULL) {
				$_SESSION['roommateerrorlog'] = '請輸入房號';
			} else if (!preg_match("/^[1-5][1-9][0-1][0-9]$/", $roomid)) {
				$_SESSION['roommateerrorlog'] = '房號格式錯誤，請重新輸入';
			} else if ($roombed == NULL) {
				$_SESSION['roommateerrorlog'] = '請選擇床位';
			} else if (!preg_match("/^[0-3]$/", $roombed)) {
				$_SESSION['roommateerrorlog'] = '床位格式錯誤，請重新選擇';
			} else if ($captcha != $_SESSION['captcha_code']) {
				$_SESSION['roommateerrorlog'] = '驗證碼錯誤，請重新輸入';
			}
			
			if (!isset($_SESSION['roommateerrorlog'])) {
				$sql = "SELECT `id` FROM `freshman_dormroommate` WHERE `grade` = '".$_SESSION['grade'][$_SESSION['mygrade']]."' AND `room_id` = '".$roomid."' AND `room_bed` = '".$roombed."'";
				$result = mysqli_query($mysqli_connecting, $sql);
				$quantity = mysqli_num_rows($result);
				if ($quantity > 0) {
					$_SESSION['roommateerrorlog'] = '床號已存在，如有問題，請通知管理員查詢';
				} else {
					$sql = "INSERT INTO `freshman_dormroommate` (`grade`, `name`, `room_id`, `room_bed`, `ip_address`) VALUES ('".$_SESSION['grade'][$_SESSION['mygrade']]."', '".$myname."', '".$roomid."', '".$roombed."', '".$ip."')";
					$result = mysqli_query($mysqli_connecting, $sql);
					if ($result) {
						$success = '1';
					} else {
						$success = '-1';
					}
				}
			}
		} else if (isset($_POST['searchroomid']) and isset($_POST['searchmyname']) and isset($_POST['searchmcaptcha']) and !isset($_SESSION['roommatedeny'])) {
			$searchroomid = htmlspecialchars($_POST['searchroomid'], ENT_QUOTES);
			$searchmyname = htmlspecialchars($_POST['searchmyname'], ENT_QUOTES);
			$searchcaptcha = htmlspecialchars($_POST['searchmcaptcha'], ENT_QUOTES);
			$searchroomid = mysqli_real_escape_string($mysqli_connecting, $searchroomid);
			$searchmyname = mysqli_real_escape_string($mysqli_connecting, $searchmyname);
			$searchcaptcha = mysqli_real_escape_string($mysqli_connecting, $searchcaptcha);
			
			if ($searchroomid == NULL) {
				$_SESSION['roommateerrorlog'] = '請輸入房號';
			} else if (!preg_match("/^[1-5][1-9][0-1][0-9]$/", $searchroomid)) {
				$_SESSION['roommateerrorlog'] = '房號格式錯誤，請重新輸入';
			} else if ($searchmyname == NULL) {
				$_SESSION['roommateerrorlog'] = '請輸入姓名';
			} else if (strlen($searchmyname) < 6 or strlen($searchmyname) > 12) {
				$_SESSION['roommateerrorlog'] = '姓名格式錯誤，請重新輸入';
			} else if ($searchcaptcha != $_SESSION['captcha_code']) {
				$_SESSION['roommateerrorlog'] = '驗證碼錯誤，請重新輸入';
			}
			
			if (!isset($_SESSION['roommateerrorlog'])) {
				$sql = "SELECT `id` FROM `freshman_dormroommate` WHERE `grade` = '".$_SESSION['grade'][$_SESSION['mygrade']]."' AND `name` = '".$searchmyname."' AND `room_id` = '".$searchroomid."'";
				$result = mysqli_query($mysqli_connecting, $sql);
				$quantity = mysqli_num_rows($result);
				if ($quantity == 0) {
					$sql = "INSERT INTO `freshman_dormroommatesearchlog` (`grade`, `name`, `room_id`, `searchlog`, `ip_address`) VALUES ('".$_SESSION['grade'][$_SESSION['mygrade']]."', '".$searchmyname."', '".$searchroomid."', 'Failure!', '".$ip."')";
					$result = mysqli_query($mysqli_connecting, $sql);
					$_SESSION['roommateerrorlog'] = '身分驗證失敗';
				} else {
					$sql = "INSERT INTO `freshman_dormroommatesearchlog` (`grade`, `name`, `room_id`, `searchlog`, `ip_address`) VALUES ('".$_SESSION['grade'][$_SESSION['mygrade']]."', '".$searchmyname."', '".$searchroomid."', 'Success!', '".$ip."')";
					$result = mysqli_query($mysqli_connecting, $sql);
					$rommbedname = array("無資料", "無資料", "無資料", "無資料");
					$sql = "SELECT `name`, `room_bed` FROM `freshman_dormroommate` WHERE `grade` = '".$_SESSION['grade'][$_SESSION['mygrade']]."' AND `room_id` = '".$searchroomid."' ORDER BY `room_bed` ASC";
					$dblink = mysqli_query($mysqli_connecting, $sql);
					while ($result = mysqli_fetch_array($dblink, MYSQLI_ASSOC)) {
						switch ($result['room_bed']) {
							case '0':
							case '1':
							case '2':
							case '3':
								$rommbedname[$result['room_bed']] = $result['name'];
								break;
							default :
								break;
						}
					}
				}
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>宿舍找室友</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
		<link rel="stylesheet" type="text/css" href="./css/dormroommate.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='dormroommate'>
<?php if (!isset($_SESSION['roommatedeny']) and ((isset($_POST['search']) and isset($_SESSION['roommateerrorlog'])) or (isset($_POST['addroom']) and !isset($_SESSION['roommateerrorlog'])) or (!isset($_POST['send']) and !isset($_POST['search']) and !isset($_SESSION['roommateerrorlog'])))) { ?>
			<p>目前所在級別：<?php echo $_SESSION['gradename'][$_SESSION['mygrade']]; ?></p>
<?php
	if ($success == '1') {
		echo "<p>\t\t\t\t新增成功</p>";
		unset($success);
	} else if ($success == '-1') {
		echo "<p>\t\t\t\t資料庫連線錯誤，請通知網站管理員</p>";
		unset($success);
	}
?>
			<div class='roommateadd'>
				<h4>新增資料</h4>
				<form method='POST'>
					<input type='submit' name='send' value='新增資料'>
				</form>
			</div>
			<div class='roommatesearch'>
				<h4>室友查詢</h4>
				<form method='POST'>
					<div class='roomsearch'>
						<div>
							房號：
							<?php
								if (isset($_SESSION['roommateerrorlog']) and $searchroomid != NULL) {
									echo "<input type='text' name='searchroomid' value='".$searchroomid."' maxlength='4' autocomplete='off'>";
									unset($searchroomid);
								} else {
									echo "<input type='text' name='searchroomid' maxlength='4' autocomplete='off'>";
								}
							?>
							
							姓名：
							<?php
								if (isset($_SESSION['roommateerrorlog']) and $searchmyname != NULL) {
									echo "<input type='text' name='searchmyname' value='".$searchmyname."' maxlength='5' autocomplete='off'>";
									unset($searchmyname);
								} else {
									echo "<input type='text' name='searchmyname' maxlength='5' autocomplete='off'>";
								}
							?>
							
						</div>
						<br>
						<div>
							驗證碼：
							<input type='text' name='searchmcaptcha' maxlength='5' autocomplete='off'>
							<img src='../scripts/captcha/captcha.php' alt='Captcha'>
						</div>
					</div>
					<br>
					<div class='searchbutton'>
						<input type='submit' name='search' value='查詢'>
					</div>
				</form>
				<?php
					if (isset($_SESSION['roommateerrorlog'])) {
						echo "<p>".$_SESSION['roommateerrorlog']."</p>";
						unset($_SESSION['roommateerrorlog']);
					}
				?>
				
			</div>
			<div class='roommateinfo'>
				<h4>寢室概況</h4>
<?php
	$onepeople = 0;
	$twopeople = 0;
	$threepeople = 0;
	$fourpeople = 0;
	
	function mysqli_result($res, $row, $field=0) { 
		$res->data_seek($row); 
		$datarow = $res->fetch_array(); 
		return $datarow[$field]; 
	}
	
	$sql = "SELECT COUNT(`room_id`) FROM `freshman_dormroommate` WHERE `grade` = '".$_SESSION['grade'][$_SESSION['mygrade']]."' GROUP BY `room_id` ASC";
	$dblink = mysqli_query($mysqli_connecting, $sql);
	$quantity = mysqli_num_rows($dblink);
	for($i=0;$i<$quantity;$i++){
		$tmp = mysqli_result($dblink,$i,0);
		switch ($tmp) {
			case 1:
				$onepeople++;
				break;
			case 2:
				$twopeople++;
				break;
			case 3:
				$threepeople++;
				break;
			case 4:
				$fourpeople++;
				break;
			default :
		}
	}
?>
				<div>
					冒險將啟程：<?php echo $fourpeople; ?> 間<br>
					明星三缺一：<?php echo $threepeople; ?> 間<br>
					雙排好夥伴：<?php echo $twopeople; ?> 間<br>
					孤單一人中：<?php echo $onepeople; ?> 間
				</div>
			</div>
<?php } else if (!isset($_SESSION['roommatedeny']) and isset($_POST['search']) and !isset($_SESSION['roommateerrorlog'])) { ?>
			<div class='searchresult'>
				<h3>寢室房號：<?php echo $searchroomid; ?></h3>
				<table>
					<th>床位</th>
					<th>姓名</th>
					<tr>
						<td>A</td>
						<td><?php echo $rommbedname[0]; ?></td>
					</tr>
					<tr>
						<td>B</td>
						<td><?php echo $rommbedname[1]; ?></td>
					</tr>
					<tr>
						<td>C</td>
						<td><?php echo $rommbedname[2]; ?></td>
					</tr>
					<tr>
						<td>D</td>
						<td><?php echo $rommbedname[3]; ?></td>
					</tr>
				</table>
			</div>
<?php } else if (!isset($_SESSION['roommatedeny']) and (!isset($_POST['search']) and isset($_POST['send']) or (isset($_POST['addroom']) and isset($_SESSION['roommateerrorlog'])))) { ?>
			<div class='addroom'>
				<form method='POST'>
					<div class='myname'>
						姓名：
						<?php
							if (isset($_SESSION['roommateerrorlog']) and $myname != NULL) {
								echo "<input type='text' name='myname' value='".$myname."' maxlength='5' autocomplete='off'>";
								unset($myname);
							} else {
								echo "<input type='text' name='myname' maxlength='5' autocomplete='off'>";
							}
						?>
						
					</div>
					<br>
					<div class='roomid'>
						房號：
						<?php
							if (isset($_SESSION['roommateerrorlog']) and $roomid != NULL) {
								echo "<input type='text' name='roomid' value='".$roomid."' maxlength='4' autocomplete='off'>";
								unset($roomid);
							} else {
								echo "<input type='text' name='roomid' maxlength='4' autocomplete='off'>";
							}
						?>
						
					</div>
					<br>
					<div class='roombed'>
						床位：
						<select name='roombed'>
<?php
	$roombedname = array("A", "B", "C", "D");
	$roombednamelength = count($roombedname);
	for ($i=0;$i<$roombednamelength;$i++) {
		if ($i == $roombed) {
			echo "\t\t\t\t\t\t\t<option value=".$i." selected>".$roombedname[$i]."</option>\n";
			unset($roombed);
		} else {
			echo "\t\t\t\t\t\t\t<option value=".$i.">".$roombedname[$i]."</option>\n";
		}
	}
?>
						</select>
					</div>
					<br>
					<div class='authenticate'>
						驗證碼：
						<input type='text' name='mcaptcha' maxlength='5' autocomplete='off'>
						<img src='../scripts/captcha/captcha.php' alt='Captcha'>
					</div>
					<br>
					<div class='addroombutton'>
						<input type='submit' name='addroom' value='新增資訊'>
					</div>
				</form>
				<?php
					if (isset($_SESSION['roommateerrorlog'])) {
						echo "<p>".$_SESSION['roommateerrorlog']."</p>";
						unset($_SESSION['roommateerrorlog']);
					}
				?>
				
			</div>
<?php
	} else {
		if (isset($_SESSION['roommateerrorlog'])) {
			echo "\t\t<p>".$_SESSION['roommateerrorlog']."</p>";
			unset($_SESSION['roommateerrorlog']);
		} else if (isset($_SESSION['roommatedeny'])) {
			echo "\t\t<p>".$_SESSION['roommatedeny']."</p>";
		}
	}
?>

		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>