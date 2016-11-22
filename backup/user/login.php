<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!LOGIN_ALLOW) {
		$login_deny = '很抱歉，目前登入功能關閉中';
	} else if (isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "index.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['username']) and isset($_POST['password'])) {
			$login_message = user_login($_POST['username'], $_POST['password']);
		}
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/SHA_512.js", $prefix . "scripts/js/SHA_512_Calc.js");
	display_head("帳號登入", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "user/sources/navigation.php";
?>
		<div>
<?php
	if (isset($login_deny)) {
?>
			<div>
				<h4><?php echo $login_deny; ?></h4>
			</div>
<?php
	} else {
		if (isset($login_message)) { ?>
			<div>
				<h4><?php echo $login_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="LoginCalcHash()">
					<fieldset>
						<div class="pure-control-group">
							<label for="Username">帳號：</label>
							<input type="text" name="username" id="username" maxlength="24" placeholder="Username" pattern="^[a-zA-Z0-9]{6,20}$" title="6~20個英文字母或數字" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password">密碼：</label>
							<input type="password" name="password" id="password" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">登入</button>
							<button type="reset" class="pure-button pure-button-primary">清除</button>
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