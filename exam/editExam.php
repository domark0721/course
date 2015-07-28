<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$author_id = $_SESSION['member_id'];

	// get POST data from addExam page (exam name....)
	$course_id = $_POST['course_id'];
	$course_name = $_POST['course_name'];
	$type = $_POST['type'];
	$from_date = $_POST['from_date'];
	// exam period start 
	$start_date = $_POST['start_date'];
		$start_hour = $_POST['start_hour'];
		$start_min = $_POST['start_min'];
	$start_time = $start_hour . ':' . $start_min ;
	$end_date = $_POST['end_date'];
		$end_hour = $_POST['end_hour'];
		$end_min = $_POST['end_min'];
	$end_time = $end_hour . ':' . $end_min ;
	// exam period end
	$explanation = $_POST['explanation'];
	$generateType = $_POST['generateType'];

	if($generateType == "autoMode"){
		$level = $_POST['level'];
		$time = $_POST['time'];
		$check_time = $_POST['check_time'];
		$trueFalseNum = $_POST['trueFalse'];
		$shortAnswerNum = $_POST['shortAnswer'];
		$singleChoiceNum = $_POST['singleChoice'];
		$multiChoiceNum = $_POST['multiChoice'];
		$seriesQuesNum = $_POST['seriesQues'];
		$chapterRange = $_POST['chapter_range'];
	}

	$generate_type = $generateType;

	// Get course meta data
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	// get all questions of the course from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $exercise -> find($mongoQuery) -> sort(array('create_date' => -1));

	// group questions by type
	$trueFalseQues = array();
	$shortAnswerQues = array();
	$singleChoiceQues = array();
	$multiChoiceQues = array();
	$seriesQues = array();

	foreach($mon as $data){
		if($data['type'] == "TRUE_FALSE"){
			$trueFalseQues[] = $data;
		}else if($data['type'] == "SHORT_ANSWER"){
			$shortAnswerQues[] = $data;
		}else if($data['type'] == "SINGLE_CHOICE"){
			$singleChoiceQues[] = $data;
		}else if($data['type'] == "MULTI_CHOICE"){
			$multiChoiceQues[] = $data;
		}else if($data['type'] == "SERIES_QUESTIONS"){
			$seriesQues[] = $data;
		}
	}

	// start to generate exam:
	$questionList = array();
	$examList = array();
	$countTrueFalse = 0;
	$countShortAnswer = 0;
	$countSingleChoice = 0;
	$countMultiChoice = 0;
	$countSeriesQues = 0;


	if ($generate_type == 'manualMode') {
		$questionList['trueFalseQues'] = $trueFalseQues;
		$questionList['shortAnswerQues'] = $shortAnswerQues;
		$questionList['singleChoiceQues'] = $singleChoiceQues;
		$questionList['multiChoiceQues'] = $multiChoiceQues;
		$questionList['seriesQues'] = $seriesQues;

	}else if($generate_type == 'autoMode') {

		// ---- 是非題
		$isValidQues = array();
		$leftQues = array();

		// 篩選題目章節範圍
		foreach ($trueFalseQues as $question) {
			if (in_array($question['test_section'], $chapterRange)) {
				$isValidQues[] = $question;
			} else {
				$leftQues[] = $question;
			}
		}

		// 隨機排序
		shuffle($isValidQues);

		if (count($isValidQues) <= $trueFalseNum) {
			// 如果想要的題數大於篩選過的實際題數，全部加進去考卷
			$countTrueFalse += count($isValidQues);
			$examList = array_merge($examList, $isValidQues);
		} else {
			// 如果想要的題數小於篩選過的實際題數，加入想要的題數進考卷
			$pickedQues = array_slice($isValidQues, 0, $trueFalseNum);
			$unPickedQues = array_slice($isValidQues, $trueFalseNum);

			$countTrueFalse += count($pickedQues);

			$examList = array_merge($examList, $pickedQues);
			$leftQues = array_merge($leftQues, $unPickedQues);
		}

		// 剩餘的題目塞到右側待編輯
		$questionList['trueFalseQues'] = $leftQues;


		// ---- 簡答題
		$isValidQues = array();
		$leftQues = array();

		// 篩選題目章節範圍
		foreach ($shortAnswerQues as $question) {
			if (in_array($question['test_section'], $chapterRange)) {
				$isValidQues[] = $question;
			} else {
				$leftQues[] = $question;
			}
		}

		// 隨機排序
		shuffle($isValidQues);

		if (count($isValidQues) <= $shortAnswerNum) {
			// 如果想要的題數大於篩選過的實際題數，全部加進去考卷
			$countShortAnswer += count($isValidQues);
			$examList = array_merge($examList, $isValidQues);
		} else {
			// 如果想要的題數小於篩選過的實際題數，加入想要的題數進考卷
			$pickedQues = array_slice($isValidQues, 0, $shortAnswerNum);
			$unPickedQues = array_slice($isValidQues, $shortAnswerNum);

			$countShortAnswer += count($pickedQues);

			$examList = array_merge($examList, $pickedQues);
			$leftQues = array_merge($leftQues, $unPickedQues);
		}

		// 剩餘的題目塞到右側待編輯
		$questionList['shortAnswerQues'] = $leftQues;

		// ---- 單選題
		$isValidQues = array();
		$leftQues = array();

		// 篩選題目章節範圍
		foreach ($singleChoiceQues as $question) {
			if (in_array($question['test_section'], $chapterRange)) {
				$isValidQues[] = $question;
			} else {
				$leftQues[] = $question;
			}
		}

		// 隨機排序
		shuffle($isValidQues);

		if (count($isValidQues) <= $singleChoiceNum) {
			// 如果想要的題數大於篩選過的實際題數，全部加進去考卷
			$countSingleChoice += count($isValidQues);
			$examList = array_merge($examList, $isValidQues);
		} else {
			// 如果想要的題數小於篩選過的實際題數，加入想要的題數進考卷
			$pickedQues = array_slice($isValidQues, 0, $singleChoiceNum);
			$unPickedQues = array_slice($isValidQues, $singleChoiceNum);

			$countSingleChoice += count($pickedQues);

			$examList = array_merge($examList, $pickedQues);
			$leftQues = array_merge($leftQues, $unPickedQues);
		}

		// 剩餘的題目塞到右側待編輯
		$questionList['singleChoiceQues'] = $leftQues;


		// ---- 多選題
		$isValidQues = array();
		$leftQues = array();

		// 篩選題目章節範圍
		foreach ($multiChoiceQues as $question) {
			if (in_array($question['test_section'], $chapterRange)) {
				$isValidQues[] = $question;
			} else {
				$leftQues[] = $question;
			}
		}

		// 隨機排序
		shuffle($isValidQues);

		if (count($isValidQues) <= $multiChoiceNum) {
			// 如果想要的題數大於篩選過的實際題數，全部加進去考卷
			$countMultiChoice += count($isValidQues);
			$examList = array_merge($examList, $isValidQues);
		} else {
			// 如果想要的題數小於篩選過的實際題數，加入想要的題數進考卷
			$pickedQues = array_slice($isValidQues, 0, $multiChoiceNum);
			$unPickedQues = array_slice($isValidQues, $multiChoiceNum);

			$countMultiChoice += count($pickedQues);

			$examList = array_merge($examList, $pickedQues);
			$leftQues = array_merge($leftQues, $unPickedQues);
		}

		// 剩餘的題目塞到右側待編輯
		$questionList['multiChoiceQues'] = $leftQues;


		// ---- 題組
		$isValidQues = array();
		$leftQues = array();

		// 篩選題目章節範圍
		foreach ($seriesQues as $question) {
			if (in_array($question['test_section'], $chapterRange)) {
				$isValidQues[] = $question;
			} else {
				$leftQues[] = $question;
			}
		}

		// 隨機排序
		shuffle($isValidQues);

		if (count($isValidQues) <= $seriesQuesNum) {
			// 如果想要的題數大於篩選過的實際題數，全部加進去考卷
			$countSeriesQues += count($isValidQues);
			$examList = array_merge($examList, $isValidQues);
		} else {
			// 如果想要的題數小於篩選過的實際題數，加入想要的題數進考卷
			$pickedQues = array_slice($isValidQues, 0, $seriesQuesNum);
			$unPickedQues = array_slice($isValidQues, $seriesQuesNum);

			$countSeriesQues += count($pickedQues);

			$examList = array_merge($examList, $pickedQues);
			$leftQues = array_merge($leftQues, $unPickedQues);
		}

		// 剩餘的題目塞到右側待編輯
		$questionList['seriesQues'] = $leftQues;
	}

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

?>
<!doctype html>
<html>
	<head>
		<?php require("exam_meta.php") ?>
		<!-- <link type="text/css" rel="stylesheet" href="../css/exercise.css"> -->
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css">
		<link href="../css/jquery.tagit.css" rel="stylesheet" type="text/css">
		<link type="text/css" rel="stylesheet" href="../css/editExam.css">
		<link type="text/css" rel="stylesheet" href="../css/editExam_add.css">
		<link type="text/css" rel="stylesheet" href="../css/editExam_setting.css">
		<title>考卷編輯系統 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_editExam.php"); ?>
			<div class="exam_status_wrap">
				<span class="examSetting"><i class="fa fa-gear"></i>&nbsp;&nbsp;設定</span>
				<span class="newQuestionBtn"><i class="fa fa-plus"></i>&nbsp;&nbsp;題目</span>
				<div class="examInfo">
				<a>平均難度：<span id="exam_level">0 / 5</span></a>
				<a>總時間：<span id="exam_time">0分0秒</span></a><br>
				</div>
				<div class="numberInfo">
					<a>總題數：<span id="total_num"><?php echo $countTrueFalse+$countShortAnswer+$countSingleChoice+$countMultiChoice+$countSeriesQues?></span></a>
					<a>是非：<span id="trueFalse_num"><?php echo $countTrueFalse;?></span><input id="trueFalsePer" class="scorePercent"></a>
					<a>簡答：<span id="shortAnswer_num"><?php echo $countShortAnswer;?></span></a>
					<a>單選：<span id="single_num"><?php echo $countSingleChoice;?></span></a>
					<a>多選：<span id="multi_num"><?php echo $countMultiChoice;?></span></a>
					<a>題組：<span id="series_num"><?php echo $countSeriesQues;?></span></a>
				</div>
			</div>
			<div class="editExamWrap">
				<div class="editExamContainer">
					<div class="left-container">
						<ol id="drop-question-list">
						<!-- 是非題 -->
						<?php foreach($examList as $question){
							$type = $question['type'];
							if($type == "TRUE_FALSE"){
								$trueFalseQuesBody = $question['body'];?>
								<li class="true_false_wrap questionItem">
									<div class="true_false_answer_wrap">
										<?php if($trueFalseQuesBody['answer'] == true){ ?>
											<a class="trueFalseAnswer">Ｏ</a>
										<?php }else if($trueFalseQuesBody['answer'] == false){ ?>
											<a class="trueFalseAnswer">Ｘ</a>
										<?php } ?>
									</div>
									<div class="tfQuestion"><?php echo $trueFalseQuesBody['question'];?></div>
									<div class="question_editor_wrap">
										<div class="questionInfo">
											<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
											<a class="time">答題時間：<?php echo $question['time']; ?></a>
											<div class="tags">
											<?php
											if(!empty($question['tags'])){
												$tags = explode("," ,$question['tags']);
												foreach($tags as $tag){ ?>
													<a><?php echo $tag;?></a>
										<?php }}?>
											</div>
										</div>
										<div class="for_section">
											<?php if($question['test_section'] == "0"){ ?>
												<a class="is_test">適用章節： <span>未指定</span></a>
											<?php } else{
													foreach($contentData['chapters'] as $i => $chapter){
														foreach($chapter['sections'] as $j => $section){
															$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
															$courseURL = sprintf("../courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																					,$courseData['course_id'], $i, $j);

															if($section['uid'] == $question['test_section']){ ?>
																<a class="is_test">適用章節：</a>
																<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
											<?php 			}
														}
													}
											}?>
										</div>
									</div>
								</li>
						<?php }else if($type == "SHORT_ANSWER"){
								$shortAnswerQuesBody = $question['body'];
						?>
								<li class="short_answer_wrap questionItem">
									<div class="question"><?php echo $shortAnswerQuesBody['question'];?></div>
									<div class="short_answer_answer_wrap">
										<a><?php echo $shortAnswerQuesBody['answer'];?></a>
									</div>
									<div class="question_editor_wrap">
										<div class="questionInfo">
											<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
											<a class="time">答題時間：<?php echo $question['time']; ?></a>
											<div class="tags">
											<?php
											if(!empty($question['tags'])){
												$tags = explode("," ,$question['tags']);
												foreach($tags as $tag){ ?>
													<a><?php echo $tag;?></a>
										<?php }}?>
											</div>
										</div>
										<div class="for_section">
											<?php if($question['test_section'] == "0"){ ?>
												<a class="is_test">適用章節： <span>未指定</span></a>
											<?php } else{
													foreach($contentData['chapters'] as $i => $chapter){
														foreach($chapter['sections'] as $j => $section){
															$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
															$courseURL = sprintf("../courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																					,$courseData['course_id'], $i, $j);

															if($section['uid'] == $question['test_section']){ ?>
																<a class="is_test">適用章節：</a>
																<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
											<?php 			}
														}
													}
											}?>
										</div>
									</div>
								</li>
							<?php } else if($type == 'SINGLE_CHOICE'){
											$singleChoiceQuesBody = $question['body'];
											$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];
							?>
									<li class="single_choice_wrap questionItem">
										<div class="question"><?php echo $singleChoiceQuesBody['question'];?><!-- <span class="questionType"> ( 單選 )</span> --></div>
										<div class="single_choice_answer_wrap">
											<?php foreach($singleChoiceQuesOpt as $j => $options){
												if($options['is_answer'] == true){?>
													<a class="opt_true"><?php echo $options['content'];?></a>
											<?php }else{  ?>
													<a><?php echo $options['content'];?></a>
											<?php } }?>	
										</div>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
												<a class="time">答題時間：<?php echo $question['time']; ?></a>
												<div class="tags">
													<?php
													if(!empty($question['tags'])){
														$tags = explode("," ,$question['tags']);
														foreach($tags as $tag){ ?>
															<a><?php echo $tag;?></a>
												<?php }}?>
												</div>
											</div>
											<div class="for_section">
												<?php if($question['test_section'] == "0"){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{
														foreach($contentData['chapters'] as $i => $chapter){
															foreach($chapter['sections'] as $j => $section){
																$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
																$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																						,$courseData['course_id'], $i, $j);

																if($section['uid'] == $question['test_section']){ ?>
																	<a class="is_test">適用章節：</a>
																	<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php 			}
															}
														}
												}?>
											</div>
										</div>
									</li>
								<?php  }else if($type == 'MULTI_CHOICE'){
									$multiChoiceQuesBody = $question['body'];
									$multiChoiceQuesOpt = $multiChoiceQuesBody['options'];
								?>
									<li class="multi_choice_wrap questionItem">
											<div class="question"><?php echo $multiChoiceQuesBody['question'];?><!-- <span class="questionType"> ( 多選 )</span> --></div>
											<div class="multi_choice_answer_wrap">
												<?php foreach($multiChoiceQuesOpt as $j => $options){
													if($options['is_answer'] == true){?>
														<a class="opt_true"><?php echo $options['content'];?></a>
												<?php }else{  ?>
														<a><?php echo $options['content'];?></a>
												<?php } }?>	
											</div>
											<div class="question_editor_wrap">
												<div class="questionInfo">
													<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
													<a class="time">答題時間：<?php echo $question['time']; ?></a>
													<div class="tags">
													<?php
													if(!empty($question['tags'])){
														$tags = explode("," ,$question['tags']);
														foreach($tags as $tag){ ?>
															<a><?php echo $tag;?></a>
												<?php }}?>
													</div>
												</div>
												<div class="for_section">
													<?php if($question['test_section'] == "0"){ ?>
														<a class="is_test">適用章節： <span>未指定</span></a>
													<?php } else{
															foreach($contentData['chapters'] as $i => $chapter){
																foreach($chapter['sections'] as $j => $section){
																	$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
																	$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																							,$courseData['course_id'], $i, $j);

																	if($section['uid'] == $question['test_section']){ ?>
																		<a class="is_test">適用章節：</a>
																		<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
													<?php 			}
																}
															}
													}?>
												</div>
											</div>
									</li>
								<?php } else if($type == 'SERIES_QUESTIONS'){
									$seriesQuesHeader = $question['body'];
								?>
									<li class="series_question_wrap questionItem">
										<div class="question"><?php echo $seriesQuesHeader['description'];?></div>
										<ul class="seriesNum">		
											<?php foreach($seriesQuesHeader['questions'] as $j => $question){ 
													$questionOpt = $question['options']; ?>
											<li>	
												<div class="series_question"><?php echo $question['question'];?></div>
												<div class="series_question_answer_wrap">
													<?php foreach($questionOpt as $k => $options){
														if($options['is_answer'] == true){?>
															<a class="opt_true"><?php echo $options['content'];?></a>
													<?php }else{  ?>
															<a><?php echo $options['content'];?></a>
													<?php } }?>	
												</div>
											</li>
											<?php }?>
										</ul>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $questionHeader['level'];?>">難易度：<?php for($i=1; $i<=$questionHeader['level']; $i++) echo '★';?></a>
												<a class="time">答題時間：<?php echo $questionHeader['time']; ?></a>
												<div class="tags"><a>演算法</a><a>Binary Tree</a></div>
											</div>
											<div class="for_section">
												<?php if($questionHeader['is_test'] == false){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{
														foreach($contentData['chapters'] as $i => $chapter){
															foreach($chapter['sections'] as $j => $section){
																$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
																$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																						,$courseData['course_id'], $i, $j);

																if($section['uid'] == $question['test_section']){ ?>
																	<a class="is_test">適用章節：</a>
																	<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php 			}
															}
														}
												}?>
											</div>
										</div>
									</li>
								<?php } }?>
						</ol>
					</div>
					<!-- 題庫 -->
					<div class="right-container">
						<div class="search_wrap"> 
							<input id="search_exercise" class="clearable" placeholder="搜尋題目"/>
							<div class="search_condition">
								<a id="search_tag" class="search_opt">TAG</a>
								<a id="search_section" class="search_opt">SECTION</a>
								<a id="search_level" class="search_opt">LEVEL</a>
							</div>
						</div>
						<div id="exerciseList" class="">
							<div class="nav-wrap">
									<div class="userControl">
										<ul class="tab-list">
											<li><a href="#true_false">是非題</a></li>
											<li><a href="#short_answer">簡答</a></li>
											<li><a href="#single_choice">單選題</a></li>
											<li><a href="#multi_choice">多選題</a></li>
											<li><a href="#series_question">題組</a></li>
										</ul>
									</div>
							</div>
							<!-- ************* 是非題 ************* -->
							<ul id="true_false" class="tab-content questionNum">
							<?php 
								if(!empty($questionList['trueFalseQues'])){
									foreach($questionList['trueFalseQues'] as $i => $question){
										$trueFalseQuesBody = $question['body']; ?>
									<li class="true_false_wrap questionItem notSelect" data-exercise-id="<?php echo $question['_id'];?>" 
																			 data-exercise-type="TRUE_FALSE" 
																			 data-section-uid="<?php echo $question['test_section'];?>" 
																			 data-section-name="<?php echo $sectionNameArray[$question['test_section']];?>">
										<div class="true_false_answer_wrap">
											<?php if($trueFalseQuesBody['answer'] == true){ ?>
												<a class="trueFalseAnswer">Ｏ</a>
											<?php }else if($trueFalseQuesBody['answer'] == false){ ?>
												<a class="trueFalseAnswer">Ｘ</a>
											<?php } ?>
										</div>
										<div class="tfQuestion"><?php echo nl2br($trueFalseQuesBody['question']);?></div>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
												<a class="time" data-time="<?php echo $question['time']; ?>">答題時間：<?php echo $question['time']; ?></a>
												<div class="tags">
												<?php
												if(!empty($question['tags'])){
													$tags = explode("," ,$question['tags']);
													foreach($tags as $tag){ ?>
														<a><?php echo $tag;?></a>
											<?php }}?>
												</div>
											</div>
											<div class="for_section">
												<?php if($question['test_section'] == "0"){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
												<?php 	}
												 ?>
											</div>
										</div>
										<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span>
									</li>
								<?php } }else {?>
									<div class="noQuestion">
										<img src="../img/oops.png">
										<a>此題形沒有資料 :(</a>
									</div>
								<?php }?>
							</ul>
							<!-- ************* 簡答題 ************* -->
							<ul id="short_answer" class="tab-content questionNum">
							<?php if(!empty($questionList['shortAnswerQues'])){
									foreach($questionList['shortAnswerQues'] as $i => $question){
										$shortAnswerQuesBody = $question['body'];
										?>
									<li class="short_answer_wrap questionItem notSelect" data-exercise-id="<?php echo $question['_id'];?>" 
																			 data-exercise-type="SHORT_ANSWER" 
																			 data-section-uid="<?php echo $question['test_section'];?>" 
																			 data-section-name="<?php echo $sectionNameArray[$question['test_section']];?>">
										<div class="question"><?php echo nl2br($shortAnswerQuesBody['question']);?></div>
										<div class="short_answer_answer_wrap">
											<a><?php echo $shortAnswerQuesBody['answer'];?></a>
										</div>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
												<a class="time" data-time="<?php echo $question['time']; ?>">答題時間：<?php echo $question['time']; ?></a>
												<div class="tags">
												<?php
												if(!empty($question['tags'])){
													$tags = explode("," ,$question['tags']);
													foreach($tags as $tag){ ?>
														<a><?php echo $tag;?></a>
											<?php }}?>
												</div>
											</div>
											<div class="for_section">
												<?php if($question['test_section'] == "0"){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
												<?php 	}
												 ?>
											</div>
										</div>
										<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span>
									</li>
								<?php } }else {?>
									<div class="noQuestion">
										<img src="../img/oops.png">
										<a>此題形沒有資料 :(</a>
									</div>
								<?php }?>
							</ul>
							<!-- ************* 單選題 ************* -->
							<ul id="single_choice" class="tab-content questionNum">
							<?php if(!empty($questionList['singleChoiceQues'])){
									foreach($questionList['singleChoiceQues'] as $i => $question){
										$singleChoiceQuesBody = $question['body'];
										$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];
									?>
									<li class="single_choice_wrap questionItem notSelect" data-exercise-id="<?php echo $question['_id'];?>" 
																				data-exercise-type="SINGLE_CHOICE"
																				data-section-uid="<?php echo $question['test_section'];?>" 
																				data-section-name="<?php echo $sectionNameArray[$question['test_section']];?>" >
										<div class="question"><?php echo nl2br($singleChoiceQuesBody['question']);?></div>
										<div class="single_choice_answer_wrap">
											<?php foreach($singleChoiceQuesOpt as $j => $options){
												if($options['is_answer'] == true){?>
													<a class="opt_true"><?php echo $options['content'];?></a>
											<?php }else{  ?>
													<a><?php echo $options['content'];?></a>
											<?php } }?>	
										</div>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
												<a class="time" data-time="<?php echo $question['time']; ?>">答題時間：<?php echo $question['time']; ?></a>
												<div class="tags">
													<?php
													if(!empty($question['tags'])){
														$tags = explode("," ,$question['tags']);
														foreach($tags as $tag){ ?>
															<a><?php echo $tag;?></a>
												<?php }}?>
												</div>
											</div>
											<div class="for_section">
												<?php if($question['test_section'] == "0"){ ?>
														<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
														<a class="is_test">適用章節：</a>
														<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
												<?php 	} 
												 ?>
											</div>
										</div>
										<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span>
									</li>
								<?php } }else {?>
									<div class="noQuestion">
										<img src="../img/oops.png">
										<a>此題形沒有資料 :(</a>
									</div>
								<?php }?>
							</ul>

							<!-- ************* 多選題 ************* -->
							<ul id="multi_choice" class="tab-content questionNum">
							<?php if(!empty($questionList['multiChoiceQues'])){
									foreach($questionList['multiChoiceQues'] as $i => $question){
										$multiChoiceQuesBody = $question['body'];
										$multiChoiceQuesOpt = $multiChoiceQuesBody['options'];
									?>
									<li class="multi_choice_wrap questionItem notSelect" data-exercise-id="<?php echo $question['_id'];?>" 
																			   data-exercise-type="MULTI_CHOICE"
																			   data-section-uid="<?php echo $question['test_section'];?>" 
																			   data-section-name="<?php echo $sectionNameArray[$question['test_section']];?>" >
										<div class="question"><?php echo nl2br($multiChoiceQuesBody['question']);?></div>
										<div class="multi_choice_answer_wrap">
											<?php foreach($multiChoiceQuesOpt as $j => $options){
												if($options['is_answer'] == true){?>
													<a class="opt_true"><?php echo $options['content'];?></a>
											<?php }else{  ?>
													<a><?php echo $options['content'];?></a>
											<?php } }?>	
										</div>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $question['level'];?>">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
												<a class="time" data-time="<?php echo $question['time']; ?>">答題時間：<?php echo $question['time']; ?></a>
												<div class="tags">
												<?php
												if(!empty($question['tags'])){
													$tags = explode("," ,$question['tags']);
													foreach($tags as $tag){ ?>
														<a><?php echo $tag;?></a>
											<?php }}?>
												</div>
											</div>
											<div class="for_section">
												<?php if($question['test_section'] == "0"){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
												<?php }?>
											</div>
										</div>
										<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span>
									</li>
								<?php } }else {?>
									<div class="noQuestion">
										<img src="../img/oops.png">
										<a>此題形沒有資料 :(</a>
									</div>
								<?php }?>
							</ul>

							<!-- ************* 題組 ************* -->
							<ul id="series_question" class="tab-content questionNum">
							<?php if(!empty($questionList['seriesQues'])){
									foreach($questionList['seriesQues'] as $i => $questionHeader){
										$seriesQuesBody = $questionHeader['body'];
							?>
									<li class="series_question_wrap questionItem notSelect" data-exercise-id="<?php echo $questionHeader['_id'];?>" 
																			      data-exercise-type="SERIES_QUESTIONS"
																			      data-section-uid="<?php echo $questionHeader['test_section'];?>" 
																			      data-section-name="<?php echo $sectionName;?>" >
										<div class="question"><?php echo nl2br($seriesQuesBody['description']);?><!-- <span class="questionType"> ( 題組 )</span> --></div>
										<ul class="seriesNum">		
											<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
													$questionOpt = $question['options']; ?>
											<li>	
												<div class="series_question"><?php echo nl2br($question['question']);?></div>
												<div class="series_question_answer_wrap">
													<?php foreach($questionOpt as $k => $options){
														if($options['is_answer'] == true){?>
															<a class="opt_true"><?php echo $options['content'];?></a>
													<?php }else{  ?>
															<a><?php echo $options['content'];?></a>
													<?php } }?>	
												</div>
											</li>
											<?php }?>
										</ul>
										<div class="question_editor_wrap">
											<div class="questionInfo">
												<a class="level" data-level="<?php echo $questionHeader['level'];?>">難易度：<?php for($i=1; $i<=$questionHeader['level']; $i++) echo '★';?></a>
												<a class="time" data-time="<?php echo $questionHeader['time']; ?>">答題時間：<?php echo $questionHeader['time']; ?></a>
												<div class="tags"><a>演算法</a><a>Binary Tree</a></div>
											</div>
											<div class="for_section">
												<?php if($questionHeader['is_test'] == false){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
												<?php } ?>
											</div>
										</div>
										<span class="deleteQuestionBtn"><i class="fa fa-times-circle"></i></span>
									</li>
								<?php } }else {?>
									<div class="noQuestion">
										<img src="../img/oops.png">
										<a>此題形沒有資料 :(</a>
									</div>
								<?php }?>
							</ul>
						</div>
						
					</div>
				</div>
				<!-- hidden parameter -->
				<input type="hidden" id="course_id" value="<?php echo $course_id;?>"/>
				<input type="hidden" id="course_name" value="<?php echo $course_name;?>"/>
				<input type="hidden" id="type" value="<?php echo $type;?>"/>
				<!-- <input type="hidden" id="time" value=""/> -->
				<input type="hidden" id="level" value=""/>
				<input type="hidden" id="start_date" value="<?php echo $start_date;?>"/>
				<input type="hidden" id="start_time" value="<?php echo $start_time;?>"/>
				<input type="hidden" id="end_date" value="<?php echo $end_date;?>"/>
				<input type="hidden" id="end_time" value="<?php echo $end_time;?>"/>
				<input type="hidden" id="explanation" value="<?php echo $explanation;?>"/>
			</div>
			<div class="examSetting_wrap" style="display:none;">
				<div class="addExerciseTitle">測驗設定</div>
				<div class="addExercise_wrap">
					<label class="label_style">測驗科目</label>
					<a class="fixedInfo"><?php echo $course_name;?></a><br>
					<label class="label_style">測驗類型</label>
					<?php 
						if($type=='test') $exam_Type = '小考';
						else if($type=='mid') $exam_Type = '期中考';
						else if($type=='final') $exam_Type = '期末考';
					?>
					<a class="fixedInfo" ><?php echo $exam_Type;?></a><br>
					<label class="label_style">測驗排程</label>
					<a class="fixedInfo"><?php echo $start_date .' '.$start_time;?></a>
					<a class="to"> ~ </a>
					<a class="fixedInfo"><?php echo $end_date .' '.$end_time;?></a><br>
					<label class="label_style">測驗總時間</label>
						<input class="test_time" value="20"><span class="test_timeMinWord">分</span><span class="reference_time_wrap">(建議高於<span class="reference_time">0</span>分)</span><br>
					<label class="label_style">簡答題模糊比對</label>
					<div class="fuzzy_match_wrap">
						<select name="fuzzy_match" class="fuzzy_match">
							<?php for($i=0; $i<=5; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
						</select>
					</div><br>
					<label class="label_style" for="course_name">複選題部分給分</label>
					<div class="multi_score_wrap">
						<input id="multi_score2" value="1" type="radio" name="multi_score" checked>
							<label for="multi_score2">啟用</label>
						<input id="multi_score1" value="0" type="radio" name="multi_score">
							<label for="multi_score1">不啟用</label>
					</div>
					<div class="resultBtn_wrap examSettingFunc">
						<div class="formCheck"></div>
						<a class="resultBtn closeBox">關 閉</a>
					</div>
				</div>

			</div>
			<div class="addExerciseBox_wrap" style="display:none;">
				<div class="addExerciseTitle">新增題目</div>
				<div class="add_userControl">
					<ul class="add_tab-list">
						<li><a href="#add_true_false">是非題</a></li>
						<li><a href="#add_short_answer">簡答</a></li>
						<li><a href="#add_single_choice">單選題</a></li>
						<li><a href="#add_multi_choice">複選題</a></li>
						<li><a href="#add_series_question">題組</a></li>
					</ul>
				</div>
				<div class="spinner_wrap" style="display:none;">
					<div class="spinner">
					  <div class="bounce1"></div>
					  <div class="bounce2"></div>
					  <div class="bounce3"></div>
					</div>
				</div>
				<div class="addExercise_wrap">
					<!-- 是非題 -->
					<form id="add_true_false" class="add_tab-content">
						<div class="question_content_wrap">
							<label for="question">題目</label>
								<textarea class="question_textarea" name="question"></textarea>
							<label for="TF_answer">本題解答</label>
							<div class="opt">
								<input id="true" value="true" type="radio" name="answer">
								<label for="true" name="TF_answer">Ｏ</label>
								<input id="false" value="false" type="radio" name="answer">
								<label for="false" name="TF_answer">Ｘ</label>
							</div>
							<div class="tags_wrap">
								<label for="tags">標籤</label>
								<input type="text" class="tagsInput" name="tags" value="">
							</div>
							<label for="level">難易度</label>
							<div class="opt">
								<?php for($i=1; $i<=5; $i++){ ?>								
									<input id="truefalse_level<?php echo $i;?>" value="<?php echo $i;?>" type="radio" name="level">
										<label for="truefalse_level<?php echo $i;?>" name="level"><?php for($j=1; $j<=$i; $j++){?>★<?php }?></label>
								<?php } ?>
							</div>
							<label for="time">答題時間</label>
								<select name="min" class="time_select min">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select sec">
									<?php for($i=0; $i<=50; $i+=10){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option> <?php }?>
								</select>
								<a class="time_char">秒</a>
							<label for="is_test">選為隨堂練習</label>
							<div class="opt">
								<input id="is_test_false" target="trueFalse" value="false" type="radio" name="is_test" checked>
								<label for="is_test_false" name="is_test">否</label>
								<input id="is_test_true" target="trueFalse" value="true" type="radio" name="is_test">
								<label for="is_test_true" name="is_test">是</label>
							</div>
							
							<div id="section_trueFalse" class="chapter_select">
								<label for="section">適用章節</label>
								<select class="testSection_select" name="section">
								<?php
								foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<optgroup label='<?php echo $courseName; ?>'>
								<?php foreach($chapter['sections'] as $j => $section){
										$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']); ?>
										<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } ?>
									</optgroup>
									
								<?php } ?>	
								</select>
							</div>
						</div>
						<div class="resultBtn_wrap tfSave">
							<div class="formCheck"></div>
							<a class="resultBtn save tfSave">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
						
					</form>
					<!-- 簡答題 -->
					<form id="add_short_answer" class="add_tab-content">
						<div class="question_content_wrap">
							<label for="question">題目</label>
								<textarea class="question_textarea" name="question"></textarea>
							<label for="short_answer">本題解答</label>
									<textarea class="short_answer" name="short_answer"></textarea>
							<div class="tags_wrap">
								<label for="tags">標籤</label>
								<input type="text" class="tagsInput" name="tags" value="">
							</div>
							<label for="level">難易度</label>
							<div class="opt">
								<?php for($i=1; $i<=5; $i++){ ?>								
									<input id="shortAnswer_level<?php echo $i;?>" value="<?php echo $i;?>" type="radio" name="level">
										<label for="shortAnswer_level<?php echo $i;?>" name="level"><?php for($j=1; $j<=$i; $j++){?>★<?php }?></label>
								<?php } ?>
							</div>
							<label for="time">答題時間</label>
								<select name="min" class="time_select min">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select sec">
									<?php for($i=0; $i<=50; $i+=10){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option> <?php }?>
								</select>
								<a class="time_char">秒</a>
							<label for="is_test">選為隨堂練習</label>
							<div class="opt">
								<input id="is_test_false_short" target="shortAnswer" value="false" type="radio" name="is_test" checked>
								<label for="is_test_false_short" name="is_test">否</label>
								<input id="is_test_true_short" target="shortAnswer" value="true" type="radio" name="is_test">
								<label for="is_test_true_short" name="is_test">是</label>
							</div>
							
							<div id="section_shortAnswer" class="chapter_select">
								<label for="section">適用章節</label>
								<select class="testSection_select" name="section">
								<?php
								foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<optgroup label='<?php echo $courseName; ?>'>
								<?php foreach($chapter['sections'] as $j => $section){
										$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']); ?>
										<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } ?>
									</optgroup>
									
								<?php } ?>	
								</select>
							</div>
						</div>
						<div class="resultBtn_wrap shortAnswer">
							<div class="formCheck"></div>
							<a class="resultBtn save shortAnswer">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
						
					</form>
					<!-- 單選題 -->
					<form id="add_single_choice" class="add_tab-content">
						<div class="question_content_wrap">
							<label for="question">題目</label>
								<textarea class="question_textarea" name="question"></textarea>
							<label for="single_opt_content">選項內容</label>
							<div class="opt_content">
								<?php for($i=1; $i<=4; $i++){ ?>
									<div><span>(<?php echo $i;?>)</span><textarea name="single_opt_content_<?php echo $i;?>"></textarea></div>
								<?php }?>
							</div>
							<label for="single_answer">本題解答</label>
							<div class="opt">
								<?php for($i=1; $i<=4; $i++){ ?>
								<input id="single_opt<?php echo $i;?>" value="<?php echo $i;?>" type="radio" name="answer">
								<label for="single_opt<?php echo $i;?>" name="single_answer">(<?php echo $i;?>)</label>
								<?php }?>
							</div>
							<label for="tags">標籤</label>
								<input class="tagsInput" name="tags">
							<label for="level">難易度</label>
							<div class="opt">
								<?php for($i=1; $i<=5; $i++){ ?>								
									<input id="single_level<?php echo $i;?>" value="<?php echo $i;?>" type="radio" name="level">
										<label for="single_level<?php echo $i;?>" name="level"><?php for($j=1; $j<=$i; $j++){?>★<?php }?></label>
								<?php } ?>							
							</div>
							<label for="time">答題時間</label>
								<select name="min" class="time_select min">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select sec">
									<?php for($i=0; $i<=50; $i+=10){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option> <?php }?>
								</select>
								<a class="time_char">秒</a>
							<label for="is_test">選為隨堂練習</label>
							<div class="opt">
								<input id="is_test_false_single" target="single" value="false" type="radio" name="is_test" checked>
								<label for="is_test_false_single" name="is_test">否</label>
								<input id="is_test_true_single" target="single" value="true" type="radio" name="is_test">
								<label for="is_test_true_single" name="is_test">是</label>
							</div>
							
							<div id="section_single"  class="chapter_select">
								<label for="section">適用章節</label>
								<select class="testSection_select" name="section">
								<?php
								foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<optgroup label='<?php echo $courseName; ?>'>
								<?php foreach($chapter['sections'] as $j => $section){
										$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']); ?>
										<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } ?>
									</optgroup>
								<?php } ?>	
								</select>
							</div>
							
						</div>
						<div class="resultBtn_wrap single">
				
					<div class="formCheck"></div>			<a class="resultBtn save single">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
					</form>
					<!-- 多選題 -->
					<form id="add_multi_choice" class="add_tab-content">
						<div class="question_content_wrap">
							<label for="question">題目</label>
								<textarea class="question_textarea" name="question"></textarea>
							<label for="single_opt_content">選項內容</label>
							<div class="opt_content">
								<?php for($i=1; $i<=5; $i++){ ?>
									<div><span>(<?php echo $i;?>)</span><textarea name="multi_opt_content_<?php echo $i;?>"></textarea></div>
								<?php } ?>
							</div>
							<label for="single_answer">本題解答</label>
							<div class="opt">
								<?php for($i=1; $i<=5; $i++){ ?>
									<input id="multi_opt<?php echo $i;?>" value="<?php echo $i;?>" type="checkbox" name="answer[]">
									<label for="multi_opt<?php echo $i;?>" name="single_answer">(<?php echo $i;?>)</label>
								<?php } ?>
							</div>
							<label for="tags">標籤</label>
								<input class="tagsInput" name="tags">
							<label for="level">難易度</label>
							<div class="opt">
								<?php for($i=1; $i<=5; $i++){ ?>								
									<input id="multi_level<?php echo $i;?>" value="<?php echo $i;?>" type="radio" name="level">
										<label for="multi_level<?php echo $i;?>" name="level"><?php for($j=1; $j<=$i; $j++){?>★<?php }?></label>
								<?php } ?>
							</div>
							<label for="time">答題時間</label>
								<select name="min" class="time_select min">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select sec">
									<?php for($i=0; $i<=50; $i+=10){ ?><option value="<?php echo $i;?>"><?php echo $i;?></option> <?php }?>
								</select>
								<a class="time_char">秒</a>
							<label for="is_test">選為隨堂練習</label>
							<div class="opt">
								<input id="is_test_false_multi" target="multi" value="false" type="radio" name="is_test" checked>
								<label for="is_test_false_multi" name="is_test">否</label>
								<input id="is_test_true_multi" target="multi" value="true" type="radio" name="is_test">
								<label for="is_test_true_multi" name="is_test">是</label>
							</div>
							<div id="section_multi"  class="chapter_select">
								<label for="section">適用章節</label>
								<select class="testSection_select" name="section">
								<?php
								foreach($contentData['chapters'] as $i => $chapter){
									$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
									<optgroup label='<?php echo $courseName; ?>'>
								<?php foreach($chapter['sections'] as $j => $section){
										$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']); ?>
										<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } ?>
									</optgroup>
								<?php } ?>	
								</select>
							</div>
							
						</div>
						<div class="resultBtn_wrap multi">
					
						<div class="formCheck"></div>		<a class="resultBtn save multi">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
					</form>
					<!-- 題組 -->
					<form id="add_series_question" class="add_tab-content">
				
						<div class="resultBtn_wrap">
							<a c
								<div class="formCheck"></div>lass="resultBtn save">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
					</form>
					<input id="author_id" type="hidden" value="<?php echo $author_id;?>">
					<?php
						// $sectionNameArray_Iterator = new ArrayIterator($sectionNameArray);
						// $courseURLArray_Iterator = new ArrayIterator($courseURLArray);
						// $combine = new MultipleIterator;
						// $combine->attachIterator($sectionNameArray_Iterator);
						// $combine->attachIterator($courseURLArray_Iterator);
						// foreach($combine as $key => $Item){ ?>
						<!-- <input id="key<?php echo $key[0];?>" type="hidden" data-section-name="<?php echo $Item[0];?>" data-course-url="<?php echo $Item[1];?>"> -->
					<?php 
							// }
					 ?>
				</div>
			</div>
			<div class="overlay"> </div>
			<div class="statusSilde" style="display:none;"></div>
			<div class="statusSildeSave" style="display:none;">
				<div class="spinnerSaveExam">
				  <div class="bounce1"></div>
				  <div class="bounce2"></div>
				  <div class="bounce3"></div>
				</div>
			</div>

		</div>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>		
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		        $(".tagsInput").tagit(); 
		</script>
		<?php require("../js/js_com.php"); ?>
		<script src="../js/lib/moment.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
			window._questionList = <?php echo json_encode($questionList); ?>;
		</script>
		<script type="text/javascript" src="../js/editExam_switch.js"></script>
		<script type="text/javascript" src="../js/jquery.clearsearch.js"></script>
		<script type="text/javascript" src="../js/editExam.js"></script>
		<script type="text/javascript" src="../js/editExam_add.js"></script>
		<script>
			 
		    // $( ".questionItem" ).draggable({
		    //   appendTo: "body",
		    //   helper:"original",
		    //   revert: true,
		    //   connectWith: "#drop-question-list",
		    //   dropOnEmpty: true,

		    // });

		    // $( "#drop-question-list" ).droppable({
		    //   activeClass: "ui-state-default",
		    //   hoverClass: "ui-state-hover",
		    //   accept: ":not(.ui-sortable-helper)",
		    //   drop: function( event, ui ) {
		    //     // $( this ).find( ".placeholder" ).remove();
		    //     $( "<li class=\"questionItem\"></li>" ).html( ui.draggable.html() ).appendTo( $('#drop-question-list') );
		    //   }
		    // }).sortable({
		    //   items: "li:not(.placeholder)",
		    //   sort: function() {
		    //     // gets added unintentionally by droppable interacting with sortable
		    //     // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
		    //     $( this ).removeClass( "ui-state-default" );
		    //   }
		    // });
		  
		</script>
	</body>
</html>

<?php
	// {$unwind : "$content.chapters"},
    // {$unwind : "$content.chapters.sections"},
    // {$match : {'content.chapters.sections.uid':'346181432565383941'}},
    // {$project : {_id : 0,
    //     section : "$content.chapters.sections"
    //     }})
		
	// $mongoQuery = array(
	// 		'content.chapters.sections.uid' => '346181432565383941'
	// 	);
	
	/* 用unwind方式來提取document裡面其中的資料  */
	// $unwind_chapters = array('$unwind' => '$content.chapters');
	// $unwind_sections = array('$unwind' => '$content.chapters.sections');
	// $match = array(
	// 			'$match' => array(
	// 				'content.chapters.sections.uid' => '346181432565383941')
	// 		);
	// $project = array(
	// 				'$project' => array(
	// 					'_id' => 0,
	// 					'section' => '$content.chapters.sections'
	// 				)
	// 			);
	// $mon = $collection -> aggregate($unwind_chapters,$unwind_sections,$match,$project);
	// foreach($mon as $value){
	// 	echo $value[0]['section']['name'];
	// }
?>