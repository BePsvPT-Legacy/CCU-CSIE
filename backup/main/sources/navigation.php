<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
?>
		<header>
			<nav class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul>
					<li><a href="<?php echo $prefix . "index.php"; ?>" title="首頁">首頁</a></li>
					<li><a href="<?php echo $prefix . "dormflows/index.php"; ?>" title="流量守護">流量守護</a></li>
<?php if (!isset($_SESSION['cid'])) { ?>
					<li><a href="<?php echo $prefix . "user/login.php"; ?>" title="登入">登入</a></li>
					<li><a href="<?php echo $prefix . "user/register.php"; ?>" title="註冊">註冊</a></li>
<?php } else { ?>
					<li><a href="<?php echo $prefix . "#"; ?>" title="製作中">選課方針</a>
					<li><a href="<?php echo $prefix . "chatroom/channel.php"; ?>" title="聊天室">聊天室</a>
					<li><a href="<?php echo $prefix . "user/index.php"; ?>" title="個人中心">個人中心</a>
					<li><a href="<?php echo $prefix . "user/logout.php"; ?>" title="登出">登出</a></li>
<?php } ?>
				</ul>
			</nav>
		</header>
		<hr>
