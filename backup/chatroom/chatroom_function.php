<?php
	// chatroom
	function chatroom_setting_query() {
		$sql = "SELECT `message_lines`, `message_update_time`, `message_nofity`, `music_autoplay`, `music_loop`, `music_file_name` FROM `chat_room_setting` WHERE `cid` = '".$_SESSION['cid']."'";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			$lines = array(10, 20, 30, 40, 50);
			$time = array(3000, 3500, 4000, 4500, 5000, 6000, 10000);
			$result = $stmt->fetch_array(MYSQLI_BOTH);
			$stmt->close();
			return array(
				'message_lines' => $result[0],
				'message_update_time' => $result[1],
				'message_lines_value' => $lines[$result[0]],
				'message_update_time_value' => $time[$result[1]],
				'message_nofity' => $result[2],
				'music_autoplay' => $result[3],
				'music_loop' => $result[4],
				'music_file_name' => (preg_match("/^[a-z0-9]{40}$/", $result[5])) ? $result[5] : 'LEVEL5-judgelight-'
			);
		}
	}
	
	function user_online_check($hash) {
		$_SESSION['user_online_time'] = $GLOBALS['current_time_unix'];
		$sql = "SELECT `id` FROM `chat_room_online` WHERE `cid` = '".$_SESSION['cid']."'";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			if (($stmt->num_rows) == 0) {
				$sql = "INSERT INTO `chat_room_online` (`cid`, `nickname`, `group`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?)";
				if (!($stmts = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
					handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
					exit();
				} else {
					$stmts->bind_param('sssss', $_SESSION['cid'], $_SESSION['nickname'], $hash, $GLOBALS['ip'], $GLOBALS['current_time_unix']);
					$stmts->execute();
					$stmts->close();
				}
			} else {
				$sql = "UPDATE `chat_room_online` SET `group` = ?, `ip` = ?, `time_unix` = ? WHERE `cid` = ?";
				if (!($stmts = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
					handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
					exit();
				} else {
					$stmts->bind_param('ssss', $hash, $GLOBALS['ip'], $GLOBALS['current_time_unix'], $_SESSION['cid']);
					$stmts->execute();
					$stmts->close();
				}
			}
		}
	}
	
	// channel.php
	function chatroom_channel_addroom() {
		while (true) {
			$_SESSION['room'] = hash('sha1', rand(-2147483648,2147483647), false);
			$chatroom_hash = hash('sha256', $_SESSION['room'], false);
			$sql = "SELECT `id` FROM `chat_room_log` WHERE `group` = '$chatroom_hash'";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				if (($stmt->num_rows) != 0) {
					continue;
				} else {
					$sql = "INSERT INTO `chat_room_log` (`cid`, `nickname`,`group`, `content`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?)";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$value = array();
						$stmt->bind_param('ssssss', $value[0], $value[1], $chatroom_hash, $value[2], $GLOBALS['ip'], $GLOBALS['current_time_unix']);
						$value[0] = '0';
						$value[1] = 'System';
						$value[2] = 'Welcome~';
						$stmt->execute();
						$stmt->close();
						header("Location: " . $GLOBALS['prefix'] . "chatroom/chat.php");
						exit();
					}
				}
			}
		}
	}
	
	function chatroom_channel_enterroom($roomname) {
		if (!preg_match("/^[a-z0-9]{40}$/", $roomname)) {
			return '聊天室頻道錯誤';
		} else {
			$sql = "SELECT `id` FROM `chat_room_log` WHERE `group` = '".hash('sha256', $roomname, false)."'";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				if ($stmt->num_rows == 0) {
					return '聊天室頻道錯誤';
				} else {
					$_SESSION['room'] = $roomname;
					user_online_check(hash('sha256', $_SESSION['room'], false));
					header("Location: " . $GLOBALS['prefix'] . "chatroom/chat.php");
					exit();
				}
			}
		}
	}
	
	function chatroom_channel_status() {
		$quantity = array();
		$i = 0;
		$sql = "SELECT COUNT(`group`) FROM `chat_room_log` GROUP BY `group`;";
		$sql .= "SELECT `id` FROM `chat_room_log`;";
		$sql .= "SELECT `id` FROM `chat_room_online` WHERE `time_unix` > '".($GLOBALS['current_time_unix']-300)."'";
		if (!($GLOBALS['mysqli_object_connecting']->multi_query($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			do {
				if (!($result = $GLOBALS['mysqli_object_connecting']->store_result())) {
					handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
					exit();
				} else {
					$quantity[$i++] = $result->num_rows;
					$result->free();
				}
			} while ($GLOBALS['mysqli_object_connecting']->next_result());
		}
		return $quantity;
	}
	
	// chat.php
	function chatroom_chat_send($content) {
		$content = htmlspecialchars($content, ENT_QUOTES);
		if ($content == NULL) {
			return '請輸入對話內容';
		} else {
			$sql = "INSERT INTO `chat_room_log` (`cid`, `nickname`, `group`, `content`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?)";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ssssss', $_SESSION['cid'], $_SESSION['nickname'], hash('sha256', $_SESSION['room'], false), $content, $GLOBALS['ip'], $GLOBALS['current_time_unix']);
				$stmt->execute();
				$stmt->close();
				$_SESSION['latestmsg'] = $current_time_unix;
				return NULL;
			}
		}
	}
	
	function chatroom_chat_delete_message($delmsgid, $delmsgtime) {
		if ($_SESSION['web_admin'] == 1) {
			$sql = "UPDATE `chat_room_log` SET `content_visible` = 0 WHERE `id` = ? AND `time_unix` = ?";
		} else {
			$sql = "UPDATE `chat_room_log` SET `content_visible` = 0 WHERE `cid` = ? AND `id` = ? AND `time_unix` = ?";
		}
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			if ($_SESSION['web_admin'] == 1) {
				$stmt->bind_param('ss', $delmsgid, $delmsgtime);
			} else {
				$stmt->bind_param('sss', $_SESSION['cid'], $delmsgid, $delmsgtime);
			}
			$stmt->execute();
			$stmt->close();
		}
	}
	
	function chatroom_chat_recovery_message($recmsgid, $recmsgtime) {
		$sql = "UPDATE `chat_room_log` SET `content_visible` = 1 WHERE `id` = ? AND `time_unix` = ?";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			$stmt->bind_param('ss', $recmsgid, $recmsgtime);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	// roomsetting.php
	function chatroom_chat_setting($msglines, $msgtime, $msgnotify) {
		if (!preg_match("/^[0-4]$/", $msglines)) {
			return '請重新設定對話顯示行數';
		} else if (!preg_match("/^[0-6]$/", $msgtime)) {
			return '請重新設定對話更新時間';
		} else if (!preg_match("/^[0-1]$/", $msgnotify)) {
			return '請重新設定對話通知音效';
		} else {
			$sql = "UPDATE `chat_room_setting` SET `message_lines` = ?, `message_update_time` = ?, `message_nofity` = ?, `ip` = ?, `time_unix` = ? WHERE `cid` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ssssss', $msglines, $msgtime, $msgnotify, $GLOBALS['ip'], $GLOBALS['current_time_unix'], $_SESSION['cid']);
				$stmt->execute();
				$stmt->close();
				return '成功更改設定';
			}
		}
	}
	
	function chatroom_music_setting($musicautoplay, $musicloop) {
		if (!preg_match("/^[0-1]$/", $musicautoplay)) {
			return '請重新設定音樂自動播放';
		} else if (!preg_match("/^[0-1]$/", $musicloop)) {
			return '請重新設定音樂循環播放';
		} else {
			$sql = "UPDATE `chat_room_setting` SET `music_autoplay` = ?, `music_loop` = ?, `ip` = ?, `time_unix` = ? WHERE `cid` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('sssss', $musicautoplay, $musicloop, $GLOBALS['ip'], $GLOBALS['current_time_unix'], $_SESSION['cid']);
				$stmt->execute();
				$stmt->close();
				return '成功更改設定';
			}
		}
	}
	
	function chatroom_music_upload() {
		if ((strcmp($_FILES["mp3_file"]["type"], 'audio/mp3') == 0) and ($_FILES["mp3_file"]["size"] > 524288 and $_FILES["mp3_file"]["size"] < 8388608) and (end(explode(".", $_FILES["mp3_file"]["name"])) == 'mp3')) {
			if ($_FILES["mp3_file"]["error"] > 0) {
				return $_FILES["mp3_file"]["error"];
			} else {
				while (true) {
					$file_name = hash('sha1', rand(), false);
					$sql = "SELECT `id` FROM `chat_room_setting` WHERE `music_file_name` = '$file_name'";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						if (($stmt->num_rows) != 0) {
							continue;
						} else {
							$sql = "UPDATE `chat_room_setting` SET `music_file_name` = ?, `ip` = ?, `time_unix` = ? WHERE `cid` = ?";
							if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
								handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
								exit();
							} else {
								$stmt->bind_param('ssss', $file_name, $GLOBALS['ip'], $GLOBALS['current_time_unix'], $_SESSION['cid']);
								$stmt->execute();
								$stmt->close();
								move_uploaded_file($_FILES["mp3_file"]["tmp_name"], "usermp3file/" . $file_name . ".mp3");
								return '檔案上傳成功';
							}
						}
					}
				}
			}
		} else {
			return '檔案上傳失敗，只允許 mp3 格式並且小於 8MB';
		}
	}
	
	function chatroom_music_delete() {
		$sql = "SELECT `music_file_name` FROM `chat_room_setting` WHERE `cid` = '".$_SESSION['cid']."'";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->query($sql))) {
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			$result = $stmt->fetch_array(MYSQLI_BOTH);
			if (!preg_match("/^[a-z0-9]{40}$/", $result[0])) {
				return '音樂刪除失敗';
			} else {
				$sql = "UPDATE `chat_room_setting` SET `music_file_name` = '', `ip` = ?, `time_unix` = ? WHERE `cid` = ?";
				if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
					handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
					exit();
				} else {
					$stmt->bind_param('sss', $GLOBALS['ip'], $GLOBALS['current_time_unix'], $_SESSION['cid']);
					$stmt->execute();
					$stmt->close();
					if (!unlink('usermp3file/' . $result[0] . '.mp3')) {
						return '音樂刪除失敗';
					} else {
						return '音樂已成功刪除';
					}
				}
				
			}
		}
	}
?>