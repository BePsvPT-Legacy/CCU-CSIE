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
		<title>注意事項</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='notice'>
			<ol>
				<li>在Windows作業系統下，停用網卡必須有系統管理員的權限</li>
				<li>執行本程式需安裝「<a href='http://www.microsoft.com/zh-tw/download/details.aspx?id=17718' target='_blank'>Microsoft .NET Framework 4.0</a>」以上的版本</li>
				<li>本程式會在流量超過7000、7500、8000時分別跳出警告視窗提醒使用者</li>
				<li>當發生超流狀況並停用網卡後，點擊「重啟」即可啟用網卡</li>
				<li>此程式無法確保流量顯示是即時的，此狀況受限於宿網流量查詢頁面</li>
				<li>如發現「目前流量」有不正常的顯示，如：突然歸零，即代表發生異常，此時需自行注意宿網流量</li>
				<li>如宿網流量查詢的頁面更新(改版)，此程式很可能會失效，到時會做更新</li>
				<li>流量建議：如果是用來看短片、逛網頁、或是玩遊戲，可設定在 7500 上下</li>
				<li>流量建議：如果是用來看直播(非高清畫質)、看電影(非高清畫質)，可設定在 6500 上下</li>
				<li>流量建議：如果是用來下載檔案、或流量需求大者，可設定在 5500 上下或更低</li>
				<li>流量建議：如是深夜掛機下載檔案，請將流量設定為 「8000-尚餘檔案大小」</li>
			</ol>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>