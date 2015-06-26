<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 

	$course_id = $_GET['course_id'];
	$chapter = $_GET['chapter_id'];
	$section = $_GET['section_id'];
	
	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $courseMetadata){

	}

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	$sectionData = $courseData['content']['chapters'];
	
	$goalChapter = $sectionData[$chapter]['sections'][$section];
	// var_dump($courseMetadata['course_name']);
	// exit;

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSections.css">
		<link type="text/css" rel="stylesheet" href="css/exam.css">
		<title><?php echo $courseMetadata['course_name'] . " " . ($chapter+1) . "-" . ($section+1); ?> - NUCourse</title>
	</head>

	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="videoBar">
					<div class="content-wrap">
						<div id="courseChapterBar">
							<div id="leftChapter">
							<?php if($chapter == 0 && $section == 0){ ?>
									<a href="course.php?course_id=<?php echo $course_id; ?>#courseSchedule"><i class="fa fa-arrow-left"></i> 回課程目錄</a>
							<?php } else if($chapter > 0 && $section == 0) {
									$preChapter_LastSection = count($sectionData[($chapter-1)]['sections']);
									$preChapter_LastSection_Name = $sectionData[($chapter-1)]['sections'][($preChapter_LastSection-1)]['name'];
									$preFullName = sprintf("%d-%d %s", $chapter, $preChapter_LastSection , $preChapter_LastSection_Name);?>
									<a href="courseSections.php?course_id=<?php $course_id; ?>&chapter_id=<?php echo ($chapter-1); ?>&section_id=<?php echo ($preChapter_LastSection-1);?>"><i class="fa fa-arrow-left"></i> <?php echo $preFullName;?></a>
							<?php }else if($chapter >= 0 && $section > 0){
									$preSectionName = $sectionData[$chapter]['sections'][($section-1)]['name'];
									$preFullName = sprintf("%d-%d %s", ($chapter+1), $section ,$preSectionName); ?>
									<a href="courseSections.php?course_id=<?php echo $course_id;?>&chapter_id=<?php echo $chapter;?>&section_id=<?php echo ($section-1);?>"><i class="fa fa-arrow-left"></i> <?php echo $preFullName;?></a>
							<?php } ?>
							</div>
							<div id="rightChapter">
							<?php
								$lastChpater = count($sectionData);
								$lastChpater_lastSection = count($sectionData[($lastChpater-1)]['sections']);
								$currentMaxSecsstion = count($sectionData[$chapter]['sections'])-1; //have been changed to index number

								if($chapter == ($lastChpater-1) && $section == ($lastChpater_lastSection-1)){ ?>
									<a href="course.php?course_id=<?php echo $course_id;?>#courseSchedule">回課程目錄 <i class="fa fa-arrow-right"></i></a>
							<?php	}
								else if($section < $currentMaxSecsstion){
									$nextSectionName = $sectionData[$chapter]['sections'][($section+1)]['name'];
									$nextFullName = sprintf("%d-%d %s", ($chapter+1), ($section+2), $nextSectionName);?>
									<a href="courseSections.php?course_id=<?php echo $course_id;?>&chapter_id=<?php echo $chapter;?>&section_id=<?php echo ($section+1);?>"><?php echo $nextFullName;?> <i class="fa fa-arrow-right"></i></a>
							<?php	}else if($section == $currentMaxSecsstion){
									$nextSectionName = $sectionData[($chapter+1)]['sections'][0]['name'];
									$nextFullName = sprintf("%d-1 %s", ($chapter+2) , $nextSectionName);?>
									<a href="courseSections.php?course_id=<?php echo $course_id;?>&chapter_id=<?php echo ($chapter+1);?>&section_id=0"><?php echo $nextFullName;?> <i class="fa fa-arrow-right"></i></a>
							<?php } ?>
							</div>
							<div id="thisChapter"><?php echo ($chapter+1) . "-" . ($section+1) . " " . $goalChapter['name']; ?></div>
						</div>
						<div id="video">
							<video controls >
							  <source src="videos/small.mp4" type="video/mp4">
							  Your browser does not support the video tag.
							</video>
						</div>
					</div>
				</div>
				<div id="courseTab-wrap" class="display-wrap">
					<div class="userControl">
						<ul class="tab-list">
							<li><a href="#courseContent">文字教材</a></li>
							<li><a href="#courseExercise">隨堂練習</a></li>
							<li><a href="#disqus">互動與討論</a></li>
						</ul>
					</div>
				</div>
				<!-- 文字教材 -->
				<div id="courseContent" class="tab-content display-wrap">
					<div class="Content"><?php echo $goalChapter['content']; ?></div>
				</div>

				<!-- 隨堂練習 -->
				<div id="courseExercise" class="tab-content display-wrap">
					<div class="exerciseList_wrap">
					<?php
						//exercise from mongo
						$exerciseQuery = array('$and' => array(
													array('course_id' => (int)$course_id),
													array('is_test' => true),
													array('test_section' => $goalChapter['uid'])
													)
												);
						$mon = $exercise -> find($exerciseQuery);

						$trueFalseQues = array(); $singleChoiceQues = array();
						$multiChoiceQues = array(); $seriesQues = array();

						foreach($mon as $exercise){
							// var_dump($exercise);
							if($exercise['type'] == "TRUE_FALSE"){
								$trueFalseQues[] = $exercise;
							}else if($exercise['type'] == "SINGLE_CHOICE"){
								$singleChoiceQues[] = $exercise;
							}else if($exercise['type'] == "MULTI_CHOICE"){
								$multiChoiceQues[] = $exercise;
							}else if($exercise['type'] == "SERIES_QUESTIONS"){
								$seriesQues[] = $exercise;
							}
						}?>
						<!-- 是非 -->
						<ul class="typeNum">
						<?php if(!empty($trueFalseQues)){ ?>
							<li> 
								<div class="typeName">是非題</div>
						<?php } ?>
								<ul class="questionNum">
									<?php foreach($trueFalseQues as $i => $question){
											$trueFalseQuesBody = $question['body'];?>
										<li class="true_false_wrap">
											<div class="question"><?php echo $trueFalseQuesBody['question'];?></div>
											<div class="true_false_answer_wrap">
												<input id="answer_true<?php echo $i;?>" type="radio" name="answer" value="true">
												<label for="answer_true<?php echo $i;?>">Ｏ</label>
												<input id="answer_false<?php echo $i;?>" type="radio" name="answer" value="false">
												<label for="answer_false<?php echo $i;?>">Ｘ</label>
											</div>
										</li>
									<?php } ?>
								</ul>
						<?php if(!empty($trueFalseQues)){ ?>
							</li>
						<?php } ?>

						<!-- 單選 -->
						<?php if(!empty($singleChoiceQues)){ ?>
							<li> 
								<div class="typeName">單選題</div>
						<?php } ?>
								<ul class="questionNum">
									<?php foreach($singleChoiceQues as $i => $question){
											$singleChoiceQuesBody = $question['body'];
											$singleChoiceQuesOpt = $singleChoiceQuesBody['options'];?>
									<li class="single_choice_wrap">
										<div class="question"><?php echo $singleChoiceQuesBody['question'];?></div>
										<div class="single_choice_answer_wrap">
											<?php foreach($singleChoiceQuesOpt as $j => $options){?>
											<input id="single_answer<?php echo $i ."_". $j;?>" type="radio" name="single_opt<?php echo $i;?>" value="<?php echo $j;?>">
											<label for="single_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
											<?php }	?>
										</div>
									</li>
									<?php }?>
								</ul>
						<?php if(!empty($singleChoiceQues)){ ?>
							</li>
						<?php } ?>
						<!-- 多選 -->
						<?php if(!empty($multiChoiceQues)){ ?>
							<li>
								<div class="typeName">複選題</div>
						<?php } ?>
								<ul class="questionNum">
									<?php foreach($multiChoiceQues as $i => $question){
											$multiChoiceQuesBody = $question['body'];
											$multiChoiceQuesOpt = $multiChoiceQuesBody['options']?>
									<li class="multi_choice_wrap">
										<div class="question"><?php echo $multiChoiceQuesBody['question'];?></div>
										<div class="multi_choice_answer_wrap">
												<?php foreach($multiChoiceQuesOpt as $j => $options){?>
												<input id="multi_answer<?php echo $i ."_". $j;?>" type="checkbox" name="multi_opt<?php echo $i;?>" value="<?php echo $j;?>">
												<label for="multi_answer<?php echo $i ."_". $j;?>"><?php echo $options['content'];?></label>
												<?php }?>
										</div>
									</li>
									<?php }?>
								</ul>
						<?php if(!empty($multiChoiceQues)){ ?>
							</li>
						<?php } ?>
						<!-- 題組 -->
						<?php if(!empty($seriesQues)){ ?>
							<li>
								<div class="typeName">題組</div>
						<?php } ?>
								<ul class="questionNum">
									<?php foreach($seriesQues as $i => $questionHeader){
											$seriesQuesBody = $questionHeader['body'];?>
									<li class="series_question_wrap">
										<div class="question"><?php echo $seriesQuesBody['description'];?></div>
										<ul class="seriesNum">		
												<?php foreach($seriesQuesBody['questions'] as $j => $question){ 
														$questionOpt = $question['options']; ?>
											<li>	
												<div class="series_question"><?php echo $question['question'];?></div>
												<div class="series_question_answer_wrap">
													<?php foreach($questionOpt as $k => $options){?>
													<input id="series_question<?php echo $i ."_". $j ."_". $k;?>" type="radio" name="series_opt<?php echo $j;?>" value="<?php echo $k;?>">
													<label for="series_question<?php echo $i ."_". $j ."_". $k;?>"><?php echo $options['content'];?></label>
													<?php }?>
												</div>
											</li>
											
									<?php }?>
											</ul>
									</li>
												<?php }?>
								</ul>
						<?php if(!empty($seriesQues)){ ?>
							</li>
						<?php } ?>

						<div class="finish_test_wrap">
							<div class="finishBtns"><a>檢視所有答案</a></div>
						</div>	
					</div>
				</div>

				<!-- disqus -->
				<div id="disqus" class="tab-content disqus-display-wrap">
					<div id="disqus_thread"></div>
				</div>
			</div>
			<div class="back_to_schedule_wrap">
				<div class="back_to_schedule"><a href="course.php?course_id=<?php echo $course_id;?>#courseSchedule"><i class="fa fa-arrow-left"></i> 回課程目錄</a></div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		
		<script type="text/javascript">
		/* * * CONFIGURATION VARIABLES * * */
		var disqus_shortname = 'nucourse';

		/* * * DON'T EDIT BELOW THIS LINE * * */
		(function() {
		    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
		    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
		<?php require("js/js_com.php"); ?>
		<script src="js/switch.js"></script>
		<script src="js/backToSchedule.js"></script>
	</body>
</html>