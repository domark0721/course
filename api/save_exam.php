<!-- 儲存測驗 -->
<?php
	include_once('api/auth.php');
	require ("../mysql.php");

	$course_id = $_POST['course_id'];
	$course_name = $_POST['course_name'];
	$type = $_POST['type'];
	$start_date = $_POST['start_date'];
	$start_time = $_POST['start_time'];
		$start_date = $start_date . " " . $start_time;
	$end_date = $_POST['end_date'];
	$end_time = $_POST['end_time'];
		$end_date = $end_date . " " . $end_time;
	$level = $_POST['level'];
	$time = $_POST['time'];
	$explanation = $_POST['explanation'];

	$fuzzy_match = $_POST['fuzzy_match'];
	$multi_score = $_POST['multi_score'];

	$questions = $_POST['exam_paper'];
	$questions = implode(",", $questions);
	// var_dump($questions);
	
	// save to mysql
	$examData = "INSERT INTO exam (course_id, course_name, type, start_date, start_time, end_date, end_time, level, fuzzy_match, multi_score, explanation, time, questions) 
						VALUES ('$course_id', '$course_name', '$type','$start_date', '$start_time', '$end_date', '$end_time', '$level', '$fuzzy_match', '$multi_score', '$explanation', '$time', '$questions')";
	$mysqlResult = mysql_query($examData);
	if($mysqlResult){
		$response = array(
			'status' => 'ok',
			'error_message' => ''
		);	
	}
	else{
		$response = array(
			'status' => 'error',
			'error_message' => mysql_error()
		);
	}

	echo json_encode($response);
	// echo json_encode($mongoResult);
?>