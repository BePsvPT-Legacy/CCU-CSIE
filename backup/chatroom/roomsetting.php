<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION["cid"])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		require_once $prefix . "chatroom/chatroom_function.php";
	
		if (isset($_POST["roomsetting"]) and isset($_POST["msglines"]) and isset($_POST["msgtime"]) and isset($_POST["msgnotify"])) {
			$roomsetting_message = chatroom_chat_setting($_POST["msglines"], $_POST["msgtime"], $_POST["msgnotify"]);
		} else if (isset($_POST["musicsetting"]) and isset($_POST["musicautoplay"]) and isset($_POST["musicloop"])) {
			$roomsetting_message = chatroom_music_setting($_POST["musicautoplay"], $_POST["musicloop"]);
		} else if (isset($_POST["upload_music"])) {
			$roomsetting_message = chatroom_music_upload();
		} else if (isset($_POST["delete_music"])) {
			$roomsetting_message = chatroom_music_delete();
		}
		
		$chat_set = chatroom_setting_query();
	}
	
	$ico_link = $prefix . "chatroom/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/Message_Update.js");
	display_head("對話設定", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "chatroom/sources/navigation.php";
?>
		<div>
<?php if (isset($roomsetting_message)) { ?>
			<div>
				<h4><?php echo $roomsetting_message; ?></h4>
			</div>
<?php } ?>
			<div>
				<form name="roomsetting" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="msglines">對話顯示行數：：</label>
							<select name="msglines">
<?php
	$linesname = array("10行", "20行", "30行", "40行", "50行");
	for ($i=0;$i<count($linesname);$i++) {
		if ($i == $chat_set["message_lines"]) {
			echo <<<EOD
								<option value="$i" selected>$linesname[$i]</option>\n
EOD;
		} else {
			echo <<<EOD
								<option value="$i">$linesname[$i]</option>\n
EOD;
		}
	}
?>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="msgtime">對話更新時間：</label>
							<select name="msgtime">
<?php
	$timename = array("3秒", "3.5秒", "4秒", "4.5秒", "5秒", "6秒", "10秒");
	for ($i=0;$i<count($timename);$i++) {
		if ($i == $chat_set["message_update_time"]) {
			echo <<<EOD
									<option value="$i" selected>$timename[$i]</option>\n
EOD;
		} else {
			echo <<<EOD
									<option value="$i">$timename[$i]</option>\n
EOD;
		}
	}
?>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="msgnotify">對話通知音效：</label>
							<select name="msgnotify">
								<option value="0"<?php echo ($chat_set["message_nofity"] == 0) ? " selected" : "";?>>開啟</option>
								<option value="1"<?php echo ($chat_set["message_nofity"] == 1) ? " selected" : "";?>>關閉</option>
							</select>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">更改</button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<form name="musicsetting" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="musicautoplay">音樂自動播放：</label>
							<select name="musicautoplay">
								<option value="0"<?php echo ($chat_set["music_autoplay"] == 0) ? " selected" : "";?>>開啟</option>
								<option value="1"<?php echo ($chat_set["music_autoplay"] == 1) ? " selected" : "";?>>關閉</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="musicloop">音樂循環播放：</label>
							<select name="musicloop">
								<option value="0"<?php echo ($chat_set["music_loop"] == 0) ? " selected" : "";?>>開啟</option>
								<option value="1"<?php echo ($chat_set["music_loop"] == 1) ? " selected" : "";?>>關閉</option>
							</select>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">更改</button>
						</div>
					</fieldset>
				</form>
			</div>
<?php if ($chat_set["music_file_name"] == "LEVEL5-judgelight-") { ?>
			<div>
				<form name="upload_music" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned" enctype="multipart/form-data">
					<fieldset>
						<div class="pure-control-group">
							<label for="music_upload">聊天室音樂上傳：</label>
							<input type="file" name="mp3_file" id="mp3_file">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">上傳</button>
						</div>
					</fieldset>
				</form>
			</div>
<?php } ?>
			<div>
				<form name="delete_music" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return Message_Process_Check();">
					<table>
						<tr>
							<th>聊天室音樂狀況：</th>
<?php if ($chat_set["music_file_name"] != "LEVEL5-judgelight-") { ?>
							<td>使用自行上傳音樂檔</td>
						</tr>
						<tr class="button">
							<td colspan="2">
								<input type="submit" name="delete_music" value="刪除">
							</td>
<?php } else { ?>
							<td>使用系統預設音樂檔</td>
<?php } ?>
						</tr>
					</table>
				</form>
			</div>
		</div>
<?php
	display_footer();
?>