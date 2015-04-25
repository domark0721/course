<?php
	//connect mysqldb
	require ("../mysql.php");
	session_start();

	$member_id = $_SESSION['member_id'];

	$current_lesson = "SELECT * FROM attendent as a LEFT JOIN course as b ON a.course_id = b.course_id WHERE member_id='$member_id'";
	$result = mysql_query($current_lesson);

	$attened_course = array(); //inital

	//將結果存到array裡面
	while($row = mysql_fetch_assoc($result)){
		$attened_course[] = $row;
	}
	var_dump($attened_course);
	




	// echo json_encode($rt);

?>