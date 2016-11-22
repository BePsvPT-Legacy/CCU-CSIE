<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
?>
				<header>
					<nav id="demo-horizontal-menu">
						<ul id="std-menu-items">
							<li><a href="<?php echo $prefix."index.php"; ?>">首頁</a></li>
							<li>
								<a href="#">宿舍</a>
								<ul>
									<li><a href="<?php echo $prefix."dormitory/roommate.php"; ?>">宿舍找室友</a></li>
									<li class="pure-menu-separator"></li>
									<li>
										<a href="#">宿網守護天使</a>
										<ul>
											<li><a href="#">使用說明</a></li>
											<li class="pure-menu-separator"></li>
											<li><a href="#">注意事項</a></li>
											<li class="pure-menu-separator"></li>
											<li><a href="#">檔案下載</a></li>
											<li class="pure-menu-separator"></li>
											<li><a href="#">開發日誌</a></li>
											<li class="pure-menu-separator"></li>
											<li><a href="#">聯絡我們</a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
					<script>
						YUI({classNamePrefix:"pure"}).use("gallery-sm-menu",function(a){var b=new a.Menu({container:"#demo-horizontal-menu",sourceNode:"#std-menu-items",orientation:"horizontal",hideOnOutsideClick:false,hideOnClick:false});b.render();b.show();});
					</script>
				</header>
