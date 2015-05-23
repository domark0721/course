<?php
	include_once('api/auth.php');
	include_once("../mysql.php");

	session_start();
	$member_id = $_SESSION['member_id'];
	$course_id = $_POST['course_id'];

	$attendentCourse = "INSERT INTO attendent(course_id, member_id) VALUES ('$course_id', '$member_id')";
	// var_dump($attendentCourse);
	// exit;
	$mysqlResult = mysql_query($attendentCourse);
	var_dump($mysqlResult);
	if($mysqlResult == TRUE){
		echo '<script>window.location.href="../joinSuccess.php";
		  	 </script>';
	}
	else{
		echo '<script>window.location.href="../joinCourse.php?course_id='.$course_id.'";
		  	 </script>';
	}
?>