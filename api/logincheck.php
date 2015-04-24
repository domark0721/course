<?php  ?>
<?php
	//connect mysqldb
	require ("../mysql.php");

	//get user id and pwd
	session_start();
	$memberid=$_POST['username'];
	$userpwd=$_POST['userpwd'];

	//query id from member table
	$sql = "SELECT * FROM member where memberid='$memberid'";
	$result = mysql_query($sql);
	$row = @mysql_fetch_row($result);

	// echo $row[0];
	//判斷帳號與密碼是否為空
	if($memberid != null && $userpwd != null && $row[0] == $memberid && $row[1] == $userpwd)
	{
		$_SESSION['isLogin'] = true;
		$_SESSION['memberid'] = $memberid;
		$_SESSION['membername'] = $row[2];
		$_SESSION['mode'] = "st";
		// echo "login success!";
		Header("Location: ../stmode.php");
	}
	else{
		// echo "login fail!";
		Header("Location: ../login.php");
	}

?>