<?php
	// include_once('isLogin.php');
	require ("../mysql.php");

	$course_id = $_POST['course_id'];
	$member_id = $_POST['member_id'];
	$title = $_POST['title'];
	$content = $_POST['content'];

	$sql = "INSERT INTO announce(course_id, member_id, title, content) VALUES ('$course_id', '$member_id', '$title', '$content')";
	$mysqlResult = mysql_query($sql);

	if($mysqlResult){
		$response = array(
			'status' => 'ok',
			'error_message' => ''
		);	
	}
	else{
		$response = array(
			'status' => 'error'
		);
	}

	echo json_encode($response);
?>