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

		// echo $newMember;
		if(mysql_query($newMember, $con )){
			// echo "register success";
			$url = "login.php";
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