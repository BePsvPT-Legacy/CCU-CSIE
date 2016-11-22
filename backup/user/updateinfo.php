<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!UPDATEINFO_ALLOW) {
		$update_deny = '很抱歉，目前資料修改功能關閉中';
	} else if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['old_pw']) and isset($_POST['new_pw']) and isset($_POST['new_pw_check'])) {
			$update_message = user_updateinfo_pw($_POST['old_pw'], $_POST['new_pw'], $_POST['new_pw_check']);
		} else if (isset($_POST['nickname']) and isset($_POST['email'])) {
			$update_message = user_updateinfo_info($_POST['nickname'], $_POST['email']);
		}
		
		$personal_info = user_updateinfo_query();
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/SHA_512.js", $prefix . "scripts/js/SHA_512_Calc.js");
	display_head("資料修改", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "user/sources/navigation.php";
?>
		<div>
<?php
	if (isset($update_deny) or isset($success)) {
?>
			<div>
				<h4><?php
	if (isset($update_deny)) {
		echo $update_deny;
	} else {
		echo $success;
		session_unset();
	}
?></h4>
			</div>
<?php
	} else {
		if (isset($update_message)) { ?>
			<div>
				<h4><?php echo $update_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<div>
					<h2>會員帳號：<?php echo $personal_info['username']; ?></h2>
				</div>
				<div>
					<h3>密碼修改</h3>
					<form name="changepw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return ChangePWCalcHash();">
						<fieldset>
							<div class="pure-control-group">
								<label for="old_pw">舊密碼：</label>
								<input type="password" name="old_pw" id="old_pw" maxlength="24" placeholder="Old Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw">新密碼：</label>
								<input type="password" name="new_pw" id="new_pw" maxlength="24" placeholder="New Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw_check">新密碼確認：</label>
								<input type="password" name="new_pw_check" id="new_pw_check" maxlength="24" placeholder="New Password" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary">提交</button>
								<button type="reset" class="pure-button pure-button-primary">清除</button>
							</div>
						</fieldset>
					</form>
				</div>
				<div>
					<h3>資料修改</h3>
					<form name="changeinfo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="nickname">暱稱：</label>
								<input type="text" name="nickname" maxlength="16" placeholder="Nickname" pattern="^[a-zA-Z0-9]{1}[a-zA-Z0-9_]{2,14}[a-zA-Z0-9]{1}$" title="4~16個英文字母、數字或底線符號" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="email">信箱：</label>
								<input type="email" name="email" maxlength="64" placeholder="Email" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary">提交</button>
								<button type="reset" class="pure-button pure-button-primary">清除</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
<?php
	}
?>
		</div>
<?php
	display_footer();
?>