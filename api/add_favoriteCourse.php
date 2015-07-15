<?php
	include_once('api/auth.php');
	include_once("../mysql.php");

	$member_id = $_POST['member_id'];
	$course_id = $_POST['course_id'];
	$deleteSign = $_POST['deleteSign'];

	if($deleteSign == 0){
		$insertFavoritaCourse = "INSERT INTO favorite (member_id, course_id) VALUES ('$member_id', '$course_id')";
		$mysqlResult = mysql_query($insertFavoritaCourse);
		// var_dump($mysqlResult);
		if($mysqlResult == TRUE){
			$response = array(
				'status' => 'ok',
				'error_message' => ''
			);
		}
		else{
			$response = array(
				'status' => 'fail',
				'error_message' => mysql_error()
			);
		}

		echo json_encode($response);
	}else if($deleteSign == 1){

		$deleteFavoritaCourse = "DELETE FROM favorite WHERE member_id='$member_id' AND course_id='$course_id'";
		$mysqlResult = mysql_query($deleteFavoritaCourse);

		if($mysqlResult){
			$response = array(
				'status' => 'ok',
				'error_message' => ''
			);
		}
		else{
			$response = array(
				'status' => 'fail',
				'error_message' => mysql_error()
			);
		}

		echo json_encode($response);
	}
?>