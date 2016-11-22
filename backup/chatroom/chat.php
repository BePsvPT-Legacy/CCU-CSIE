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
	
	if (!isset($_SESSION["user_online_time"]) or ($current_time_unix - $_SESSION["user_online_time"]) > 60) {
		user_online_check(hash("sha256", $_SESSION["room"], false));
	}
	if (isset($_POST["content"])) {
		$chat_message = chatroom_chat_send($_POST["content"]);
	} else if (isset($_POST["delmsgid"]) and isset($_POST["delmsgtime"]) and isset($_POST["delmsg"])) {
		chatroom_chat_delete_message($_POST["delmsgid"], $_POST["delmsgtime"]);
	} else if (isset($_POST["recmsgid"]) and isset($_POST["recmsgtime"]) and isset($_POST["recmsg"]) and $_SESSION["web_admin"] == 1) {
		chatroom_chat_recovery_message($_POST["recmsgid"], $_POST["recmsgtime"]);
	}
	
	$chat_set = chatroom_setting_query();
	
	$ico_link = $prefix . "chatroom/images/icon.ico";
	$body = " onLoad=\"window.setInterval(Message_Update, ".$chat_set["message_update_time_value"]."); return Message_Update();\"";
	$css_link = array($prefix . "chatroom/css/main.css", $prefix . "chatroom/css/chat.css");
	$js_link = array($prefix . "scripts/js/jquery-2.0.2.js", $prefix . "scripts/js/Message_Update.js");
	display_head("聊天室", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "chatroom/sources/navigation.php";
?>
		<div>
<?php if (isset($chat_message)) { ?>
			<div>
				<p><?php echo $chat_message; ?></p>
			</div>
<?php } ?>
			<div>
				<label for="roomname">房間代碼：<?php echo $_SESSION["room"]; ?></label>
				<input type="text" name="hashkey" id="hashkey" class="hashkey" value="<?php echo substr(preg_replace("/[a-zA-Z]/", "", $_SESSION["room"]), 0, 11); ?>" size="13" onChange="location.reload();" onFocus="location.reload();" autocomplete="off" readonly hidden>
			</div>
			<div>
				<form name="message" onSubmit="return Message_Send();">
					<label for="content">說話：</label>
					<input type="text" name="content" id="content" class="message_text" size="40" maxlength="32" required autofocus>
					<input type="submit" name="send" id="send" class="message_submit" value="送出">
				</form>
			</div>
			<div id="show_message">
			</div>
			<div>
				<audio id="msgnotify" preload="auto">
				  <source src="<?php echo $prefix . "chatroom/sources/msgnotify.mp3"; ?>" type="audio/mpeg">
				</audio>
			</div>
			<div>
				<audio preload="auto" controls<?php echo ($chat_set["music_autoplay"] == 0) ? " autoplay" : ""; echo ($chat_set["music_loop"] == 0) ? " loop" : ""; ?>>
				  <source src="<?php echo $prefix . "chatroom/usermp3file/" . $chat_set["music_file_name"] . ".mp3"; ?>" type="audio/mpeg">
				</audio>
			</div>
		</div>
<?php
	display_footer();
?>