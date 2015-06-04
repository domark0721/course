<?php
	include_once('api/auth.php');
	include_once("mysql.php");
	include_once("mongodb.php");
	include_once('api/isLogin.php');
	 date_default_timezone_set("Asia/Taipei");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$member_id = $_SESSION['member_id'];
	$course_id = $_GET['course_id'];
	// $course_id = 123;

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

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}

	$contentData = $courseData['content'];
	// var_dump($contentData);
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css">
		<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="css/addExercise.css">
		<title>新增題庫 - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-question-circle"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">新增題庫</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>

				<div class="editSetting-wrap">
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

					<div class="addExercise_wrap">
						<!-- 是非題 -->
						<form id="true_false" class="tab-content" action="api/addExercise_save.php" method="POST">
							<div class="question_content_wrap">
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"></textarea>
								<label for="TF_answer">本題解答</label>
								<div class="level_wrap">
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
								<div class="level_wrap">									
									<input id="truefalse_level1" value="1" type="radio" name="level">
										<label for="truefalse_level1" name="level">★</label>
									<input id="truefalse_level2" value="2" type="radio" name="level">
										<label for="truefalse_level2" name="level">★★</label>
									<input id="truefalse_level3" value="3" type="radio" name="level">
										<label for="truefalse_level3" name="level">★★★</label>
									<input id="truefalse_level4" value="4" type="radio" name="level">
										<label for="truefalse_level4" name="level">★★★★</label>
									<input id="truefalse_level5" value="5" type="radio" name="level">
										<label for="truefalse_level5" name="level">★★★★★</label>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
										<option value="1">0</option><option value="1">1</option><option value="2">2</option>
										<option value="3">3</option><option value="4">4</option><option value="5">5</option>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
										<option value="0">0</option><option value="10">10</option><option value="20">20</option>
										<option value="30">30</option><option value="40">40</option><option value="50">50</option>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="level_wrap">
									<input id="is_test_false" target="trueFalse" value="false" type="radio" name="is_test" checked>
									<label for="is_test_false" name="is_test">否</label>
									<input id="is_test_true" target="trueFalse" value="true" type="radio" name="is_test">
									<label for="is_test_true" name="is_test">是</label>
								</div>
								
								<div id="section_trueFalse" class="showSection">
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

								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="create_date" value="<?php echo date('Y-m-d H:i:s');?>">
								<input type="hidden" name="type" value="TRUE_FALSE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<!-- 單選題 -->
						<form id="single_choice" class="tab-content" action="api/addExercise_save.php" method="POST">
							<div class="question_content_wrap">
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"></textarea>
								<label for="single_opt_content">選項內容</label>
								<div class="opt_content">
									<div><span>(1)</span><textarea name="single_opt_content_1"></textarea></div>
									<div><span>(2)</span><textarea name="single_opt_content_2"></textarea></div>
									<div><span>(3)</span><textarea name="single_opt_content_3"></textarea></div>
									<div><span>(4)</span><textarea name="single_opt_content_4"></textarea></div>
								</div>
								<label for="single_answer">本題解答</label>
								<div class="level_wrap">
									<input id="single_opt1" value="1" type="radio" name="answer">
									<label for="single_opt1" name="single_answer">(1)</label>
									<input id="single_opt2" value="2" type="radio" name="answer">
									<label for="single_opt2" name="single_answer">(2)</label>
									<input id="single_opt3" value="3" type="radio" name="answer">
									<label for="single_opt3" name="single_answer">(3)</label>
									<input id="single_opt4" value="4" type="radio" name="answer">
									<label for="single_opt4" name="single_answer">(4)</label>
								</div>
								<label for="tags">標籤</label>
									<input class="tagsInput" name="tags">
								<label for="level">難易度</label>
								<div class="level_wrap">									
									<input id="single_level1" value="1" type="radio" name="level">
										<label for="single_level1"  name="level">★</label>
									<input id="single_level2" value="2" type="radio" name="level">
										<label for="single_level2" name="level">★★</label>
									<input id="single_level3" value="3" type="radio" name="level">
										<label for="single_level3" name="level">★★★</label>
									<input id="single_level4" value="4" type="radio" name="level">
										<label for="single_level4" name="level">★★★★</label>
									<input id="single_level5" value="5" type="radio" name="level">
										<label for="single_level5" name="level">★★★★★</label>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
										<option value="1">0</option><option value="1">1</option><option value="2">2</option>
										<option value="3">3</option><option value="4">4</option><option value="5">5</option>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
										<option value="0">0</option><option value="10">10</option><option value="20">20</option>
										<option value="30">30</option><option value="40">40</option><option value="50">50</option>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="level_wrap">
									<input id="is_test_false_single" target="single" value="false" type="radio" name="is_test" checked>
									<label for="is_test_false_single" name="is_test">否</label>
									<input id="is_test_true_single" target="single" value="true" type="radio" name="is_test">
									<label for="is_test_true_single" name="is_test">是</label>
								</div>
								
								<div id="section_single"  class="showSection">
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
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="create_date" value="<?php echo date('Y-m-d H:i:s');?>">
								<input type="hidden" name="type" value="SINGLE_CHOICE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<!-- 多選題 -->
						<form id="multi_choice" class="tab-content" action="api/addExercise_save.php" method="POST">
							<div class="question_content_wrap">
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"></textarea>
								<label for="single_opt_content">選項內容</label>
								<div class="opt_content">
									<div><span>(1)</span><textarea name="multi_opt_content_1"></textarea></div>
									<div><span>(2)</span><textarea name="multi_opt_content_2"></textarea></div>
									<div><span>(3)</span><textarea name="multi_opt_content_3"></textarea></div>
									<div><span>(4)</span><textarea name="multi_opt_content_4"></textarea></div>
									<div><span>(5)</span><textarea name="multi_opt_content_5"></textarea></div>
								</div>
								<label for="single_answer">本題解答</label>
								<div class="level_wrap">
									<input id="multi_opt1" value="1" type="checkbox" name="answer[]">
									<label for="multi_opt1" name="single_answer">(1)</label>
									<input id="multi_opt2" value="2" type="checkbox" name="answer[]">
									<label for="multi_opt2" name="single_answer">(2)</label>
									<input id="multi_opt3" value="3" type="checkbox" name="answer[]">
									<label for="multi_opt3" name="single_answer">(3)</label>
									<input id="multi_opt4" value="4" type="checkbox" name="answer[]">
									<label for="multi_opt4" name="single_answer">(4)</label>
									<input id="multi_opt5" value="5" type="checkbox" name="answer[]">
									<label for="multi_opt5" name="single_answer">(5)</label>
								</div>
								<label for="tags">標籤</label>
									<input class="tagsInput" name="tags">
								<label for="level">難易度</label>
								<div class="level_wrap">									
									<input id="multi_level1" value="1" type="radio" name="level">
										<label for="multi_level1" name="level">★</label>
									<input id="multi_level2" value="2" type="radio" name="level">
										<label for="multi_level2" name="level">★★</label>
									<input id="multi_level3" value="3" type="radio" name="level">
										<label for="multi_level3" name="level">★★★</label>
									<input id="multi_level4" value="4" type="radio" name="level">
										<label for="multi_level4" name="level">★★★★</label>
									<input id="multi_level5" value="5" type="radio" name="level">
										<label for="multi_level5" name="level">★★★★★</label>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
										<option value="1">0</option><option value="1">1</option><option value="2">2</option>
										<option value="3">3</option><option value="4">4</option><option value="5">5</option>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
										<option value="0">0</option><option value="10">10</option><option value="20">20</option>
										<option value="30">30</option><option value="40">40</option><option value="50">50</option>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="level_wrap">
									<input id="is_test_false_multi" target="multi" value="false" type="radio" name="is_test" checked>
									<label for="is_test_false_multi" name="is_test">否</label>
									<input id="is_test_true_multi" target="multi" value="true" type="radio" name="is_test">
									<label for="is_test_true_multi" name="is_test">是</label>
								</div>
								<div id="section_multi"  class="showSection">
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
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="create_date" value="<?php echo date('Y-m-d H:i:s');?>">
								<input type="hidden" name="type" value="MULTI_CHOICE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<!-- 題組 -->
						<form id="series_question" class="tab-content" action="api/addExercise_save.php" method="POST">
							
							<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
							<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
							<input type="hidden" name="type" value="SERIES_QUESTIONS">
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php require("footer.php"); ?>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		        $(".tagsInput").tagit();
		</script>
		<?php require("js/js_com.php"); ?>
		<script type="text/javascript" src="js/addExercise.js"></script>
		
		
	</body>
</html>