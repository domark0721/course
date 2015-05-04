<?php

$courseID = $_POST['course_id'];
$chapters = $_POST['chapters'];

$chaptersJson = json_encode($chapters);


// save chapters to mongo


// return result response

// if success
$response = array(
	'status' => 'ok',
);
// if error
$response = array(
	'status' => 'error',
);

echo json_encode($response);

?>