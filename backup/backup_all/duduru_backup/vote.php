<?
	require_once("authenticate/userinit.php");
	if(!isset($_POST['vote']) && !isset($_POST['unvote'])){
		header("Location:main.php");
		exit;
	}
	if(isset($_POST['vote'])){
		$result=mysql_query("SELECT id,subid,votenum FROM subject WHERE id=".mysql_real_escape_string($_POST['vote']));
		$result2=mysql_query("SELECT id FROM voted WHERE account='".$user["username"]."' AND votedsubid=".@mysql_result($result,0,1));
		if($result and $result2){
			if(mysql_num_rows($result2)==0){
				mysql_query("INSERT INTO voted(account,votedsubid,votedid,votedip,votedtime) VALUES('".$user['username']."',".@mysql_result($result,0,1).",".@mysql_result($result,0,0).",'".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."',NOW())");
				mysql_query("UPDATE subject SET votenum=".(@mysql_result($result,0,2)+1)." WHERE id=".@mysql_result($result,0,0));
			}
		}
	}elseif(isset($_POST['unvote'])){
		$result=mysql_query("SELECT id,subid,votenum FROM subject WHERE id=".mysql_real_escape_string($_POST['unvote']));
		$result2=mysql_query("SELECT id FROM voted WHERE account='".$user["username"]."' AND votedsubid=".@mysql_result($result,0,1));
		if($result and $result2){
			if(mysql_num_rows($result2)>0){
				mysql_query("DELETE FROM voted WHERE id=".@mysql_result($result2,0,0)."");
				mysql_query("UPDATE subject SET votenum=".(@mysql_result($result,0,2)-1)." WHERE id=".@mysql_result($result,0,0));
			}
		}
	}
	if(isset($_POST['debug'])) $_POST['Order']= $_POST['Order']."&debug";
	header("Location:main.php?Order=".$_POST['Order']);
?>