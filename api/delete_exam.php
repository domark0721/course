<?php
	require ("../mysql.php");
	$exam_id = $_POST['exam_id'];

	$sql = "DELETE FROM exam WHERE id='$exam_id'";
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