<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	if($Member_NAME!=NULL){

	}
	else{
		Header("Location: login.php");
	}
	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='123'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $courseMetadata){

	}
	// var_dump($courseMetadata);
	// exit;
	
	//course data from mongo
	$mongoQuery = array('course_id' => 123);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}

?>