<?php
	//connect mysqldb
	require("../mysql.php");
	$account = $_POST['account'];
	$name = $_POST['name'];
	$password = $_POST['password'];

	//insert new member to mysqlbd
	$newMember = "INSERT INTO member(account, password, name)VALUES('$account', '$password', '$name')" ;

	// echo $newMember;
	if(mysql_query($newMember, $con )){
		// echo "register success";
		// session_start();
		// $_SESSION['isLogin'] = true;
		// $_SESSION['account'] = $account;
		// $_SESSION['name'] = $name;
		// $_SESSION['mode'] = "st";
		Header("Location: ../login.php");
	}
	else{
		// echo "register fail";
		Header("Location: ../register.php");
	}
?>