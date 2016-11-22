<?
session_start();
require_once('include/connect.php');
if(!isset($_SESSION['uid'])){
	header("Location:index.php");
	exit;
}
$sql="SELECT `id`,`username`,`nologin` FROM accounts WHERE id='".$_SESSION['uid']."'";
$result=mysql_query($sql);
for ($i=0 ; $i<mysql_num_fields($result) ; $i++) {
	$user[mysql_field_name($result,$i)] = @mysql_result($result,0,$i);
}
if ($user['nologin']){
	exit();
}
?>