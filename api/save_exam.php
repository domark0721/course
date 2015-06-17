<?php
	require ("../mysql.php");

	$course_id = $_POST['course_id'];
	$course_name = $_POST['course_name'];
	$type = $_POST['type'];
	$start_date = $_POST['start_date'];
	$start_time = $_POST['start_time'];
	$end_date = $_POST['end_date'];
	$end_time = $_POST['end_time'];
	$level = 5;
	$time = "50:00";
	$explanation = $_POST['explanation'];

	$questions = $_POST['exam_paper'];
	$questions = implode(",", $questions);

	// save to mysql
	$examData = "INSERT INTO exam (course_id, course_name, type, start_date, start_time, end_date, end_time, level, explanation, time, questions) 
						VALUES ('$course_id', '$course_name', '$type','$start_date', '$start_time', '$end_date', '$end_time', '$level', '$explanation', '$time', '$questions')";
	$mysqlResult = mysql_query($examData);


	echo $course_id;
	echo json_encode($exam_paper);
?>