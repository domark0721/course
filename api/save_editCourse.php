<!-- 儲存編輯的課程內容 -->
<?php
	date_default_timezone_set('Asia/Taipei');
	require ("../mongodb.php");

	$course_id = $_POST['course_id'];
	$chapters = $_POST['chapters'];

	// get objectID of this course
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery) -> limit(1);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}

	$courseObjectID = $courseData['_id'];

	$mongoid = array('_id' => new MongoId($courseObjectID));

	$updateMONGO = array(
						'$set' => array(
							'content' => array(
								'chapters' => $chapters
							))
						);

	$mongoResult = $collection->update($mongoid, $updateMONGO);
	// var_dump($db->lastError());

	$lastEditDate = date("Y-m-d H:i:s");
    // prepare response
	if ($mongoResult['err']) {
		$response = array(
			'status' => 'error',
			'error_message' => $mongoResult['errmsg']
		);
	} else {
		$response = array(
			'status' => 'ok',
			'error_message' => '',
			'lastEditDate' => $lastEditDate

		);	
	}

	echo json_encode($response);
?>