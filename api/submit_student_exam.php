<!-- 學生提交考卷 -->
<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	session_start();
	$course_id = $_POST['course_id'];
	$exam_id = $_POST['exam_id'];
	$member_id = $_POST['member_id'];
	$postAnswer = $_POST['answer'];
	$exam_result_id =$_POST['exam_result_id'];
	
	//FIXME: get student id from login session, for later to save exam result in DB
	
	//get the course Metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);
	
	//get the objectID of the question from mysql
	$id = $_POST['exam_id'];
	$sql = "SELECT * FROM exam WHERE id='$id'";
	$result = mysql_query($sql);
	$examMetadata = mysql_fetch_assoc($result);

	$questionList = $examMetadata['questions'];
	$questionArray = explode(",", $questionList);

	$correctQestionNum = 0;
	
	// query exercise from mongodb
	foreach($questionArray as $question){
		$subQuestion_num = 0;
		$correctSubSeriesNum = 0;
		$mongoQuery = array('_id' => new MongoId($question));
		$mon = $exercise -> find($mongoQuery);

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
				
			}else if($data['type'] == "SHORT_ANSWER"){
				// 簡答
				$answer = $data["body"]["answer"];

				if ($studentAnswer != -1){

					// fuzzy matching
					$fuzzyVaule = $examMetadata["fuzzy_match"];
					if ($fuzzyVaule == 0) {
						// 不支援fuzzy matching, 需全對才給分
						if ($studentAnswer === $answer) {
							$correctQestionNum ++;
						}
					} else {
						// 支援fuzzy matching, 使用 agrep 做容錯比對
						$agrepResult = shell_exec("echo \"$answer\" | /usr/local/bin/agrep -$fuzzyVaule \"$studentAnswer\"");

						if (!empty($agrepResult) && trim($agrepResult) == $answer) {
							$correctQestionNum ++;
						}
					}
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
				if ($studentAnswer != -1) {
					$isMultiScore = $examMetadata['multi_score'];

					$answers = [];
					foreach ($data["body"]["options"] as $key => $option) {
						if ($option["is_answer"]) {
							$answers[] = $key;
						}
					}

					// convert each answer from string to int
					$studentAnswer = array_map('intval', $studentAnswer);

					if ($isMultiScore == 0) {
						if ($studentAnswer === $answers) {
							$correctQestionNum ++;
						}					
					} else {

						$correntOptions = 0;
						for ($i = 0; $i < 5 ; $i++) {
							if (in_array($i, $answers)) {
								if (in_array($i, $studentAnswer)) {
									$correntOptions ++;
								}
							} else {
								if (!in_array($i, $studentAnswer)) {
									$correntOptions ++;
								}
							}
						}
						$correctQestionNum = $correctQestionNum + ($correntOptions / 5);
					}					
				}

			}else if($data['type'] == "SERIES_QUESTIONS"){
				// var_dump($data);
				$answers = [];
				foreach($data['body']['questions'] as $sub_question){
					foreach ($sub_question['options'] as $key => $question) {
						if($question['is_answer']){
							$answers[] = $key;
							break;
						}	
					}
				}
				$subQuestion_num = count($answers); //count how many subquestion number
				foreach($studentAnswer as $key => $studentAnswer_sub){
					if($studentAnswer_sub == $answers[$key]){ 
						$correctSubSeriesNum ++;					
					}
				}
				$thisSeriesCorrectPercent = $correctSubSeriesNum / $subQuestion_num;
				$correctQestionNum += $thisSeriesCorrectPercent;
			}

		}
	}
	// var_dump($correctQestionNum);
	// calculate total score
	$questionNum = count($questionArray);
	$scoreOfEachQuestion = 100 / $questionNum;

	$totalScore = round($scoreOfEachQuestion * $correctQestionNum, 2);
	$student_answer = json_encode($postAnswer);

	// save to exam result DB
	$sql = "UPDATE exam_result SET correct_num='$correctQestionNum', total_num='$questionNum', score='$totalScore', answer_snapshot='$student_answer'
						WHERE id='$exam_result_id'";
	$result = mysql_query($sql);
	$exam_result_id = mysql_insert_id();

	// echo result back to exam.php 
	// return status, exam_result id

	$response = array(
			'status' => 'ok',
			'error_message' => '',
			'score' => $totalScore
		);

	echo json_encode($response);
?>