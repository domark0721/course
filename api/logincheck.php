<?php require ("../mysql.php") ?>
<?php
	//抓取帳號密碼
	session_start();
	$memberid=$_POST['username'];
	$userpwd=$_POST['userpwd'];

	//搜尋資料庫帳號密碼
	$sql = "SELECT * FROM member where memberid='$memberid'";
	$result = mysql_query($sql);
	$row = @mysql_fetch_row($result);

	echo $row[0];
	//判斷帳號與密碼是否為空
	if($memberid != null && $userpwd != null && $row[0] == $memberid && $row[1] == $userpwd)
	{
		$_SESSION['isLogin'] = true;
		$_SESSION['memberid'] = $memberid;
		echo "login success!";
		Header("Location: ../course.php");
	}
	else{
		echo "login fail!";
		Header("Location: ../login.php");
	}	

?>