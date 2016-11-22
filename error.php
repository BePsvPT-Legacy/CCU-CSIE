<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
<?php
	if (false/*!ADMIN_ONLY*/) {
?>
		<meta http-equiv="refresh" content="2;url=http://crux.coder.tw/freedom/index.php">
<?php
	}
?>
		<title>Freedom</title>
		<link rel="icon" href="http://www.cs.ccu.edu.tw/~cys102u/images/error.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="http://www.cs.ccu.edu.tw/~cys102u/scripts/css/pure-min.css">
	</head>
	<body>
		<div class="heading_center heading_highlight">
			<h1><?php //echo $_GET["message"]; ?></h1>
		</div>
	</body>
</html>
<?php
	} else {
		//header("Location: http://crux.coder.tw/freedom/index.php");
	}
?>