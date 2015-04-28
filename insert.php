<?php
	include_once("mongodb.php");
	$json_doc = array(
		"course_id" =>(int) "123",
		"memberNum" => "100",
		"description" => "瞭解計算機",
		"syllabus" => "第一章：資本概念",
		"teachingMethods" => "影片及講義",
		"textbooks" => "abc 第一版",
		"references" => "維基百科"

	);

	$collection->insert($json_doc); //insert to mongodb
?>