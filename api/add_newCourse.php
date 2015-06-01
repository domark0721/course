<?php
	ini_set('default_charset','utf-8');
	include_once("../mysql.php");
	include_once("../mongodb.php");

	$course_name = $_POST['courseName'];

	// get teacher member info (member_id from session) id, name
	session_start();
	$member_id = $_SESSION['member_id'];
	$name = $_SESSION['name'];

	// add a new course to db
	// 1. MySQL: insert row in `course` table (teacher_id, teacher_name and some default value)
	$teacherInfo = "INSERT INTO course(course_name, teacher_id, teacher_name)VALUES('$course_name', '$member_id', '$name')";
	$mysqlResult = mysql_query($teacherInfo);
	$course_id = mysql_insert_id();

	// 2. get course_id from step 1 (mysql), then insert row into MongoDB * with initial course data *
	// Mongo Initial Course Data
	$mongoInitData = array(
	    "course_id" => (int)$course_id,
		"description" => "尚未初始化",
		"syllabus" => "尚未初始化",
		"teachingMethods" => "尚未初始化",
		"textbooks" => "尚未填寫內容",
		"references" => "尚未填寫內容",
	    "content" => array( 
	    					"chapters" => [array(
	    							"name" => "尚未命名", "sections" => []
	    										)]
	    					)
	);

	$mongoResult = $collection->insert($mongoInitData); //insert to mongodb
	// var_dump($mongoResult);
	// exit;
	if($mysqlResult == TRUE && $mongoResult['ok'] == 1){
		echo '<script>window.location.href="../courseSetting.php?course_id=' .$course_id. '";
		  	 </script>';
	}
	else{
		echo '<script>window.location.href="../newCourse.php";
		  	 </script>';
	}
?>