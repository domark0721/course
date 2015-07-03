<?php

	require ("../mysql.php");
	$announce_id = $_POST['announce_id'];

	//remove announce from mysql
	$sql = "DELETE FROM announce WHERE id='$announce_id'";
	$result = mysql_query($sql);

	if($result){
		$response = array(
			'status' => "ok",
			'error_message' => $mongoResult['errmsg']
		);
	}else{
		$response = array(
			'status' => "fail",
			'error_message' => mysql_error()
		);
	}

	echo json_encode($response);
?>