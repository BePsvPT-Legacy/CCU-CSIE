<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION["cid"])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if (!isset($_SESSION["room"])) {
		header("Location: " . $prefix . "chatroom/channel.php");
		exit();
	}
	require_once $prefix . "chatroom/chatroom_function.php";
	
	$chatroom_hash = hash("sha256", $_SESSION["room"], false);
	if (!isset($_SESSION["user_online_time"]) or ($current_time_unix - $_SESSION["user_online_time"]) > 60) {
		user_online_check($chatroom_hash);
	}
	
	$chat_set = chatroom_setting_query();
	
	$datetime = date("Y-m-d");
	$sql = "SELECT `nickname` FROM `chat_room_online` WHERE `time_unix` > '".($current_time_unix-60)."' AND `group` = '$chatroom_hash' ORDER BY `time_unix` DESC";
	if (!($stmt = $mysqli_object_connecting->query($sql))) {
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		echo <<<EOD
\n				<div class="online_people">
					Online：
EOD;
		while ($result = $stmt->fetch_array(MYSQLI_BOTH)) {
			echo $result[0] . ", ";
		}
		$stmt->close();
		echo <<<EOD
System
				</div>
				<div class="current_time">
					$datetime
				</div>\n
EOD;
	}
	
	if ($_SESSION["web_admin"] == 1) {
		$sql = "SELECT `id`, `cid`, `nickname`, `content`, `content_visible`, `time`, `time_unix` FROM `chat_room_log` WHERE `group` = '$chatroom_hash' ORDER BY `id` DESC LIMIT ".$chat_set["message_lines_value"]."";
	} else {
		$sql = "SELECT `id`, `cid`, `nickname`, `content`, `content_visible`,`time`, `time_unix` FROM `chat_room_log` WHERE `group` = '$chatroom_hash' AND `content_visible` = 1 ORDER BY `id` DESC LIMIT ".$chat_set["message_lines_value"]."";
	}
	if (!($stmt = $mysqli_object_connecting->query($sql))) {
		handle_database_error($web_url, mysqli_error($mysqli_connecting));
		exit();
	} else {
		$i = 0;
		$msg_del_rec_protect = "onChange=\"location.reload();\" onFocus=\"location.reload();\" autocomplete=\"off\" readonly=\"true\" hidden=\"true\"";
		$msg_del_rec_action = "action=\"/~cys102u/chatroom/chat.php\" method=\"POST\" onSubmit=\"return Message_Process_Check();\"";
		echo <<<EOD
				<div class="show_message">
					<table>
						<tbody>\n
EOD;
		while ($result = $stmt->fetch_array(MYSQLI_BOTH)) {
			if ($result["cid"] == 0) {
				$sysmsg = 1;
			}
			if (!isset($_SESSION["latestmsg"])) {
				$_SESSION["latestmsg"] = $result["time_unix"];
			}
			if ($i == 0) {
				$latestmsgusername = $result["nickname"];
				$latestmsg = $result["time_unix"];
			}
?>
							<tr>
								<td class="msgname"><?php echo $result["nickname"] . "："; ?></td>
								<td name="<?php echo ($result["cid"] != 0) ? "hashcontent".$i : "hashcontent" ; ?>" id="<?php echo ($result["cid"] != 0) ? "hashcontent".$i++ : "hashcontent"; ?>" class="msgcontent"><?php echo $result["content"]; ?></td>
								<td class="msgtime"><?php echo substr($result["time"], 11, 5); ?></td>
<?php if (($result["cid"] == $_SESSION["cid"] or $_SESSION["web_admin"] == 1) and $result["content_visible"] == 1) { ?>
								<td class="msg_process">
									<form name="deletemsg" <?php echo $msg_del_rec_action; ?>>
										<input type="text" name="delmsgid" value="<?php echo $result["id"]; ?>" <?php echo $msg_del_rec_protect; ?>>
										<input type="text" name="delmsgtime" value="<?php echo $result["time_unix"]; ?>" <?php echo $msg_del_rec_protect; ?>>
										<input type="submit" name="delmsg" value="刪除">
									</form>
								</td>
<?php } else if ($_SESSION["web_admin"] == 1 and $result["content_visible"] == 0) { ?>
								<td class="msg_process">
									<form name="recoverymsg" <?php echo $msg_del_rec_action; ?>>
										<input type="text" name="recmsgid" value="<?php echo $result["id"];?>" <?php echo $msg_del_rec_protect; ?>>
										<input type="text" name="recmsgtime" value="<?php echo $result["time_unix"];?>" <?php echo $msg_del_rec_protect; ?>>
										<input type="submit" name="recmsg" value="複原">
									</form>
								</td>
<?php } ?>
							</tr>
<?php
		}
		$stmt->close();
		echo <<<EOD
						</tbody>
					</table>
				</div>\n
EOD;
		if ($i == 0 and !isset($sysmsg)) {
			unset($_SESSION["room"]);
			echo <<<EOD
				聊天內容查詢失敗
				<script language=JavaScript>
					parent.location.reload();
				</script>\n
EOD;
		} else {
			if (strcmp($latestmsg, $_SESSION["latestmsg"]) > 0) {
				$_SESSION["latestmsg"] = $latestmsg;
				echo <<<EOD
				<script language="JavaScript">
					Start_Msg_Notify("$latestmsgusername");
				</script>\n
EOD;
				if ($chat_set["message_nofity"] == 0) {
					echo <<<EOD
				<script language="JavaScript">
					document.getElementById("msgnotify").play();
				</script>\n
EOD;
				}
			}
			echo <<<EOD
				<script language="JavaScript">
					XOR_Decrypt("$i");
				</script>\n
EOD;
		}
	}
?>