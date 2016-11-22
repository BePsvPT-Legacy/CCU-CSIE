<?php
	if (!isset($prefix)) {
		$prefix = "./";
	}
	require_once ($prefix . "config/visitor_check.php");
	
	$ico_link = "";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array();
	display_head("From the New World", $ico_link, $body, $css_link, $js_link);
	
	require_once($prefix . "main/sources/navigation.php");
?>
			<h3>網站更新中</h1>
<?php
	display_footer();
?>