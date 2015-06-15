<?php
	require ("../mysql.php");

	$course_id = $_POST['course_id'];
	$exam_paper = $_POST['exam_paper'];
	$exam_paper = implode(",", $exam_paper);
	// $exam_paper = json_decode($exam_paper);

	$examData = "INSERT INTO exam (course_id, questions) VALUES ('$course_id','$exam_paper')";
	$mysqlResult = mysql_query($examData);


	echo $course_id;
	echo json_encode($exam_paper);
?>