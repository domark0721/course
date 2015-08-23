<!-- 刪除題目api -->
<?php

	require ("../mongodb.php");
	$exercise_id = $_POST['exercise_mongo_id'];

	//remove exercise from mongo
	$removeExercise = array('_id' => new MongoID($exercise_id));
	$mongoResult = $exercise -> remove($removeExercise);

	if($mongoResult['err']){
		$response = array(
			'status' => "error",
			'error_message' => $mongoResult['errmsg']
		);
	}else{
		$response = array(
			'status' => "ok",
			'error_message' => ''
		);
	}

	echo json_encode($response);
?>