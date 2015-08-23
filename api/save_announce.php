<!-- 儲存公告 -->
<?php
	// include_once('isLogin.php');
	require ("../mysql.php");

	$course_id = $_POST['course_id'];
	$member_id = $_POST['member_id'];
	$title = $_POST['title'];
	$content = nl2br($_POST['content']);

	$sql = "INSERT INTO announce(course_id, member_id, title, content) VALUES ('$course_id', '$member_id', '$title', '$content')";
	$mysqlResult = mysql_query($sql);
	$annouce_id = mysql_insert_id();
	
	$sql = "SELECT create_date FROM announce WHERE id='$annouce_id'";
	$mysqlResult = mysql_query($sql);
	
	while($row = mysql_fetch_assoc($mysqlResult)){
		$announce = $row;
	}
	// var_dump($announce);

	if($mysqlResult){
		$response = array(
			'status' => 'ok',
			'error_message' => '',
			'annouce_id' => $annouce_id,
			'annouce_date' => $announce['create_date']
		);	
	}
	else{
		$response = array(
			'status' => 'error'
		);
	}

	echo json_encode($response);
?>