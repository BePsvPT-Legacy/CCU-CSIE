<?php
	if (!isset($prefix)) {
		$prefix = "./";
	}
	require_once $prefix."config/web_preprocess.php";
	
	if (isset($_POST['semester'])) {
		switch ($_POST['semester']) {
			case '107':
				$tmp = $current_time + 15552000;
				setcookie('semester', '107', $tmp, "/", WEB_DOMAIN_NAME);
				$message = '設置成功';
				break;
			default :
				$message = '似乎選錯嚕~';
				break;
		}
	}
	
	$ico_link = "images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix,"Freedom",$ico_link,$css_link,$js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_highlight">
						<h3><?php echo $message; ?></h3>
					</div>
					<form name="init" id="init" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="semester">級別：</label>
								<select name="semester" id="semester">
									<option value="107">107級</option>
								</select>
							</div>
							<div class="pure-controls">
								<button type="submit" id="submit" class="pure-button pure-button-primary">送出</button>
							</div>
						</fieldset>
					</form>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>