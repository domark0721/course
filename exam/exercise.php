<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$course_id = $_GET['course_id'];

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $tempData){
		$courseMetadata = $tempData;
		break;
	}

	//search question from mongodb in exercise collection
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $exercise -> find($mongoQuery);
	

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

	//course data from mongo
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
		<?php require("../meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="../css/mode.css">
		<link type="text/css" rel="stylesheet" href="../css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="../css/exercise.css">
		<title>題庫 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("../header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-leanpub"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">題庫列表</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="function_wrap">
					<ul class="function_ul">
						<li><a class="functionBtn blueFunc" href="addExercise.php?course_id=<?php echo $course_id; ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;新增題目</a></li>
						<li><a class="functionBtn redFunc" href="../temode.php"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp;&nbsp;返回</a></li>
					</ul>
				</div>		
				<div id="exerciseList" class="questionsDisplay-wrap">
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
					<?php if(!empty($trueFalseQues)){
							foreach($trueFalseQues as $i => $question){
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
											<a class="level">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
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
											<?php } else{ ?>
												<a class="is_test">適用章節：</a>
												<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
											<?php }?>
										</div>
										<div class="questionFunc">
											<a class="editQuesBtn" href="editExercise.php?id=<?php echo $question['_id'];?>&course_id=<?php echo $course_id;?>">編輯</a>
											<a class="deletQuesBtn" data-exercise-id="<?php echo $question['_id'];?>">刪除</a>
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
					
					<!-- ************* 簡答題 ************* -->
					<ul id="short_answer" class="tab-content questionNum">
					<?php if(!empty($shortAnswerQues)){
							foreach($shortAnswerQues as $i => $question){
								$shortAnswerQuesBody = $question['body'];?>
							<li class="short_answer_wrap questionItem">
									<div class="question"><?php echo $shortAnswerQuesBody['question'];?></div>
									<div class="short_answer_answer_wrap">
										<a><?php echo $shortAnswerQuesBody['answer'];?></a>
									</div>
									<div class="question_editor_wrap">
										<div class="questionInfo">
											<a class="level">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
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
											<?php } else{ ?>
												<a class="is_test">適用章節：</a>
												<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
											<?php }?>
										</div>
										<div class="questionFunc">
											<a class="editQuesBtn" href="editExercise.php?id=<?php echo $question['_id'];?>&course_id=<?php echo $course_id;?>">編輯</a>
											<a class="deletQuesBtn" data-exercise-id="<?php echo $question['_id'];?>">刪除</a>
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
					<!-- ************* 單選題 ************* -->
					<ul id="single_choice" class="tab-content questionNum">
					<?php if(!empty($singleChoiceQues)){
							foreach($singleChoiceQues as $i => $question){
									$singleChoiceQuesBody = $question['body'];
									$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];?>
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
										<a class="level">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
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
											<?php } else{ ?>
												<a class="is_test">適用章節：</a>
												<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
											<?php }?>
									</div>
									<div class="questionFunc">
										<a class="editQuesBtn" href="editExercise.php?id=<?php echo $question['_id'];?>&course_id=<?php echo $course_id;?>">編輯</a>
										<a class="deletQuesBtn" data-exercise-id="<?php echo $question['_id'];?>">刪除</a>
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
					<?php if(!empty($multiChoiceQues)){
							foreach($multiChoiceQues as $i => $question){
							$multiChoiceQuesBody = $question['body'];
							$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
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
											<a class="level">難易度：<?php for($i=1; $i<=$question['level']; $i++) echo '★';?></a>
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
											<?php } else{ ?>
												<a class="is_test">適用章節：</a>
												<a class="is_test_href" target="_blank" href="<?php echo $courseURLArray[$question['test_section']];?>"><?php echo $sectionNameArray[$question['test_section']];?></a>
											<?php }?>
										</div>
										<div class="questionFunc">
											<a class="editQuesBtn" href="editExercise.php?id=<?php echo $question['_id'];?>&course_id=<?php echo $course_id;?>">編輯</a>
											<a class="deletQuesBtn"  data-exercise-id="<?php echo $question['_id'];?>">刪除</a>
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
						<?php if(!empty($seriesQues)){
								foreach($seriesQues as $i => $questionHeader){
								$seriesQuesBody = $questionHeader['body'];?>
								<li class="series_question_wrap questionItem">
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
											<a class="level">難易度：<?php for($i=1; $i<=$questionHeader['level']; $i++) echo '★';?></a>
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
										<div class="questionFunc">
											<a class="editQuesBtn" href="editExercise.php?id=<?php echo $question['_id'];?>&course_id=<?php echo $course_id;?>">編輯</a>
											<a class="deletQuesBtn" data-excercise-id="<?php echo $question['_id'];?>">刪除</a>
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
			<div class="statusSilde"></div>
			<?php require("../footer.php") ?>
		</div>
		
		<?php require("../js/js_com.php"); ?>
		<script type="text/javascript" src="../js/addExercise.js"></script>
		<script type="text/javascript" src="../js/Exercise.js"></script>
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