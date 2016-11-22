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
		<title>使用說明</title>
		<link rel="shortcut icon" href="./images/icon.ico">
		<link rel="stylesheet" type="text/css" href="./css/main.css">
	</head>
	<body>
<?php require_once("./sources/navigation.php"); ?>
		<div class='teaching'>
			<ol>
				<li>請先下載本程式並解壓縮，<a href='./downloads.php' target='_blank'>點我前往下載頁面</a></li>
				<li>完成後，對程式點右鍵，點擊內容，並在上方的選單列選擇「相容性」</li>
				<li>將「特殊權限等級」的「以系統管理員的身分執行此程式」打勾並點擊右下方的確定</li>
				<img src='./images/teaching/permission.png'></br></br>
				<li>執行程式，開啟畫面如下</li>
				<img src='./images/teaching/welcome.png'></br></br>
				<li>網卡及IP會自動偵測，IP只會在第一次啟動本程式時做自動偵測(如未偵測成功，於下次啟動時仍會自動偵測)，爾後將會使用上一次的IP記錄(可手動更改)</li>
				<li>如欲自動停用網卡(流量保護)，請在上方框框內輸入欲停用之上限流量並將左方之框框打勾</li>
				<img src='./images/teaching/setting.png'></br></br>
				<li>點選右上角的叉叉即可讓此程式常駐於系統，如欲關閉此程式，請在右下角的通知欄內對程式點右鍵，選擇「離開」</li>
				<li>如欲開機自動啟動，請將本程式放置於下列目錄「C:\Users\使用者\AppData\Roaming\Microsoft\Windows\Start Menu\Programs\Startup\」</li>
			</ol>
		</div>
<?php require_once("./sources/footer.php"); ?>
	</body>
</html>