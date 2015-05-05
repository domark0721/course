<?php
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


    // prepare response
	if ($mongoResult['err']) {
		$response = array(
			'status' => 'error',
			'error_message' => $mongoResult['errmsg']
		);
	} else {
		$response = array(
			'status' => 'ok',
			'error_message' => ''
		);	
	}

	echo json_encode($response);
?>