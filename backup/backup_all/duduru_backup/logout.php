<?
unset($_SESSION['uid']);
setcookie("PHPSESSID",null ,null,"/");
setcookie("uid",null ,null,"/");
setcookie("upass",null ,null,"/");
header("Location:index.php");
?>