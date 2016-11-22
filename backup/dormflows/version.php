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
		<title>開發日誌</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='version'>
			<table>
				<th>版本號</th>
				<th>更新內容</th>
				<tr>
					<td>1.0.1</td>
					<td>
						<li>修復已知BUG</li>
						<li>新增查詢頁面異常警告</li>
					</td>
				</tr>
				<tr>
					<td>1.0.0</td>
					<td>
						<li>正式公開</li>
					</td>
				</tr>
			</table>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>