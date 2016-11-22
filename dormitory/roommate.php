<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/web_preprocess.php";
	
	if (!isset($_COOKIE['semester'])) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$room_data = new roommate('mysql', DATABASE_MYSQL_HOST, DATABASE_MYSQL_DBNAME, DATABASE_MYSQL_USERNAME, DATABASE_MYSQL_PASSWORD);
	if (isset($_POST['room_add']) and isset($_POST['bed']) and isset($_POST['name_add']) and isset($_POST['fb_link']) and isset($_POST['line_num'])) {
		if ($_POST['line_num'] == '') {
			$_POST['line_num'] = null;
		}
		$add_result = $room_data->add_info($_POST['room_add'], $_POST['bed'], $_POST['name_add'], $_POST['fb_link'], $_POST['line_num']);
		if ($add_result === true) {
			$search_result = $room_data->search_roommate($_POST['room_add'], $_POST['name_add']);
			$message = '新增成功';
		} else {
			$message = $add_result;
		}
	} else if (isset($_POST['room']) and isset($_POST['name'])) {
		$search_result = $room_data->search_roommate($_POST['room'], $_POST['name']);
	}
	$group_result = $room_data->group_search();
	
	$ico_link = "images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix,"宿舍找室友",$ico_link,$css_link,$js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="pure-g">
						<div class="pure-u-1-2">
							<div>
								<div class="heading_center heading_title">
									<h1>新增資訊</h1>
								</div>
								<div class="heading_center heading_highlight">
									<h3><?php echo $message; ?></h3>
								</div>
								<form name="add_info" id="add_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
									<fieldset>
										<div class="pure-control-group">
											<label for="room_add">寢室編號：</label>
											<input type="text" name="room_add" id="room_add" maxlength="4" pattern="^[\d]{4}$" autocomplete="off" required>
										</div>
										<div class="pure-control-group">
											<label for="bed">床位：</label>
											<select name="bed" id="bed">
												<option value="1">A</option>
												<option value="2">B</option>
												<option value="3">C</option>
												<option value="4">D</option>
											</select>
										</div>
										<div class="pure-control-group">
											<label for="name_add">姓名：</label>
											<input type="text" name="name_add" id="name_add" maxlength="16" autocomplete="off" required>
										</div>
										<div class="pure-control-group">
											<label for="fb_link">FB連結：</label>
											<input type="text" name="fb_link" id="fb_link" maxlength="128" autocomplete="off" required>
										</div>
										<div class="pure-control-group">
											<label for="line_num">Line：</label>
											<input type="text" name="line_num" id="line_num" maxlength="32" placeholder="選填" autocomplete="off">
										</div>
										<div class="pure-controls">
											<button type="submit" id="submit" class="pure-button pure-button-primary">新增</button>
										</div>
									</fieldset>
								</form>
							</div>
							<div>
								<div class="heading_center heading_title">
									<h1>可公開情報</h1>
								</div>
								<div>
									<table class="pure-table">
										<thead>
											<tr>
												<th>^_^</th>
												<th>^_^</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>夢想啟程</td>
												<td><?php echo $group_result['4']; ?></td>
											</tr>
											<tr>
												<td>明星三缺一</td>
												<td><?php echo $group_result['3']; ?></td>
											</tr>
											<tr>
												<td>成雙成對</td>
												<td><?php echo $group_result['2']; ?></td>
											</tr>
											<tr>
												<td>冒險的起點</td>
												<td><?php echo $group_result['1']; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="pure-u-1-2">
							<div>
								<form name="search" id="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
									<fieldset>
										<div class="pure-control-group">
											<label for="room">寢室編號：</label>
											<input type="text" name="room" id="room" maxlength="4" pattern="^[\d]{4}$" autocomplete="off" required>
										</div>
										<div class="pure-control-group">
											<label for="name">姓名：</label>
											<input type="text" name="name" id="name" maxlength="16" autocomplete="off" required>
										</div>
										<div class="pure-controls">
											<button type="submit" id="submit" class="pure-button pure-button-primary">查詢</button>
										</div>
									</fieldset>
								</form>
							</div>
							<div>
								<table class="pure-table">
									<thead>
										<tr>
											<th>床位</th>
											<th>姓名</th>
											<th>FB</th>
											<th>Line</th>
										</tr>
									</thead>
									<tbody>
<?php
	if (isset($search_result)) {
		$room_info[0] = $room_info[1] = $room_info[2] = $room_info[3] = array(
			'bed' => '-',
			'name' => '-',
			'FB' => '-',
			'Line' => '-',
		);
		foreach ($search_result as $tmp) {
			$i = $tmp['bed']-1;
			$room_info[$i]['bed'] = $tmp['bed'];
			$room_info[$i]['name'] = htmlspecialchars_decode($tmp['name'], ENT_QUOTES);
			$room_info[$i]['FB'] = $tmp['fb_link'];
			$room_info[$i]['Line'] = $tmp['line_num'];
		}
		$bed_name = array('A', 'B', 'C', 'D');
		for ($i = 0; $i < 4; $i++) {
?>
										<tr>
											<td><?php echo $bed_name[$i]; ?></td>
											<td><?php echo $room_info[$i]['name']; ?></td>
											<td>
<?php
			if ($room_info[$i]['FB'] != '-') {
?>
												<a href="<?php echo $room_info[$i]['FB']; ?>" target="_blank"><?php echo $room_info[$i]['FB']; ?></a>
<?php
			} else {
?>
												-
<?php
			}
?>
											</td>
											<td><?php echo $room_info[$i]['Line']; ?></td>
										</tr>
<?php
		}
	}
?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>