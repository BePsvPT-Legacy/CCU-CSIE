<?php
	session_start();
	if ($_SESSION['navigation'] != 'success') {
		header("Location: ../index.php");
		exit();
	} else {
?>
		<header>
			<nav>
				<ul>
					<li><a href='../index.php' title='主選單'>主選單</a></li>
					<li><a href='./index.php' title='首頁'>首頁</a></li>
					<li><a href='./enrollinfo.php' title='入學注意事項'>入學注意事項</a></li>
					<li><a href='./studyinfo.php' title='學業資訊'>學業資訊</a></li>
					<li><a href='./departmentinfo.php' title='系版資訊'>系版資訊</a></li>
					<li><a href='./dorminfo.php' title='宿舍資訊'>宿舍資訊</a></li>
					<li><a href='./dormroommate.php' title='宿舍找室友'>宿舍找室友</a></li>
					<li><a href='./otherinfo.php' title='其他'>其他</a></li>
				</ul>
			</nav>
		</header>
<?php
	}
	unset($_SESSION['navigation']);
?>