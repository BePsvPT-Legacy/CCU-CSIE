<?php
	session_start();
	if ($_SESSION['footer'] != 'success') {
		header("Location: ../index.php");
		exit();
	} else {
?>
		<div class='footer'>
			<p>
				本網站最佳瀏覽解析度 1920×1080，並使用 <a href='http://www.google.com/intl/zh-TW/chrome/' target='_blank'>Google Chrome</a> 瀏覽器</br></br>
				Web Created by：Freedom / Copyright © 2014
			</p>
		</div>
<?php
	}
	unset($_SESSION['footer']);
?>