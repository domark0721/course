<?php
	ini_set('default_charset','utf-8');
	include_once("../mongodb.php");

	$author_id = $_POST['author_id'];
	$course_id = $_POST['course_id'];
	$type = $_POST['type'];
	$_id = $_POST['mongo_id'];
	$mongo_id = array('_id' => new MongoId($_id));

	if($type=="TRUE_FALSE"){
		$answer = $_POST['answer'];
		$commonArray = commonInfo();
		$updateCol = array( '$set' => array(
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"answer" => $answer,
				)
			));
		$mongoResult = $exercise->update($mongo_id,$updateCol);
		header('Location: ../exam/exercise.php?course_id='.$course_id.'#true_false');
	}else if($type=="SHORT_ANSWER"){
		$answer = $_POST['short_answer'];
		$commonArray = commonInfo();
		$updateCol = array( '$set' => array(
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"answer" => $answer,
				)
			));
		$mongoResult = $exercise->update($mongo_id,$updateCol);
		header('Location: ../exam/exercise.php?course_id='.$course_id.'#short_answer');
	}else if($type=="SINGLE_CHOICE"){
		$opt_content_1 = $_POST['single_opt_content_1'];
		$opt_content_2 = $_POST['single_opt_content_2'];
		$opt_content_3 = $_POST['single_opt_content_3'];
		$opt_content_4 = $_POST['single_opt_content_4'];
		$answer = $_POST['answer'];
		$commonArray = commonInfo();

		$ansArray = array();
		for($i=1; $i<=4; $i++){
			if($i == (int)$answer)
				$ansArray[$i] = true;
			else
				$ansArray[$i] = false;
		}

		$updateCol = array( '$set' => array(
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"options" => [
					array(
						"content" => $opt_content_1,
						"is_answer" => $ansArray[1]),
					array(
						"content" => $opt_content_2,
						"is_answer" => $ansArray[2]),
					array(
						"content" => $opt_content_3,
						"is_answer" => $ansArray[3]),
					array(
						"content" => $opt_content_4,
						"is_answer" => $ansArray[4]),
					]
				)
			));
		$mongoResult = $exercise->update($mongo_id, $updateCol);
		header('Location: ../exam/exercise.php?course_id='.$course_id.'#single_choice');
	}else if($type=="MULTI_CHOICE"){
		$opt_content_1 = $_POST['multi_opt_content_1'];
		$opt_content_2 = $_POST['multi_opt_content_2'];
		$opt_content_3 = $_POST['multi_opt_content_3'];
		$opt_content_4 = $_POST['multi_opt_content_4'];
		$opt_content_5 = $_POST['multi_opt_content_5'];
		$answer = $_POST['answer'];
		$commonArray = commonInfo();

		$ansArray = array();
		for($i=1; $i<=5; $i++){
			$ansArray[$i] = false;
		}
		foreach($answer as $value){
			$ansArray[$value] = true;
		}
		
		$updateCol = array( '$set' => array(
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"options" => [
					array(
						"content" => $opt_content_1,
						"is_answer" => $ansArray[1]),
					array(
						"content" => $opt_content_2,
						"is_answer" => $ansArray[2]),
					array(
						"content" => $opt_content_3,
						"is_answer" => $ansArray[3]),
					array(
						"content" => $opt_content_4,
						"is_answer" => $ansArray[4]),
					array(
						"content" => $opt_content_5,
						"is_answer" => $ansArray[5]),
					]
				)
			));		
		$mongoResult = $exercise->update($mongo_id, $updateCol);
		header('Location: ../exam/exercise.php?course_id='.$course_id.'#multi_choice');
	}else if($type=="SERIES_QUESTIONS"){

	}

	function commonInfo() {
		$question = $_POST['question'];
		$tags = $_POST['tags'];
		$level = $_POST['level'];
		$min = $_POST['min']; $sec = $_POST['sec'];
		$is_test = $_POST['is_test'];
		$section = $_POST['section'];

		if($is_test == "false"){
			$is_test = false;
		}else{
			$is_test = true;
		}

		$test_section = !empty($section) ? $section : "0";

		return array(
				"question" => $question, 
				"tags" => $tags, 
				"level" => $level, 
				"min" => $min, "sec" => $sec, 
				"is_test" => $is_test, 
				"test_section" => $test_section,
			);
	}
?>