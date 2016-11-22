<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
?>
		<header>
			<nav class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul>
					<li><a href="<?php echo $prefix . "index.php"; ?>" title="主選單">主選單</a></li>
<?php if (!isset($_SESSION['cid'])) { ?>
					<li><a href="<?php echo $prefix . "user/login.php"; ?>" title="登入">登入</a></li>
					<li><a href="<?php echo $prefix . "user/register.php"; ?>" title="註冊">註冊</a></li>
<?php } else { ?>
					<li><a href="<?php echo $prefix . "user/index.php"; ?>" title="個人中心">個人中心</a></li>
					<li><a href="<?php echo $prefix . "user/updateinfo.php"; ?>" title="資料修改">資料修改</a></li>
					<li><a href="<?php echo $prefix . "user/logout.php"; ?>" title="登出">登出</a></li>
<?php } ?>
				</ul>
			</nav>
		</header>
		<hr>
