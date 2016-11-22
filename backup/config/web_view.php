<?php
	function display_head($page_title, $page_icon, $page_body, $page_css, $page_js) {
		echo <<<EOD
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>$page_title</title>\n
EOD;
		if ($page_icon) {
			echo <<<EOD
		<link rel="icon" href="$page_icon" type="image/x-icon">\n
EOD;
		}
		foreach($page_css as $css_link) {
			echo <<<EOD
		<link rel="stylesheet" type="text/css" href="$css_link">\n
EOD;
		}
		foreach($page_js as $js_link) {
			echo <<<EOD
		<script type="text/javascript" src="$js_link"></script>\n
EOD;
		}
		echo <<<EOD
	</head>
	<body$page_body>\n
EOD;
	}
	
	function display_footer() {
		echo <<<EOD
		<footer>  
			本網站最佳瀏覽解析度 1920×1080，並使用 <a href="http://www.google.com/intl/zh-TW/chrome/" target="_blank">Google Chrome</a> 瀏覽器</br></br>
			Web Created by：Freedom / Copyright © 2014
		</footer> 
	</body>
</html>
EOD;
	}
?>