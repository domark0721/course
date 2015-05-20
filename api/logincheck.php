<?php
	//connect mysqldb
	require ("../mysql.php");

	//get user id and pwd
	session_start();
	$account=$_POST['account'];
	$password=$_POST['password'];

	if(empty($account) || empty($password)) {
		Header("Location: ../login.php");
	}
	
	//query id from member table
	$sql = "SELECT * FROM member WHERE account='$account'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result); //save as array

	// echo $row[0];
	if(!empty($row) && $row['account'] == $account && $row['password'] == $password)
	{
		$_SESSION['isLogin'] = true;
		$_SESSION['member_id'] = $row['member_id'];
		$_SESSION['account'] = $row['account'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['mode'] = "st";
		// echo "login success!";
		if(isset($_SESSION['url']))
			$url = $_SESSION['url'];
		else
			$url = "stmode.php";
		Header("Location: $url");
	}
	else{
		// echo "login fail!";
		Header("Location: ../login.php");
	}

?>