<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array();
	display_head("個人中心", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "user/sources/navigation.php";
?>
		<div>
			<div>
				<h3>帳號資料</h3>
				<table class="pure-table pure-table-bordered pure-table-user-index">
					<tbody>
<?php
	$sql = "SELECT `username`, `nickname`, `email` FROM `accounts` WHERE `id` = ?";
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		$stmt->close();
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$result = array();
		$stmt->bind_param('s', $_SESSION['cid']);
		$stmt->execute();
		$stmt->bind_result($result[0], $result[1], $result[2]);
		$i = 0;
		$item = array("帳號：", "暱稱：", "信箱：");
		if ($stmt->fetch()) {
			foreach($item as $item) {
				$result[$i] = $result[$i];
				echo <<<EOD
						<tr>
							<td>$item</td>
							<td>$result[$i]</td>
						</tr>\n
EOD;
				$i++;
			}
		}
		$stmt->close();
	}
?>
					</tbody>
				</table>
			</div>
			<div>
				<h3>帳號登入紀錄</h3>
				<table class="pure-table pure-table-user-index">
					<thead>
						<tr>
							<th>#</th>
							<th>IP來源</th>
							<th>登入時間</th>
						</tr>
					</thead>
					<tbody>
<?php
	$sql = "SELECT `ip`, `time` FROM `web_login_log` WHERE `cid` = ? ORDER BY `id` DESC LIMIT 5";
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		$stmt->close();
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$stmt->bind_param('s', $_SESSION['cid']);
		$stmt->execute();
		$stmt->bind_result($result_ip, $result_time);
		$i = 1;
		while ($stmt->fetch()) {
			if ($i % 2 == 1) {
				echo <<<EOD
						<tr class="pure-table-odd">\n
EOD;
			} else {
				echo <<<EOD
						<tr>\n
EOD;
			}
			echo <<<EOD
							<td>$i</td>
							<td>$result_ip</td>
							<td>$result_time</td>
						</tr>\n
EOD;
			$i++;
		}
		$stmt->close();
	}
?>
					</tbody>
				</table>
			</div>
		</div>
<?php
	display_footer();
?>