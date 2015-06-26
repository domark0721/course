<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];

	

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
		$trueFalse = $_POST['trueFalse'];
		$singleChoice = $_POST['singleChoice'];
		$multiChoice = $_POST['multiChoice'];
		$seriesQues = $_POST['seriesQues'];
	}

	$generate_type = 'manualMode';

	// Get course meta data
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	// get all questions of the course from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $exercise -> find($mongoQuery);

	// group questions by type
	$trueFalseQues = array();
	$singleChoiceQues = array();
	$multiChoiceQues = array();
	$seriesQues = array();

	foreach($mon as $data){
		if($data['type'] == "TRUE_FALSE"){
			$trueFalseQues[] = $data;
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

	if ($generate_type == 'manualMode') {
		$questionList['trueFalseQues'] = $trueFalseQues;
		$questionList['singleChoiceQues'] = $singleChoiceQues;
		$questionList['multiChoiceQues'] = $multiChoiceQues;
		$questionList['seriesQues'] = $seriesQues;

	}else if($generate_type == 'autoMode') {

		$examList = $seriesQues;
		// start auto generate exam

		// randomly(Algorithmly) pick questions form grouped question to examList

		// e.g. : $trueFalseQues ---- take 3 questions ----> $examlist

	}

	// get course chapter content from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	$contentData = $courseData['content'];
?>
<!doctype html>
<html>
	<head>
		<?php require("exam_meta.php") ?>
		<!-- <link type="text/css" rel="stylesheet" href="../css/exercise.css"> -->
		<link type="text/css" rel="stylesheet" href="../css/editExam.css">
		<title>考卷編輯系統 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_exam.php"); ?>
			<div class="exam_status_wrap">
				<div class="examInfo">
				<a>平均難度：<span id="exam_level">0 / 5</span></a>
				<a>總時間：<span id="exam_time">0分0秒</span></a><br>
				</div>
				<div class="numberInfo">
					<a>總題數：<span id="total_num">0</span></a><a>是非：<span id="trueFalse_num">0</span></a>
					<a>單選：<span id="single_num">0</span></a><a>多選：<span id="multi_num">0</span></a>
					<a>題組：<span id="series_num">0</span></a>
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
												<?php if($question['is_test'] == false){ ?>
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
												<?php if($question['is_test'] == false){ ?>
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
													<?php if($question['is_test'] == false){ ?>
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
											<li><a href="#single_choice">單選題</a></li>
											<li><a href="#multi_choice">多選題</a></li>
											<li><a href="#series_question">題組</a></li>
										</ul>
									</div>
							</div>
							<!-- ************* 是非題 ************* -->
							<ul id="true_false" class="tab-content questionNum">
							<?php if(!empty($questionList['trueFalseQues'])){
									foreach($questionList['trueFalseQues'] as $i => $question){
										$trueFalseQuesBody = $question['body'];
										// for sectionName
										$sectionName = "";
										if(!($question['is_test'] == false)){
											foreach($contentData['chapters'] as $i => $chapter){
												foreach($chapter['sections'] as $j => $section){
													$sectionName_temp = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
													$courseURL = sprintf("../courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																			,$courseData['course_id'], $i, $j);
														if($section['uid'] == $question['test_section']){
															$sectionName = $sectionName_temp;
															break;
														}
													}
												}
											}
										?>
									<li class="true_false_wrap questionItem" data-exercise-id="<?php echo $question['_id'];?>" 
																			 data-exercise-type="TRUE_FALSE" 
																			 data-section-uid="<?php echo $question['test_section'];?>" 
																			 data-section-name="<?php echo $sectionName;?>">
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
												<?php if($question['is_test'] == false){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php 	}
												 ?>
											</div>
										</div>
									</li data-section-name="<?php echo $sectionName;?>">
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
										// for sectionName
										$sectionName = "";
										if(!($question['is_test'] == false)){
											foreach($contentData['chapters'] as $i => $chapter){
												foreach($chapter['sections'] as $j => $section){
													$sectionName_temp = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
													$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																			,$courseData['course_id'], $i, $j);
													if($section['uid'] == $question['test_section']){
														$sectionName = $sectionName_temp;
														break;
													}
												}
											}
										}
									?>
									<li class="single_choice_wrap questionItem" data-exercise-id="<?php echo $question['_id'];?>" 
																				data-exercise-type="SINGLE_CHOICE"
																				data-section-uid="<?php echo $question['test_section'];?>" 
																				data-section-name="<?php echo $sectionName;?>" >
										<div class="question"><?php echo $singleChoiceQuesBody['question'];?></div>
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
												<?php if($question['is_test'] == false){ ?>
														<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
														<a class="is_test">適用章節：</a>
														<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php 	} 
												 ?>
											</div>
										</div>
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
										// for sectionName
										$sectionName = "";
										if(!($question['is_test'] == false)){
											foreach($contentData['chapters'] as $i => $chapter){
												foreach($chapter['sections'] as $j => $section){
													$sectionName_temp = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
													$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																			,$courseData['course_id'], $i, $j);
													if($section['uid'] == $question['test_section']){
														$sectionName = $sectionName_temp;
														break;
													}
												}
											}
										}
									?>
									<li class="multi_choice_wrap questionItem" data-exercise-id="<?php echo $question['_id'];?>" 
																			   data-exercise-type="MULTI_CHOICE"
																			   data-section-uid="<?php echo $question['test_section'];?>" 
																			   data-section-name="<?php echo $sectionName;?>" >
										<div class="question"><?php echo $multiChoiceQuesBody['question'];?></div>
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
												<?php if($question['is_test'] == false){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php }?>
											</div>
										</div>
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
										// for sectionName
										if(!($questionHeader['is_test'] == false)){ 
											foreach($contentData['chapters'] as $i => $chapter){
												foreach($chapter['sections'] as $j => $section){
													$sectionName_temp = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
													$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
																			,$courseData['course_id'], $i, $j);
													if($section['uid'] == $question['test_section']){
														$sectionName = $sectionName_temp;
														break;
													}
												}
											}
										}
									?>
									<li class="series_question_wrap questionItem" data-exercise-id="<?php echo $questionHeader['_id'];?>" 
																			      data-exercise-type="SERIES_QUESTIONS"
																			      data-section-uid="<?php echo $questionHeader['test_section'];?>" 
																			      data-section-name="<?php echo $sectionName;?>" >
										<div class="question"><?php echo $seriesQuesBody['description'];?><!-- <span class="questionType"> ( 題組 )</span> --></div>
										<ul class="seriesNum">		
											<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
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
												<a class="time" data-time="<?php echo $questionHeader['time']; ?>">答題時間：<?php echo $questionHeader['time']; ?></a>
												<div class="tags"><a>演算法</a><a>Binary Tree</a></div>
											</div>
											<div class="for_section">
												<?php if($questionHeader['is_test'] == false){ ?>
													<a class="is_test">適用章節： <span>未指定</span></a>
												<?php } else{ ?>
													<a class="is_test">適用章節：</a>
													<a class="is_test_href" target="_blank" href="<?php echo $courseURL;?>"><?php echo $sectionName;?></a>
												<?php } ?>
											</div>
										</div>
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
				<input type="hidden" id="time" value=""/>
				<input type="hidden" id="level" value=""/>
				<input type="hidden" id="start_date" value="<?php echo $start_date;?>"/>
				<input type="hidden" id="start_time" value="<?php echo $start_time;?>"/>
				<input type="hidden" id="end_date" value="<?php echo $end_date;?>"/>
				<input type="hidden" id="end_time" value="<?php echo $end_time;?>"/>
				<input type="hidden" id="explanation" value="<?php echo $explanation;?>"/>
			</div>
			
		</div>

		<?php require("../js/js_com.php"); ?>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
			window._questionList = <?php echo json_encode($questionList); ?>;
		</script>
		<script type="text/javascript" src="../js/addExercise.js"></script>
		<script type="text/javascript" src="../js/jquery.clearsearch.js"></script>
		<script type="text/javascript" src="../js/editExam.js"></script>
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