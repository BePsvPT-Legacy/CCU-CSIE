<?
	require_once("authenticate/userinit.php");
	if(!isset($_GET['Order'])) $_GET['Order']="0";
?>
<html>
	<head>
		<title>嘟嘟嚕 課程系統</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<div>
			<form action="" method="GET">
				<p align='center'>
					<select name="Order" onChange="this.form.submit()">
						<option value="0" <?if ($_GET['Order']=="0") echo "selected";?>>首頁</option>
						<option value="1" <?if ($_GET['Order']=="1") echo "selected";?>>第一領域 基本語文能力</option>
						<option value="2" <?if ($_GET['Order']=="2") echo "selected";?>>第二領域 數理能力</option>
						<option value="3" <?if ($_GET['Order']=="3") echo "selected";?>>第三領域 人文素養</option>
						<option value="4" <?if ($_GET['Order']=="4") echo "selected";?>>第四領域 社會科學</option>
						<option value="5" <?if ($_GET['Order']=="5") echo "selected";?>>第五領域 自然科學</option>
						<option value="6" <?if ($_GET['Order']=="6") echo "selected";?>>非本系 必修課程</option>
					</select>
					<a href='teaching.php'>使用說明</a>
					<a href='version.php'>開發日誌</a>
					<a href='logout.php'>登出</a>
				</p>
<?if(isset($_GET['debug'])) echo "				<input type='hidden' name='debug'>\n";?>
			</form>
		</div>
<?if($_GET['Order']=="0") echo "		<h1 align=center>Top 10</h1>\n";?>
		<div>
			<form action='vote.php' method='POST'>
<?
	$subgroup=0;
	$subgroupsum=0;
	switch ($_GET['Order']){
	case "1":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=710 ORDER BY subid,id"; //votenum DESC,
		break;
	case "2":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=720 ORDER BY subid,id";
		break;
	case "3":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=730 ORDER BY subid,id";
		break;
	case "4":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=740 ORDER BY subid,id";
		break;
	case "5":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=750 ORDER BY subid,id";
		break;
	case "6":
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE subgroup=410 ORDER BY subid,id";
		break;
	default:
		$sql="SELECT id,subid,subgroup,subsub,subname,subteacher,votenum FROM subject WHERE votenum > 0 ORDER BY votenum DESC,subid,id LIMIT 0 , 10";
	}
	$result=mysql_query($sql);
	echo "				<table border=1 align='center'>\n";
	echo "					<tr>\n";
	echo "						<td align=center>科目代碼</td>\n";
	echo "						<td align=center>課程名稱</td>\n";
	echo "						<td align=center>授課教師</td>\n";
	echo "						<td align=center>嘟嘟嚕</td>\n";
	echo "						<td align=center></td>\n";
	echo "					</tr>\n";
	if($result){
		$subgroupsum=@mysql_result(mysql_query("SELECT MAX(votenum) vote_sum FROM subject"),0,0);
		for ($i=0 ; $i< mysql_num_rows($result) ; $i++){
			echo "					<tr>\n";
			echo "						<td align=center>".@mysql_result($result,$i,1)."</td>\n";
			echo "						<td align=center>".@mysql_result($result,$i,4)."</td>\n";
			echo "						<td align=center>".@mysql_result($result,$i,5)."</td>\n";
			$vote=@mysql_result($result,$i,6);
			$subid=@mysql_result($result,$i,2);
			$percent=0;
			if($subgroupsum>0) $percent = number_format($vote / $subgroupsum * 20);
			echo "						<td>\n";
			for($j=0;$j+2<=$percent;$j+=2) echo "							<img src='./images/th.png'>\n";
			if($j+1<=$percent){
				echo "							<img src='./images/th_half.png'>\n";
				$j+=1;
			}
			for($j;$j+2<=20;$j+=2) echo "							<img src='./images/th_no.png'>\n";
			if(isset($_GET['debug'])) echo "							".$vote."/".$subgroupsum." (".$percent.")\n";
			echo "						</td>\n";
			echo "						<td>";
			$result2=mysql_query("SELECT id,votedid FROM voted WHERE account='".$user["username"]."' AND votedsubid=".@mysql_result($result,$i,1));
			if($result2){
				if(mysql_num_rows($result2)==0){
					echo "<input type='image' name='vote' value='".@mysql_result($result,$i,0)."' src='./images/th_click.png'>";
				}else{
					if(@mysql_result($result2,0,1)==@mysql_result($result,$i,0)){
						echo "<input type='image' name='unvote' value='".@mysql_result($result,$i,0)."' src='./images/th_clicked.png'>";
					}else{
						echo "<img src='./images/th_nclick.png'>";
					}
				}
			}
			echo "</td>\n";
			echo "					</tr>\n";
		}
	}
	echo "				</table>\n";
	echo "				<input type='hidden' name='Order' value='".$_GET['Order']."'>\n";
	if(isset($_GET['debug'])) echo "				<input type='hidden' name='debug'>\n";
?>			</form>
		</div>
		<?require("others/footer.php");?>
	</body>
</html>