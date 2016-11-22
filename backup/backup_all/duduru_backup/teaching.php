<?
	session_start();
	if($_SERVER['REMOTE_ADDR']=="140.123.101.139"){
		echo "<p align='center'>請勿使用系上VPN。</p>";
		exit();
	}
	if(!isset($_SESSION['uid'])){
		header("Location:index.php");
		exit;
	}
?>
<html>
	<head>
		<title>使用說明</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href='css/main.css' rel='stylesheet' type='text/css' />
	</head>
	<body>
		<h1 align="Center">使用說明</h1>
		<div class=teaching_link>
			<a href='main.php'>回首頁</a>
			<a href='version.php'>開發日誌</a>
			<a href='logout.php'>登出</a>
		</div>
		<div class=teaching_body>
			<font size="5">
				<p><strong>首先，先在主頁上方選擇欲查看的分類</strong></p>
				<img src='./images/teaching/select.png'></br></br>
				<p><strong>接著，在您欲投票的那一列，點選右方的圖示即可完成投票</strong></p>
				<img src='./images/teaching/vote_sub.png'></br></br>
				<p><strong>如欲反悔，可在已投票的那列點選投票圖示，即可取消投票</strong></p>
				<img src='./images/teaching/cancel_voted.png'></br></br>
				<p><strong>是否非常簡單呀^_^ 在此祝您使用愉快~</strong></p>
			</font>
		</div>
		<?require("others/footer.php");?>
	</body>
</html>