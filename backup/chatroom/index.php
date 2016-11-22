<?php
	if (!isset($prefix)) {
		$prefix = '../';
	}
	header("Location: " . $prefix . "chatroom/channel.php");
	exit();
?>