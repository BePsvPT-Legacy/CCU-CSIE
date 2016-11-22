<?php
	session_start();
	if ($_SESSION['navigation'] != 'success') {
		require_once("../global_config/database_connect.php");
		$sql = "INSERT INTO `dormflows_errorlog` (`errorlog`, `ip_address`) VALUES ('嘗試訪問 /dormflows/sources/navigation.php 頁面', '".$ip."')";
		mysqli_query($mysqli_connecting, $sql);
		header("Location: ../index.php");
		exit();
	} else {
?>
		<header>
			<nav>
				<ul>
					<li><a href='../index.php' title='主選單'>主選單</a></li>
					<li><a href='./index.php' title='首頁'>首頁</a></li>
					<li><a href='./teaching.php' title='使用說明'>使用說明</a></li>
					<li><a href='./notice.php' title='注意事項'>注意事項</a></li>
					<li><a href='./downloads.php' title='檔案下載'>檔案下載</a></li>
					<li><a href='./version.php' title='開發日誌'>開發日誌</a></li>
					<li><a href='./contactus.php' title='聯絡我們'>聯絡我們</a></li>
				</ul>
			</nav>
		</header>
<?php
	}
	unset($_SESSION['navigation']);
?>