<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	var_dump($_POST['answer']);
	$postAnswer = $_POST['answer'];

	session_start();
	//FIXME: get student id from login session, for later to save exam result in DB



	//get the course Metadata from mysql=================================FIXME:
	$sql = "SELECT * FROM course WHERE course_id='123'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	$id = $_POST['exam_id'];
	//get the objectID of the question from mysql
	$sql = "SELECT * FROM exam WHERE id='$id'";
	$result = mysql_query($sql);
	$examMetadata = mysql_fetch_assoc($result);

	$questionList = $examMetadata['questions'];
	$questionArray = explode(",", $questionList);

	$correctQestionNum = 0;

	// query exercise from mongodb
	foreach($questionArray as $question){
		$mongoQuery = array('_id' => new MongoId($question));
		$mon = $exercise -> find($mongoQuery);
		// var_dump($mon);

		foreach($mon as $data){
			// get user answer of that question by id
			$question_id = (string)$data["_id"];
			$studentAnswer = $postAnswer[$question_id];
			// var_dump($data);

			if($data['type'] == "TRUE_FALSE"){
				// 是非

				$answer = $data["body"]["answer"];

				if ($studentAnswer != -1 && $studentAnswer == $answer) {
					$correctQestionNum ++;
				}
				
			}else if($data['type'] == "SINGLE_CHOICE"){
				// 單選

				foreach ($data["body"]["options"] as $key => $option) {
					if ($option["is_answer"]) {
						$answer = $key;
						break;
					}
				}

				$studentAnswer = (int) $studentAnswer;

				if ($studentAnswer != -1 && $studentAnswer == $answer) {
					$correctQestionNum ++;
				}

			}else if($data['type'] == "MULTI_CHOICE"){
				// 多選

				$answers = [];
				foreach ($data["body"]["options"] as $key => $option) {
					if ($option["is_answer"]) {
						$answers[] = $key;
					}
				}
				// convert each answer from string to int
				$studentAnswer = array_map('intval', $studentAnswer);

				if ($studentAnswer != -1 && $studentAnswer === $answers) {
					$correctQestionNum ++;
				}

			}else if($data['type'] == "SERIES_QUESTIONS"){
				// TODO
			}

		}
	}

	// calculate total score
	$questionNum = count($questionArray);
	$scoreOfEachQuestion = 100 / $questionNum;

	$totalScore = $scoreOfEachQuestion * $correctQestionNum;

	// save to exam result DB
	// course_id, exam_id, student_id, score, .... , answer_snap host

	// echo result back to exam.php 
	// return status, exam_result id

?>