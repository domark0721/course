<!-- 儲存課程設定 -->
<?php
	ini_set('default_charset','utf-8');
	require ("../mysql.php");
	require ("../mongodb.php");
	
	$course_id = $_GET['course_id'];
	$teacher_name = $_POST['teacher_name'];
	$teacher_mail = $_POST['teacher_mail'];
	$website = $_POST['website'];
	$course_name = $_POST['course_name'];
	$type = $_POST['type'];
	$lang = $_POST['lang'];
	$status = $_POST['status'];
	$start_time = $_POST['start_time'];
	$tags = $_POST['tags'];
	$description = $_POST['description'];
	$syllabus = $_POST['syllabus'];
	$teachingMethods = $_POST['teachingMethods'];
	$textbooks = $_POST['textbooks'];
	$references = $_POST['references'];

	$description = replace_p_tag($description);
	$syllabus = replace_p_tag($syllabus);
	$teachingMethods = replace_p_tag($teachingMethods);
	$textbooks = replace_p_tag($textbooks);
	$references = replace_p_tag($references);
	$pic = 'img/defultCourseImg.png';
	function replace_p_tag($content){
		$content = str_replace('<p>', '', $content);
		$content = str_replace('</p>', '<br />', $content);
		return $content;
	}

	// $ = $_POST[''];
	// echo $teacher_name;
	// echo(json_encode($_POST, JSON_UNESCAPED_UNICODE));

	// update to mysql
	$updateMYSQL = "UPDATE course SET teacher_name='$teacher_name',
									  teacher_mail='$teacher_mail',
									  website='$website',
									  course_name='$course_name',
									  type='$type',
									  lang='$lang',
									  pic='$pic',
									  tags='$tags',
									  status='$status',
									  start_time='$start_time'
									  WHERE course_id=$course_id";

	$mysqlResult = mysql_query($updateMYSQL);
	
	// update to mongo

	// get objectID of this course
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery) -> limit(1);

	foreach($mon as $data){
		$courseData = $data;
		break;
		
	}

	$courseObjectID = $courseData['_id'];

	$mongoid = array('_id' => new MongoId($courseObjectID));
	$updateMONGO = array('$set' => array('description' => $description,
									   'syllabus' => $syllabus,
									   'teachingMethods' => $teachingMethods,
									   'textbooks' => $textbooks,
									   'references' => $references));
	$mongoResult = $collection->update($mongoid, 
						$updateMONGO
						); 

	if($mysqlResult == TRUE && $mongoResult['updatedExisting'] == TRUE){
		echo '<script>alert("儲存成功！");
					  window.location.href="../temode.php";
		  	 </script>';
	}
	else{
		echo '<script>alert("儲存失敗！");
					  window.location.href="../courseSetting.php?course_id=' .$course_id. '";
		  	 </script>';
	}


?>