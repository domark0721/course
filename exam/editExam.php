<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$course_id = $_GET['course_id'];

	// get POST data from addExam page (exam name....)
	// $generate_type = $_POST['generate_type'];
	$generate_type = 'man';

	// Get course meta data
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$row = mysql_fetch_assoc($result);
	$courseMetadata = $row;

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

	if ($generate_type == 'man') {
	// if is 手動
		$questionList['trueFalseQues'] = $trueFalseQues;
		$questionList['singleChoiceQues'] = $singleChoiceQues;
		$questionList['multiChoiceQues'] = $multiChoiceQues;
		$questionList['seriesQues'] = $seriesQues;

	}
	else ($generate_type == 'auto') {
	// if is 自動

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
		<title>題庫 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_exam.php"); ?>
<!-- 			<div id="topBanner_wrap">
				<div class="content-wrap clearfix">
					<div class="editorBarIcon"><i class="fa fa-leanpub"></i></div>
					<div class="courseHeader">
						<div class="topBanner_Title">題庫</div>
						<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
					</div>
						<a class="functionBtn newQuestion" href="addExercise.php?course_id=<?php echo $course_id; ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;新增題目</a>
						<a class="functionBtn newExam" href="addExam.php?course_id=<?php echo $course_id; ?>"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;&nbsp;新增考試</a>
						<a class="functionBtn return" onclick="history.back();">返回</a>
				</div>
			</div> -->
			<div class="editExamWrap">
				<div class="editExamContainer">
					<div class="left-container">			
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
						<!-- 
							foreach $questionList['trueFalseQues'] 
						-->
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
					<div class="right-container">
						<ol id="drop-question-list">
						<!-- 
							foreach $examList 
						-->
						</ol>
					</div>
				</div>
			</div>
		</div>

		<?php require("../js/js_com.php"); ?>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="../js/addExercise.js"></script>
		<script type="text/javascript" src="../js/addExercise.js"></script>
		<script>
		$(function() {
			$('#single_choice, #true_false, #multi_choice, #series_question, #drop-question-list').sortable({
				connectWith: '#drop-question-list',
				dropOnEmpty: true,
				helper: "clone",
			}).disableSelection();
		    // $( "#catalog" ).accordion();
		    // $( ".questionItem" ).draggable({
		    //   appendTo: "body",
		    //   helper: "clone",
		    //   connectWith: "#drop-question-list",
		    //   dropOnEmpty: true
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
		  });
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