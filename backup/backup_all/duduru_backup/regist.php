<?
require_once('include/connect.php');

$err = "";
if(isset($_GET['obj'])){
	switch ($_GET['obj']){
		case 'username':
			if(!preg_match("/^[a-zA-Z0-9_-]+$/",$_GET['value'])) {
				echo "<font color=red>錯誤</font>";
				exit;
			}
			$sql="SELECT id FROM accounts WHERE username='".mysql_real_escape_string($_GET['value'])."'";
			break;
		case 'email':
			if(!preg_match("/^[A-Z0-9._%-]+@gmail.com$/i", $_GET["value"]) and !preg_match("/^[A-Z0-9._%-]+@yahoo.com.tw$/i", $_GET["value"]) or  strlen($_GET["value"]) > 50){
				echo "<font color=red>錯誤</font>";
				exit;
			}
			$sql="SELECT id FROM accounts WHERE email='".mysql_real_escape_string($_GET['value'])."'";
			break;
		default:
			echo "<font color=red>錯誤</font>";
			exit;
	}
	$result=mysql_query($sql);
	if(mysql_num_rows($result) > 0){
		echo "<font color=red>錯誤</font>";
	}else{
		echo "<font color=green>OK</font>";
	}
	exit;
}

$sql="SELECT id FROM accounts WHERE lastip='".mysql_real_escape_string($_SERVER["REMOTE_ADDR"])."'";
$result=mysql_query($sql);
if(mysql_num_rows($result) > 0){
	?>
	<html>
	<head>
		<title>註冊帳號</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	</head>
	<body>
		<h1 align='Center'>註冊帳號</h1>
		<p align='center'><font color=red>您已經註冊過了!</font></p>
		<p align="Center"><a href="index.php">回首頁</a></p>
	</body>
	</html>
	<?
	exit;
}

if(isset($_POST["submit"])){
	if(!preg_match("/^[a-zA-Z0-9_-]+$/",$_POST['username']) or strlen($_POST["username"]) < 5 or strlen($_POST["username"]) > 16) {
		$err = "帳號格式錯誤!";
	} else if( $_POST["passwd"]!==$_POST["passwd2"] or strlen($_POST["passwd"]) < 5 or strlen($_POST["passwd"]) > 24){
		$err = "密碼格式錯誤!";
	} else if(!preg_match("/^[A-Z0-9._%-]+@gmail.com$/i", $_POST["email"]) and !preg_match("/^[A-Z0-9._%-]+@yahoo.com.tw$/i", $_POST["email"]) or  strlen($_POST["email"]) > 50){
		$err = "信箱格式錯誤!";
	} else{
		$sql="SELECT id FROM accounts WHERE username='".mysql_real_escape_string($_POST['username'])."' or email='".mysql_real_escape_string($_POST['email'])."'";
		$result=mysql_query($sql);
		if(mysql_num_rows($result) > 0){
			$err = "未知錯誤，請通知管理員!";
		}
	}
	if(!$err){
		$sql="INSERT INTO accounts(`username`,`password`,`email`,`regip`,`lastip`,`regdate`,`lastdate`,`nologin`) VALUES('".mysql_real_escape_string($_POST['username'])."','".md5($_POST["passwd"])."','".mysql_real_escape_string($_POST['email'])."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."',NOW(),NOW(),0)";
		$result=mysql_query($sql);
		if($result){
			$sql="SELECT `id` FROM accounts WHERE username='".mysql_real_escape_string($_POST['username'])."'";
			$result=mysql_query($sql);
	?>
<html>
	<head>
		<title>註冊帳號</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="refresh" content="0;url=index.php">
	</head>
	<body onload="javascript:alert('註冊成功!')">
	</body>
</html>	
	<?
		}else{
			echo $sql."<br>";
			echo "資料庫失敗!";
		}
		exit;
	}
}
?>
<html>
	<head>
		<title>註冊帳號</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="jquery/jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(
				function() {
					$(".pw").keyup(
						function(){
							if($("#passwd").val()==$("#passwd2").val() && $("#passwd").val().length > 4 && $("#passwd").val().length < 25){
								$("#check_passwd").html("<font color=green>OK</font>");
							}else{
								$("#check_passwd").html("<font color=red>密碼不符</font>");
							}
						}
					);
					$(".chk").change(
						function(){
							var inputobj = this;
							if(inputobj.name=="username" && (inputobj.value.length < 5 || inputobj.value.length > 16)){
								$("#check_username").html("<font color=red>帳號長度錯誤</font>");
								return true;
							}else if(inputobj.name=="username"){
								$("#check_username").html("");
							}
							$("#check_email").html("");
							$.ajax({
								type: 'GET',
								url: 'regist.php',
								data: "obj=" + inputobj.name + "&value=" + inputobj.value,
								success: function(data){
										$("#check_" + inputobj.name).html(data);
									},
								datatype: 'html'
							});
						}
					);
				}
			);
		</script>
	</head>
	<body>
		<h1 align="Center">註冊帳號</h1>
		<form method="POST" align="Center" autocomplete=off>
			<table align="Center">
				<tr>
					<td>　　帳號：</td>
					<td><input type="text"  class='chk' name="username" maxlength="16"><span id="check_username"></span></td>
					<td>字數：5~16</td>
				</tr>
				<tr>
					<td>　　密碼：</td>
					<td><input type="password" class='pw' name="passwd" id="passwd" maxlength="24"></td>
					<td>字數：5~24</td>
				</tr>
				<tr>
					<td>確認密碼：</td>
					<td><input type="password" class='pw' name="passwd2" id="passwd2" maxlength="24"><span id="check_passwd"></span></td>
					<td>字數：5~24</td>
				</tr>
				<tr>
					<td>電子信箱：</td>
					<td><input type="text" class='chk' name="email" maxlength="50"><span id="check_email"></span></td>
					<td>僅限@gmail.com 或 @yahoo.com.tw</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="submit"></td>
					<td></td>
				</tr>
			</table>
			<p><font color=red><? echo $err;?></font></p>
		</form>
		<p align="Center"><a href="index.php">回首頁</a></p>
			<?require("others/footer.php");?>
	</body>
</html>