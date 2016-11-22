<?php
	function handle_database_error ($url, $error_message) {
		if (DEBUG_MODE) {
			if (!isset($_SESSION['error_data'])) {
				$_SESSION['error_data'] = '資料庫連結錯誤 (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() . $error_message;
			}
			header("Location: " . $url . "");
			exit();
		} else {
			if (!isset($_SESSION['error_data'])) {
				$_SESSION['error_data'] = '資料庫連結發生異常，請通知網站管理員';
			}
			header("Location: " . $url . "");
			exit();
		}
	}
	
	function handle_error ($url, $error_message) {
		if (!isset($_SESSION['error_data'])) {
			$_SESSION['error_data'] = $error_message;
		}
		header("Location: " . $url . "");
		exit();
	}
	
	function input_escape_string($link, $value) {
		$value = htmlspecialchars($value, ENT_QUOTES);
		$value = mysqli_real_escape_string($link, $value);
		return $value;
	}
?>