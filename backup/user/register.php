<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!REGISTER_ALLOW) {
		$register_deny = '很抱歉，目前未開放帳號註冊';
	} else if (isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "index.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['username']) and isset($_POST['pw']) and isset($_POST['pw_check']) and isset($_POST['nickname']) and isset($_POST['email'])) {
			$register_message = user_register($_POST['username'], $_POST['pw'], $_POST['pw_check'], $_POST['nickname'], $_POST['email']);
		}
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/SHA_512.js", $prefix . "scripts/js/SHA_512_Calc.js");
	display_head("註冊帳號", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "user/sources/navigation.php";
?>
		<div>
<?php
	if (isset($register_deny)) {
?>
			<div>
				<h4><?php echo $register_deny; ?></h4>
			</div>
<?php
	} else {
		if (isset($register_message)) {
?>
			<div>
				<h4><?php echo $register_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<form name="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="RegisterCalcHash()">
					<fieldset>
						<div class="pure-control-group">
							<label for="Username">帳號：</label>
							<input type="text" name="username" maxlength="24" placeholder="Username" pattern="^[a-zA-Z0-9]{6,20}$" title="6~20個英文字母或數字" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password">密碼：</label>
							<input type="password" name="pw" id="pw" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password">密碼確認：</label>
							<input type="password" name="pw_check" id="pw_check" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Nickname">暱稱：</label>
							<input type="text" name="nickname" maxlength="16" placeholder="Nickname" pattern="^[a-zA-Z0-9]{1}[a-zA-Z0-9_]{2,14}[a-zA-Z0-9]{1}$" title="4~16個英文字母、數字或底線符號" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Email">信箱：</label>
							<input type="email" name="email" maxlength="64" placeholder="Email" autocomplete="off" required>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">註冊</button>
						</div>
					</fieldset>
				</form>
			</div>
<?php
	}
?>
		</div>
<?php
	display_footer();
?>