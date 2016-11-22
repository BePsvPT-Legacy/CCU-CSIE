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
		<title>開發日誌</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href='css/main.css' rel='stylesheet' type='text/css' />
	</head>
	<body>
		<h1 align="Center">開發日誌</h1>
		<div class=version_link>
			<a href='main.php'>回首頁</a>
			<a href='teaching.php'>使用說明</a>
			<a href='logout.php'>登出</a>
		</div>
		<div class=version_body>
			<font size="4">
				<p><strong>2013/12/25</p></strong>
				<ul>
					<li>新增使用說明頁面</br>
					<li>新增開發日誌頁面</br>
				</ul>
				<p><strong>2013/12/22</p></strong>
				<ul>
					<li>網站正式上線</br>
				</ul>
				<p><strong>2013/12/21</p></strong>
				<ul>
					<li>新增反悔按鈕</br>
					<li>補充教師未定的資料</br>
					<li>資料庫重設</br>
					<li>刪除班別的區分</br>
					<li>調整PHP相容性</br>
				</ul>
				<p><strong>2013/12/20</p></strong>
				<ul>
					<li>修正圖片顯示錯誤問題</br>
					<li>修正無法登出問題</br>
					<li>修正重複投票問題</br>
					<li>修改計算排名方法</br>
				</ul>
				<p><strong>2013/12/19</p></strong>
				<ul>
					<li>系統封測</br>
				</ul>
			</font>
		</div>
		<?require("others/footer.php");?>
	</body>
</html>