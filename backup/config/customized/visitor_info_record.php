<?php
	function getBrowser() { 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//Platform Check
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'Linux';
		} else if (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Mac';
		} else if (preg_match('/Windows NT 5.0/i', $u_agent)) {
			$platform = 'Windows 2000';
		} else if (preg_match('/Windows NT 5.1/i', $u_agent)) {
			$platform = 'Windows XP';
		} else if (preg_match('/Windows NT 5.2/i', $u_agent)) {
			$platform = 'Windows 2003';
		} else if (preg_match('/Windows NT 6.0/i', $u_agent)) {
			$platform = 'Windows Vista';
		} else if (preg_match('/Windows NT 6.1/i', $u_agent)) {
			$platform = 'Windows 7';
		} else if (preg_match('/Windows NT 6.2/i', $u_agent)) {
			$platform = 'Windows 8';
		}
		
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} else if (preg_match( '/Trident\/7.0; rv:11.0/', $u_agent)) {
			$bname = 'Internet Explorer';
		} else if(preg_match('/Firefox/i',$u_agent)) { 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} else if(preg_match('/Chrome/i',$u_agent)) { 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} else if(preg_match('/Safari/i',$u_agent)) { 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} else if(preg_match('/Opera/i',$u_agent)) { 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} else if(preg_match('/Netscape/i',$u_agent)) { 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
				$version= $matches['version'][0];
			} else {
				$version= $matches['version'][1];
			}
		} else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if (preg_match( '/Trident\/7.0; rv:11.0/', $u_agent)) {
			$version="11";
		} else if ($version==null || $version=="") {
			$version="?";
		}
		
		return array(
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'   => $pattern
		);
	}
	
	if ($ip != '140.123.220.135') {
		$server_protocol = mysqli_real_escape_string($mysqli_connecting, $_SERVER['SERVER_PROTOCOL']);
		$request_method = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REQUEST_METHOD']);
		$remote_port = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REMOTE_PORT']);
		$http_referer = mysqli_real_escape_string($mysqli_connecting, $_SERVER['HTTP_REFERER']);
		$REQUEST_URI = mysqli_real_escape_string($mysqli_connecting, $_SERVER['REQUEST_URI']);
		$http_user_agent = mysqli_real_escape_string($mysqli_connecting, $_SERVER['HTTP_USER_AGENT']);
		
		if (!($http_referer == 'http://www.cs.ccu.edu.tw/~cys102u/chatroom/chat.php' and ($REQUEST_URI == '/~cys102u/chatroom/show_message.php') or $REQUEST_URI == '/~cys102u/chatroom/chat.php')) {
			$user_browser = getBrowser();
			$browser_name = mysqli_real_escape_string($mysqli_connecting, $user_browser['name']);
			$browser_version = mysqli_real_escape_string($mysqli_connecting, $user_browser['version']);
			$platform = mysqli_real_escape_string($mysqli_connecting, $user_browser['platform']);
			
			$sql = "INSERT INTO `visitor_browse_data` (`ip`, `SERVER_PROTOCOL`, `REQUEST_METHOD`, `REMOTE_PORT`, `HTTP_REFERER`, `REQUEST_URI`, `HTTP_USER_AGENT`, `browser_name`, `browser_version`, `platform`, `time_unix`) VALUES ('".$ip."', '".$server_protocol."', '".$request_method."', '".$remote_port."', '".$http_referer."', '".$REQUEST_URI."', '".$http_user_agent."', '".$browser_name."', '".$browser_version."', '".$platform."', '".time()."')";
			if (!mysqli_query($mysqli_connecting, $sql)) {
				handle_database_error($web_url, mysqli_error($mysqli_connecting));
				exit();
			}
		}
	}
?>