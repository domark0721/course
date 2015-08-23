<!-- 使用者登入後，要記錄 -->
<?php
	//connect mysqldb
	require ("../mysql.php");

	//get user id and pwd
	session_start();
	$account=$_POST['account'];
	$password=$_POST['password'];

	if(empty($account) || empty($password)) {
		Header("Location: /www/course/login.php");
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
		if(isset($_SESSION['url'])){
			$url = $_SESSION['url'];
			$result = array(
				'status' => 'ok',
				'url' => $url
			);
		}
		else{
			$url = "/www/course/stmode.php";
			$result = array(
				'status' => 'ok',
				'url' => $url
			);
		}
		echo json_encode($result);
	}
	else{
		// echo "login fail!";
		$url = "/www/course/login.php";
		$result = array(
			'status' => 'fail',
			'url' => $url
		);

		echo json_encode($result);
	}

?>