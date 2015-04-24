<?php
	//connect mysqldb
	require("../mysql.php");
	$memberid = $_POST['memberid'];
	$membername = $_POST['membername'];
	$userpwd = $_POST['userpwd'];

	//insert new member to mysqlbd
	$newMember = "INSERT INTO member(memberid, userpwd, membername)VALUES('$memberid', '$userpwd', '$membername')" ;
	// echo $newMember;
	if(mysql_query($newMember, $con )){
		// echo "register success";
		session_start();
		$_SESSION['isLogin'] = true;
		$_SESSION['memberid'] = $memberid;
		$_SESSION['membername'] = $membername;
		$_SESSION['mode'] = "st";
		Header("Location: ../stmode.php");
	}
	else{
		// echo "register fail";
		Header("Location: ../register.php");
	}
?>