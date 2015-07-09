<?php
	ini_set('default_charset','utf-8');
	include_once("../mongodb.php");

	$author_id = $_POST['author_id'];
	$course_id = $_POST['course_id'];
	$type = $_POST['type'];

	// get course chapter content from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	$contentData = $courseData['content'];
	
	//all sectionName
	foreach($contentData['chapters'] as $i => $chapter){
		foreach($chapter['sections'] as $j => $section){
			$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
			$courseURL = sprintf("../courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																			,$courseData['course_id'], $i, $j);
			$sectionNameArray[$section['uid']] = $sectionName;						
			$courseURLArray[$section['uid']] = $courseURL;
		}
	}

	if($type=="TRUE_FALSE"){
		$answer = $_POST['answer'];
		$commonArray = commonInfo();
		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => $commonArray[level],
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
		$exercise_id = $mongoInitData['_id'];

		//common filed
		$levelStart = levelStartGern($commonArray[level]);
		$time = timeGern($commonArray[min],$commonArray[sec]);
		$tagsHTML = tagGern($commonArray[tags]);
		$sectionHTML = sectionGern($commonArray[is_test], $courseURLArray[$commonArray[test_section]],$sectionNameArray[$commonArray[test_section]]);

		// combine all html
		$truefalseHtml = "";
		$truefalseHtml .= '<li class="true_false_wrap questionItem" data-exercise-id="' . $exercise_id .'" data-exercise-type="TRUE_FALSE" data-section-uid="'. $commonArray[test_section] .'" data-section-name="' .$sectionNameArray[$commonArray[test_section]] . '">';
		$truefalseHtml .= '<div class="true_false_answer_wrap">';

			if($answer == "true"){
				$truefalseHtml .= '<a class="trueFalseAnswer">Ｏ</a>';
			}else if($answer == "false"){
				$truefalseHtml .= '<a class="trueFalseAnswer">Ｘ</a>';
			}

		$truefalseHtml .= '</div><div class="tfQuestion">'. $commonArray[question] .'</div>';
		$truefalseHtml .= '<div class="question_editor_wrap"><div class="questionInfo">';
		$truefalseHtml .= '<a class="level" data-level="' . $commonArray[level] . '">難易度：' . $levelStart . '</a>';
		$truefalseHtml .= '<a class="time" data-time="'.$time.'">答題時間：'. $time .'</a>';	
		$truefalseHtml .= '<div class="tags">'.$tagsHTML.'</div></div>';
		$truefalseHtml .= '<div class="for_section">'. $sectionHTML. '</div></div>';
		$truefalseHtml .= '<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span></li>';
		
		$back_tag = '#true_false';
		location($back_tag, $mongoResult, $course_id, $truefalseHtml);

	}else if($type=="SHORT_ANSWER"){
		$answer = $_POST['answer'];
		$commonArray = commonInfo();
		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => $commonArray[level],
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
		$exercise_id = $mongoInitData['_id'];

		//common filed
		$levelStart = levelStartGern($commonArray[level]);
		$time = timeGern($commonArray[min],$commonArray[sec]);
		$tagsHTML = tagGern($commonArray[tags]);
		$sectionHTML = sectionGern($commonArray[is_test], $courseURLArray[$commonArray[test_section]],$sectionNameArray[$commonArray[test_section]]);

		// combine all html
		$shortAnswerHtml = "";
		$shortAnswerHtml .= '<li class="short_answer_wrap questionItem" data-exercise-id="' . $exercise_id .'" data-exercise-type="SHORT_ANSWER" data-section-uid="'. $commonArray[test_section] .'" data-section-name="' .$sectionNameArray[$commonArray[test_section]] . '">';
		$shortAnswerHtml .= '<div class="short_answer_answer_wrap">';
		$shortAnswerHtml .= '<a>'. $answer .'</a>';
		$shortAnswerHtml .= '</div><div class="Question">'. $commonArray[question] .'</div>';
		$shortAnswerHtml .= '<div class="question_editor_wrap"><div class="questionInfo">';
		$shortAnswerHtml .= '<a class="level" data-level="' . $commonArray[level] . '">難易度：' . $levelStart . '</a>';
		$shortAnswerHtml .= '<a class="time" data-time="'.$time.'">答題時間：'. $time .'</a>';	
		$shortAnswerHtml .= '<div class="tags">'.$tagsHTML.'</div></div>';
		$shortAnswerHtml .= '<div class="for_section">'. $sectionHTML. '</div></div>';
		$shortAnswerHtml .= '<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span></li>';
		
		$back_tag = '#short_answer';
		location($back_tag, $mongoResult, $course_id, $shortAnswerHtml);

	}else if($type=="SINGLE_CHOICE"){
		for($a=1 ;$a<=4; $a++){
			$opt_content[$a] = $_POST['single_opt_content_' . $a .''];
		}
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
			"level" => $commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"create_date" => $commonArray[create_date],
			"body" => array(
				"question" => $commonArray[question],
				"options" => [
					array(
						"content" => $opt_content[1],
						"is_answer" => $ansArray[1]),
					array(
						"content" => $opt_content[2],
						"is_answer" => $ansArray[2]),
					array(
						"content" => $opt_content[3],
						"is_answer" => $ansArray[3]),
					array(
						"content" => $opt_content[4],
						"is_answer" => $ansArray[4]),
					]
				)
			);
		$mongoResult = $exercise->insert($mongoInitData);
		$exercise_id = $mongoInitData['_id'];

		//common filed
		$levelStart = levelStartGern($commonArray[level]);
		$time = timeGern($commonArray[min],$commonArray[sec]);
		$tagsHTML = tagGern($commonArray[tags]);
		$sectionHTML = sectionGern($commonArray[is_test], $courseURLArray[$commonArray[test_section]],$sectionNameArray[$commonArray[test_section]]);

		// combine all html
		$singleHtml = '';
		$singleHtml .= '<li class="single_choice_wrap questionItem" data-exercise-id="'.$exercise_id.'" data-exercise-type="SINGLE_CHOICE" data-section-uid="'. $commonArray[test_section] .'" data-section-name="' .$sectionNameArray[$commonArray[test_section]] . '">';
		$singleHtml .= '<div class="question">'.$commonArray[question].'</div>';
		
			$optHTML = '';
			for($i=1 ;$i <=4 ; $i++){
				if($ansArray[$i] == true){
					$optHTML .= '<a class="opt_true">'. $opt_content[$i] .'</a>';
				}else{
					$optHTML .= '<a>'. $opt_content[$i] .'</a>';
				}
			}

		$singleHtml .= '<div class="single_choice_answer_wrap">' .$optHTML. '</div>';
		$singleHtml .= '<div class="question_editor_wrap"><div class="questionInfo">';
		$singleHtml .= '<a class="level" data-level="' .$commonArray[level]. '">難易度：'.$levelStart.'</a>';
		$singleHtml .= '<a class="time" data-time="' .$time. '">答題時間：' .$time. '</a>';
		$singleHtml .= '<div class="tags">'.$tagsHTML.'</div></div>';
		$singleHtml .= '<div class="for_section">'. $sectionHTML. '</div></div>';
		$singleHtml .= '<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span></li>';

		$back_tag = '#single_choice';
		location($back_tag, $mongoResult, $course_id, $singleHtml);

	}else if($type=="MULTI_CHOICE"){
		for($b=1; $b<=5; $b++){
			$opts_content[$b] = $_POST['multi_opt_content_'.$b.''];
		}
		$answers = $_POST['answers'];
		$commonArray = commonInfo();

		$ansArray = array();
		for($i=1; $i<=5; $i++){
			$ansArray[$i] = false;
		}
		foreach($answers as $value){
			$ansArray[$value] = true;
		}

		$mongoInitData = array(
			"author_id" => (int)$author_id,
			"course_id" => (int)$course_id,
			"type" => $type,
			"tags" => $commonArray[tags],
			"level" => $commonArray[level],
			"time" => $commonArray[min].":".$commonArray[sec],
			"is_test" => $commonArray[is_test],
			"test_section" => $commonArray[test_section],
			"create_date" => $commonArray[create_date],
			"body" => array(
				"question" => $commonArray[question],
				"options" => [
					array(
						"content" => $opts_content[1],
						"is_answer" => $ansArray[1]),
					array(
						"content" => $opts_content[2],
						"is_answer" => $ansArray[2]),
					array(
						"content" => $opts_content[3],
						"is_answer" => $ansArray[3]),
					array(
						"content" => $opts_content[4],
						"is_answer" => $ansArray[4]),
					array(
						"content" => $opts_content[5],
						"is_answer" => $ansArray[5]),
					]
				)
			);		
		$mongoResult = $exercise->insert($mongoInitData);
		$exercise_id = $mongoInitData['_id'];

		//common filed
		$levelStart = levelStartGern($commonArray[level]);
		$time = timeGern($commonArray[min],$commonArray[sec]);
		$tagsHTML = tagGern($commonArray[tags]);
		$sectionHTML = sectionGern($commonArray[is_test], $courseURLArray[$commonArray[test_section]],$sectionNameArray[$commonArray[test_section]]);
		// combine all html
		$multiHTML = '';
		$multiHTML .= '<li class="multi_choice_wrap questionItem" data-exercise-id="' .$exercise_id. '" data-exercise-type="MULTI_CHOICE" data-section-uid="'. $commonArray[test_section] .'" data-section-name="' .$sectionNameArray[$commonArray[test_section]] . '">';
		$multiHTML .= '<div class="question">' .$commonArray[question]. '</div>';
			$optsHTML = '';
			for($k=1 ;$k <=5 ; $k++){
				if($ansArray[$k] == true){
					$optsHTML .= '<a class="opt_true">'. $opts_content[$k] .'</a>';
				}else{
					$optsHTML .= '<a>'. $opts_content[$k] .'</a>';
				}
			}		

		$multiHTML .= '<div class="multi_choice_answer_wrap">' .$optsHTML. '</div>';
		$multiHTML .= '<div class="question_editor_wrap"><div class="questionInfo">';
		$multiHTML .= '<a class="level" data-level="' .$commonArray[level]. '">難易度：'.$levelStart.'</a>';
		$multiHTML .= '<a class="time" data-time="'.$time.'">答題時間：'.$time.'</a>';
		$multiHTML .= '<div class="tags">' .$tagsHTML. '</div></div>';
		$multiHTML .= '<div class="for_section">'. $sectionHTML. '</div></div>';
		$multiHTML .= '<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span></li>';

		$back_tag = '#multi_choice';
		location($back_tag, $mongoResult, $course_id, $multiHTML);

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
		}else if($is_test == "true"){
			$is_test = true;
			$test_section = $section;
		}

		return array(
				"question" => $question, 
				"tags" => $tags, 
				"level" => (int)$level, 
				"min" => $min, "sec" => $sec, 
				"is_test" => $is_test, 
				"test_section" => $test_section,
				"create_date" => $create_date
			);
	}

	function levelStartGern($starNum) {
		for($i=1; $i<=$starNum; $i++) $levelStart .= '★';

		return $levelStart;
	}

	function timeGern($min, $sec){
		$time = $min .":". $sec;
		return $time;
	}

	function tagGern($tagsOri){
		if(!empty($tagsOri)){
			$tags = explode("," ,$tagsOri);
			foreach($tags as $tag){ 
				$tagsHTML .= '<a>'.$tag.'</a>';
			}
			return $tagsHTML;
		}else{
			return "";
		}
	}

	function sectionGern($is_test, $courseURL, $courseName){
		if($is_test == false){ 
			$sectionHTML .= '<a class="is_test">適用章節： <span>未指定</span></a>';
		} else{
			$sectionHTML .= '<a class="is_test">適用章節：</a>';
			$sectionHTML .= '<a class="is_test_href" target="_blank" href="'. $courseURL .'">'.$courseName.'</a>';
		}
		return $sectionHTML;
	}
	function location($back_tag, $mongoResult, $course_id, $html) {

		if($mongoResult['ok'] == 1){
			$response = array(
				'status' => 'ok',
				'error_message' => '',
				'back_tag' => $back_tag,
				'course_id' => $course_id,
				'questionHtml' => $html
			);		
		}
		else{
			$response = array(
				'status' => 'error'
			);
		}

		echo json_encode($response);

	}


?>