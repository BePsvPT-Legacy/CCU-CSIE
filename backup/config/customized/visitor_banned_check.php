<?php
	$sql = "SELECT `id` FROM `ip_banned_list` WHERE `ip` = '".$ip."'";
	$result = mysqli_query($mysqli_connecting, $sql);
	if (!$result) {
		handle_database_error($web_url, mysqli_error($mysqli_connecting));
		exit();
	} else {
		if (mysqli_num_rows($result) != 0) {
			handle_error($web_url, '禁止訪問：IP位址位於封鎖名單中');
			exit();
		}
	}
?>