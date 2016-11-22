<?
session_start();
require_once("include/connect.php");
$login_err = '';
if(isset($_POST['submit'])){
	$sql="SELECT id,username,password,nologin FROM accounts WHERE username='".mysql_real_escape_string($_POST['uname'])."' AND password='".mysql_real_escape_string($_POST['upass'])."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		if(@mysql_result($result,0,3)){
				$login_err = '帳號或密碼錯誤!';
		}else{
			$_SESSION['uid']=@mysql_result($result,0,0);
			if($_POST["keepc"]=="1"){
				setcookie("uid", $_SESSION["uid"], time()+3600*24*14,"/");
				setcookie("upass", $_POST["upass"], time()+3600*24*14,"/");
			}
			@mysql_query("UPDATE accounts SET lastdate=NOW(),lastip='".$_SERVER['REMOTE_ADDR']."' WHERE id='".$_SESSION["uid"]."'");
			header("Location:main.php");
		}
	}else{
		$login_err = '帳號或密碼錯誤!';
	}
}elseif(isset($_SESSION['uid'])){
	header("Location:main.php");
}elseif(isset($_COOKIE["uid"]) and isset($_COOKIE["upass"])){
	$sql="SELECT nologin FROM accounts WHERE id='".mysql_real_escape_string($_COOKIE["uid"])."' AND password='".mysql_real_escape_string($_COOKIE["upass"])."'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		if(!(@mysql_result($result,0,0))){
			$_SESSION["uid"]=mysql_real_escape_string($_COOKIE["uid"]);
			@mysql_query("UPDATE accounts SET lastdate=NOW(),lastip='".$_SERVER['REMOTE_ADDR']."' WHERE id='".$_SESSION["uid"]."'");
			header("Location:main.php");
		}
	}
}
?>
<html>
	<head>
		<title>登入</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="authenticate/md5.js"></script>
		<script type="text/javascript">
			function Checkf(){
				document.getElementById("upass").value =  hex_md5(document.getElementById("upass").value);
				return true;
			}
		</script>
	</head>
	<body>
		<h1 align="Center">登入</h1>
		<form name="loginform" method="post" align="Center" onsubmit="return Checkf();">
			<table align="Center">
				<tr><td>帳號：</td><td><input type="text" name="uname" maxlength="16"></td></tr>
				<tr><td>密碼：</td><td><input type="password" name="upass" id="upass"></td></tr>
				<tr><td></td><td><label><input type="checkbox" name="keepc" value="1" checked>14天自動登入</label></td></tr>
				<tr><td></td><td><input type="submit" name="submit" value="登入"><input type="reset"></td></tr>
			</table>
			<p align="Center">
				<font color='#FF0000'>
					<?echo $login_err;?>
				</font><br>
			</p>
		</form>
		<p align="Center">
			<a href="regist.php">註冊帳號</a>
		</p>
	</body>
</html>