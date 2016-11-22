<?php
	function display_head($prefix, $page_title, $page_icon, $page_css, $page_js) {
		echo <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>$page_title</title>
		<link rel="icon" href="$prefix$page_icon" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="http://www.cs.ccu.edu.tw/~cys102u/scripts/css/pure-min.css">\n
EOD;
		foreach($page_css as $page_css) {
			echo <<<EOD
		<link rel="stylesheet" type="text/css" href="$prefix$page_css">\n
EOD;
		}
		echo <<<EOD
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
		<script src="http://www.cs.ccu.edu.tw/~cys102u/scripts/js/yui-min.js"></script>\n
EOD;
		foreach($page_js as $page_js) {
			echo <<<EOD
		<script type="text/javascript" src="$prefix$page_js"></script>\n
EOD;
		}
		echo <<<EOD
	</head>\n
EOD;
	}
	
	function display_footer() {
		echo <<<EOD
			<div id="id_footer">
				<footer>
					Web Created by：Freedom / Copyright © 2014
				</footer> 
			</div>
		</div>\n
EOD;
?>
		<script>
			$(document).ready(function(){$("#go_to_top").hide(),$(window).scroll(function(){$(this).scrollTop()>0?$("#go_to_top").fadeIn(300):$("#go_to_top").stop().fadeOut(300)}),$("#go_to_top").click(function(){jQuery("html,body").animate({scrollTop:0},350)})});
		</script>
<?php
	}
	/*
		$(document).ready(function() {
			$('#go_to_top').hide();
			$(window).scroll(function() {
				if ($(this).scrollTop() > 0) {
					$('#go_to_top').fadeIn(300);
				} else {
					$('#go_to_top').stop().fadeOut(300);
				}
			});
			$("#go_to_top").click(function(){
				jQuery("html,body").animate({
					scrollTop:0
				},350);
			});
		});
	*/
?>