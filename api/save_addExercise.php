<?php
	ini_set('default_charset','utf-8');
	include_once("../mongodb.php");

	$author_id = $_POST['author_id'];
	$course_id = $_POST['course_id'];
	$type = $_POST['type'];

	if($type=="TRUE_FALSE"){
		$answer = $_POST['answer'];
		$commonArray = commonInfo();
		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"create_date" => $commonArray[create_date],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"answer" => $answer,
				)
			);
		$mongoResult = $exercise->insert($mongoInitData);

		$back_tag = '#true_false';
		location($back_tag, $mongoResult, $course_id);

	}else if($type=="SHORT_ANSWER"){
		$short_answer = $_POST['short_answer'];
		$commonArray = commonInfo();
		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"create_date" => $commonArray[create_date],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"body" => array(
				"question" => $commonArray[question],
				"answer" => $short_answer,
				)
			);
		$mongoResult = $exercise->insert($mongoInitData);

		$back_tag = '#short_answer';
		location($back_tag, $mongoResult, $course_id);
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

		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"create_date" => $commonArray[create_date],
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
			);
		$mongoResult = $exercise->insert($mongoInitData);
		$back_tag = '#single_choice';
		location($back_tag, $mongoResult, $course_id);

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
		
		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => (int)$commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"create_date" => $commonArray[create_date],
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
			);		
		$mongoResult = $exercise->insert($mongoInitData);
		$back_tag = '#multi_choice';
		location($back_tag, $mongoResult, $course_id);

	}else if($type=="SERIES_QUESTIONS"){
		// 尚未製作
	}

	function commonInfo() {
		$question = $_POST['question'];
		$tags = $_POST['tags'];
		$level = $_POST['level'];
		$min = $_POST['min']; $sec = $_POST['sec'];
		$is_test = $_POST['is_test'];
		$section = $_POST['section'];
		$create_date = $_POST['create_date'];
		if($is_test == "false"){
			$is_test = false;
			$test_section = 0;
		}else{
			$is_test = true;
			$test_section = $section;
		}
		return array(
				"question" => $question, 
				"tags" => $tags, 
				"level" => $level, 
				"min" => $min, "sec" => $sec, 
				"is_test" => $is_test, 
				"test_section" => $test_section,
				"create_date" => $create_date
			);
	}

	function location($back_tag, $mongoResult, $course_id) {
		var_dump($course_id.$back_tag);
		if($mongoResult['ok'] == 1){
			echo '<script>alert("題目新增成功！");
					window.location.href="../exam/addExercise.php?course_id=' . $course_id . $back_tag .'"</script>';
		}
		else{
			echo '<script>alert("題目新增失敗！");
					window.history.back()</script>';
		}

	}

?>