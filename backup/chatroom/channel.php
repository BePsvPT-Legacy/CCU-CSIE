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
	
		if (isset($_POST["newgroup"])) {
			chatroom_channel_addroom();
		} else if (isset($_POST["groupname"]) and isset($_POST["joingroup"])) {
			$channel_message = chatroom_channel_enterroom($_POST["groupname"]);
		}
	}
	
	$ico_link = $prefix . "chatroom/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array();
	display_head("頻道設置", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "chatroom/sources/navigation.php";
?>
		<div>
<?php if (isset($channel_message)) { ?>
			<div>
				<h4><?php echo $channel_message; ?></h4>
			</div>
<?php } ?>
			<div>
				<h3>聊天室頻道</h3>
				<form name="joingroup" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="roomname">聊天室頻道：</label>
							<input type="text" name="groupname" size="40" maxlength="40" autocomplete="off" pattern="^[a-z0-9]{40}$" required autofocus>
							<button type="submit" class="pure-button pure-button-primary">進入聊天室</button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<h3>聊天室創建</h3>
				<form name="newgroup" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<button type="submit" class="pure-button pure-button-primary">創建聊天室</button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<h3>聊天室概況</h3>
				<table class="pure-table pure-table-bordered pure-table-chat-channel">
					<tbody>
<?php
	$quantity = chatroom_channel_status();
	$i = 0;
	$item = array("聊天室總數：", "對話總數：", "線上人數：");
	foreach($item as $item) {
		echo <<<EOD
						<tr>
							<td>$item</td>
							<td>$quantity[$i]</td>
						</tr>\n
EOD;
		$i++;
	}
?>
					</tbody>
				</table>
			</div>
		</div>
<?php
	display_footer();
?>