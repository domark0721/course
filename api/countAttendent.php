<?php
	include_once('auth.php');
	include_once("../mysql.php");

	$course_id = $_GET['course_id'];
	// $course_id = 123;

	$result = mysql_query("SELECT COUNT(*) as total FROM attendent WHERE course_id='$course_id'");
	$attendentObj = mysql_fetch_assoc($result);
	$attendentNum = $attendentObj['total'];



	$updateMYSQL = "UPDATE course SET student_num = '$attendentNum'
									  WHERE course_id=$course_id";

	$mysqlResult = mysql_query($updateMYSQL);
?>