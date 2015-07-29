<?php
	//connect mysqldb
	require("../mysql.php");
	$account = $_POST['account'];
	$name = $_POST['name'];
	$password = $_POST['password'];

	//query id from member table
	$sql = "SELECT * FROM member WHERE account='$account'";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result); //save as array


	if(!$row['account'] == $account){
		//insert new member to mysqlbd
		$newMember = "INSERT INTO member(account, password, name)VALUES('$account', '$password', '$name')" ;

		session_start();
		$_SESSION['isLogin'] = true;
		$_SESSION['member_id'] = mysql_insert_id();
		$_SESSION['account'] = $account;
		$_SESSION['name'] = $name;
		$_SESSION['mode'] = "st";
		// echo $newMember;
		if(mysql_query($newMember, $con )){
			// echo "register success";
			$url = "stmode.php";
			$result = array(
				'status' => 'ok',
				'url' => $url
			);
			echo json_encode($result);
		}
		else{
			// echo "register fail";
			$result = array(
				'status' => 'fail'
			);

			echo json_encode($result);
		}
	}else{
		$result = array(
			'status' => 'fail',
			'errorMsg' => '帳號已被註冊!'
		);

		echo json_encode($result);
	}
?>