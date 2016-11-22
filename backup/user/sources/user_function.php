<?php
	// register.php
	function user_register($username, $pw, $pw_check, $nickname, $email) {
		if (!preg_match("/^[a-zA-Z0-9]{6,20}$/", $username)) {
			return '帳號格式錯誤，帳號只允許由英文字母和數字組成，並且長度為 6~20 字';
		} else if (!preg_match("/^[a-z0-9]{128}$/", $pw) or !preg_match("/^[a-z0-9]{128}$/", $pw_check)) {
			return '密碼格式錯誤，如未開啟 JavaScript 功能，請將此功能開啟';
		} else if ($pw != $pw_check) {
			return '密碼確認不符，請重新輸入';
		} else if (!preg_match("/^[\w]{4,16}$/", $nickname)) {
			return '暱稱格式錯誤，暱稱只允許 英文、數字以及底線，並且長度為 4~16 字';
		} else if (preg_match("/^_|_$/", $nickname)) {
			return '暱稱格式錯誤，底線符號不能存在暱稱開頭或結尾';
		} else if (preg_match("/(admin|system)/i", $nickname)) {
			return '被禁止的暱稱';
		} else if (strlen($email) > 64) {
			return '信箱格式錯誤';
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return '信箱格式錯誤';
		} else {
			$sql = "SELECT `id` FROM `accounts` WHERE `username` = ?  OR `email` = ? OR `nickname` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('sss', $username, $email, $nickname);
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) != 0) {
					$stmt->close();
					return '帳號、信箱或暱稱已被使用';
				} else {
					$stmt->close();
					$sql = "INSERT INTO `accounts` (`username`, `password`, `nickname`, `email`, `web_admin`, `login_deny`, `register_ip`, `register_time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						$stmt->close();
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$stmt->bind_param('ssssssss', $username, hash('sha512', $pw), $nickname, $email, $web_admin_value, $login_deny_value, $GLOBALS['ip'], $GLOBALS['current_time_unix']);
						$web_admin_value = 0;
						$login_deny_value = 0;
						$stmt->execute();
						$insertid = $GLOBALS['mysqli_object_connecting']->insert_id;
						$stmt->close();
						$sql = "INSERT INTO `chat_room_setting` (`cid`, `ip`, `time_unix`) VALUES (?, ?, ?)";
						if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
							$stmt->close();
							handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
							exit();
						} else {
							$stmt->bind_param('sss', $insertid, $GLOBALS['ip'], $GLOBALS['current_time_unix']);
							$stmt->execute();
							$stmt->close();
							return '註冊成功';
						}
					}
				}
			}
		}
	}
	
	// login.php
	function user_login($username, $password) {
		if ($username == NULL or $password == NULL) {
			return '帳號或密碼錯誤';
		} else {
			$sql = "SELECT `id`, `nickname`, `web_admin`, `login_deny` FROM `accounts` WHERE `username` = ? AND `password` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ss', $username, hash('sha512', $password));
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					return '帳號或密碼錯誤';
				} else {
					$stmt->bind_result($result[], $result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$stmt->close();
					if ($result[3] != 0) {
						return '此帳號已被封鎖';
					} else {
						$_SESSION['cid'] = $result[0];
						$_SESSION['nickname'] = $result[1];
						$_SESSION['web_admin'] = $result[2];
						$sql = "UPDATE `accounts` SET `last_login_ip` = ?, `last_login_time_unix` = ? WHERE `username` = ?";
						if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
							$stmt->close();
							handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
							exit();
						} else {
							$stmt->bind_param('sss', $GLOBALS['ip'], $GLOBALS['current_time_unix'], $username);
							$stmt->execute();
							$stmt->close();
							$sql = "INSERT INTO `web_login_log` (`cid`, `ip`, `time_unix`) VALUES (?, ?, ?)";
							if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
								$stmt->close();
								handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
								exit();
							} else {
								$stmt->bind_param('sss', $_SESSION['cid'], $GLOBALS['ip'], $GLOBALS['current_time_unix']);
								$stmt->execute();
								$stmt->close();
								header("Location: " . $GLOBALS['prefix'] . "index.php");
								exit();
							}
						}
					}
				}
			}
		}
	}
	
	// updateinfo.php
	function user_updateinfo_pw($old_pw, $new_pw, $new_pw_check) {
		if (!preg_match("/^[a-z0-9]{128}$/", $old_pw) or !preg_match("/^[a-z0-9]{128}$/", $new_pw) or !preg_match("/^[a-z0-9]{128}$/", $new_pw_check)) {
			return '密碼格式錯誤，如未開啟 JavaScript 功能，請將此功能開啟';
		} else if ($new_pw != $new_pw_check) {
			return '密碼確認不符';
		} else {
			$sql = "SELECT `id` FROM `accounts` WHERE `id` = ? AND `password` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ss', $_SESSION['cid'], hash('sha512', $old_pw));
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					return '舊密碼錯誤';
				} else {
					$stmt->close();
					$sql = "UPDATE `accounts` SET `password` = ? WHERE `id` = ?";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						$stmt->close();
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$stmt->bind_param('ss', hash('sha512', $new_pw), $_SESSION['cid']);
						$stmt->execute();
						$stmt->close();
						return '成功更改密碼';
					}
				}
			}
		}
	}
	
	function user_updateinfo_info($nickname, $email) {
		if (!preg_match("/^[\w]{4,16}$/", $nickname)) {
			return '暱稱格式錯誤，暱稱只允許 英文、數字以及底線，並且長度為 4~16 字';
		} else if (preg_match("/^_|_$/", $nickname)) {
			return '暱稱格式錯誤，底線符號不能存在暱稱開頭或結尾';
		} else if (preg_match("/(admin|system)/i", $nickname)) {
			return '被禁止的暱稱';
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return '信箱格式錯誤';
		} else {
			$sql = "SELECT `id` FROM `accounts` WHERE (`nickname` = ? OR `email` = ?) AND `id` != ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('sss', $nickname, $email, $_SESSION['cid']);
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) != 0) {
					$stmt->close();
					return '暱稱或信箱已被使用';
				} else {
					$stmt->close();
					$sql = "UPDATE `accounts` SET `nickname` = ?, `email` = ? WHERE `id` = ?";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						$stmt->close();
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$stmt->bind_param('sss', $nickname, $email, $_SESSION['cid']);
						$stmt->execute();
						$stmt->close();
						return '成功更改個人資料';
					}
				}
			}
		}
	}
	
	function user_updateinfo_query() {
		$sql = "SELECT `username` FROM `accounts` WHERE `id` = ?";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
			$stmt->close();
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			$stmt->bind_param('s', $_SESSION['cid']);
			$stmt->execute();
			$stmt->bind_result($result[]);
			$result[] = $stmt->fetch();
			$stmt->close();
			return array (
				'username' => $result[0]
			);
		}
	}
?>