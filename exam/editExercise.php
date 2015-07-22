<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');
	date_default_timezone_set("Asia/Taipei");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$member_id = $_SESSION['member_id'];
	$mongo_id = $_GET['id'];
	$course_id = $_GET['course_id'];
	//metadata from mysql
	// $sql = "SELECT * FROM course WHERE course_id='$course_id'";
	// $result = mysql_query($sql);

	// $courseMetadata_temp = array();
	// while($row = mysql_fetch_assoc($result)){
	// 	$courseMetadata_temp[] = $row;
	// }	

	// foreach($courseMetadata_temp as $tempData){
	// 	$courseMetadata = $tempData;
	// 	break;
	// }

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}

	$contentData = $courseData['content'];

	//exercise data from mongo
	$mongoQuery = array('_id' => new MongoId($mongo_id));
	$exerciseData = $exercise -> findOne($mongoQuery);
	$exerciseContent = $exerciseData['body'];
	// var_dump($exerciseData);
?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css">
		<link href="../css/jquery.tagit.css" rel="stylesheet" type="text/css">
		<link type="text/css" rel="stylesheet" href="../css/mode.css">
		<link type="text/css" rel="stylesheet" href="../css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="../css/addExercise.css">
		<title>編輯題目 - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("../header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-question-circle"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">編輯題目</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>

				<div class="editSetting-wrap">
					<div class="addExercise_wrap">
						<!-- 是非題 -->
						<?php if($exerciseData['type']== "TRUE_FALSE"){?>
						<form id="true_false" class="" action="../api/update_exercise.php" method="POST">
							<div class="question_content_wrap">
								<div class="typeName">本題為<span>是非題</span></div>
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"><?php echo $exerciseContent['question'];?></textarea>
								<label for="TF_answer">本題解答</label>
								<div class="opt">
									<input id="true" value="true" type="radio" name="answer" <?php if($exerciseContent['answer']=='true') echo "checked";?>>
									<label for="true" name="TF_answer">Ｏ</label>
									<input id="false" value="false" type="radio" name="answer" <?php if($exerciseContent['answer']=='false') echo "checked";?>>
									<label for="false" name="TF_answer">Ｘ</label>
								</div>
								<div class="tags_wrap">
									<label for="tags">標籤</label>
									<input type="text" class="tagsInput" name="tags" value="<?php echo $exerciseData['tags'];?>">
								</div>
								<label for="level">難易度</label>
								<div class="opt">
									<?php for($l=1; $l<=5; $l++){ ?>
										<input id="truefalse_level<?php echo $l;?>" value="<?php echo $l;?>" type="radio" name="level" <?php if($exerciseData['level']== $l) echo "checked";?>>
											<label for="truefalse_level<?php echo $l;?>" name="level"><?php for($m=1; $m<=$l; $m++){?>★<?php }?></label>
									<?php } ?>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
									<?php 
										$time = explode(":", $exerciseData['time']);
										for($i=0; $i<=30; $i++){ 
											if($time[0]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
									<?php 
										for($i=0; $i<=50; $i+=10){ 
											if($time[1]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="opt">
									<input id="is_test_false" target="trueFalse" value="false" type="radio" name="is_test" <?php if($exerciseData['is_test']==false) echo "checked";?>>
									<label for="is_test_false" name="is_test">否</label>
									<input id="is_test_true" target="trueFalse" value="true" type="radio" name="is_test" <?php if($exerciseData['is_test']==true) echo "checked";?>>
									<label for="is_test_true" name="is_test">是</label>
								</div>
								
								<div id="section_trueFalse" class="chapter_select <?php if($exerciseData['is_test']==true) echo "show";?>">
									<label for="section">適用章節</label>
									<select class="testSection_select" name="section">
									<?php
									foreach($contentData['chapters'] as $i => $chapter){
										$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
										<optgroup label='<?php echo $courseName; ?>'>
									<?php foreach($chapter['sections'] as $j => $section){
											$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
												if($section['uid'] == $exerciseData['test_section']){?>
													<option value="<?php echo $section['uid'];?>" selected><?php echo $sectionName;?></option>
										<?php }else{ ?>
													<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } } ?>
										</optgroup>
									<?php } ?>	
									</select>
								</div>
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="mongo_id" value="<?php echo $mongo_id;?>">
								<input type="hidden" name="type" value="TRUE_FALSE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<!-- 簡答題 -->
						<?php }else if($exerciseData['type']== "SHORT_ANSWER"){?>
						<form id="true_false" class="" action="../api/update_exercise.php" method="POST">
							<div class="question_content_wrap">
								<div class="typeName">本題為<span>簡答</span></div>
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"><?php echo $exerciseContent['question'];?></textarea>
								<label for="short_answer">本題解答</label>
									<textarea class="short_answer" name="short_answer"><?php echo $exerciseContent['answer'];?></textarea>
								<div class="tags_wrap">
									<label for="tags">標籤</label>
									<input type="text" class="tagsInput" name="tags" value="<?php echo $exerciseData['tags'];?>">
								</div>
								<label for="level">難易度</label>
								<div class="opt">
									<?php for($l=1; $l<=5; $l++){ ?>
										<input id="truefalse_level<?php echo $l;?>" value="<?php echo $l;?>" type="radio" name="level" <?php if($exerciseData['level']== $l) echo "checked";?>>
											<label for="truefalse_level<?php echo $l;?>" name="level"><?php for($m=1; $m<=$l; $m++){?>★<?php }?></label>
									<?php } ?>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
									<?php 
										$time = explode(":", $exerciseData['time']);
										for($i=0; $i<=30; $i++){ 
											if($time[0]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
									<?php 
										for($i=0; $i<=50; $i+=10){ 
											if($time[1]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="opt">
									<input id="is_test_false" target="trueFalse" value="false" type="radio" name="is_test" <?php if($exerciseData['is_test']==false) echo "checked";?>>
									<label for="is_test_false" name="is_test">否</label>
									<input id="is_test_true" target="trueFalse" value="true" type="radio" name="is_test" <?php if($exerciseData['is_test']==true) echo "checked";?>>
									<label for="is_test_true" name="is_test">是</label>
								</div>
								
								<div id="section_trueFalse" class="chapter_select <?php if($exerciseData['is_test']==true) echo "show";?>">
									<label for="section">適用章節</label>
									<select class="testSection_select" name="section">
									<?php
									foreach($contentData['chapters'] as $i => $chapter){
										$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
										<optgroup label='<?php echo $courseName; ?>'>
									<?php foreach($chapter['sections'] as $j => $section){
											$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
												if($section['uid'] == $exerciseData['test_section']){?>
													<option value="<?php echo $section['uid'];?>" selected><?php echo $sectionName;?></option>
										<?php }else{ ?>
													<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } } ?>
										</optgroup>
									<?php } ?>	
									</select>
								</div>
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="mongo_id" value="<?php echo $mongo_id;?>">
								<input type="hidden" name="type" value="SHORT_ANSWER">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<?php }else if($exerciseData['type']== "SINGLE_CHOICE"){ 
								$exerciseOpt = $exerciseContent['options'];?>
						<!-- 單選題 -->
						<form id="single_choice" class="" action="../api/update_exercise.php" method="POST">
							<div class="typeName">本題為<span>單選題</span></div>							
							<div class="question_content_wrap">
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"><?php echo $exerciseContent['question'];?></textarea>
								<label for="single_opt_content">選項內容</label>
								<div class="opt_content">
									<?php foreach($exerciseOpt as $i => $opt){ ?>
									<div>
										<span><?php echo "(" .($i+1). ")";?></span>
										<textarea name="single_opt_content_<?php echo ($i+1);?>"><?php echo $opt['content'];?></textarea>
									</div>
									<?php } ?>
								</div>
								<label for="single_answer">本題解答</label>
								<div class="opt">
									<?php foreach($exerciseOpt as $i => $opt){ ?>
									<input id="single_opt<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" type="radio" name="answer" <?php if($opt['is_answer']==true) echo"checked";?>>
									<label for="single_opt<?php echo ($i+1);?>" name="single_answer">(<?php echo ($i+1);?>)</label>
									<?php } ?>
								</div>
								<label for="tags">標籤</label>
									<input class="tagsInput" name="tags" value="<?php echo $exerciseData['tags'];?>">
								<label for="level">難易度</label>
									<div class="opt">
									<?php for($l=1; $l<=5; $l++){ ?>
										<input id="single_level<?php echo $l;?>" value="<?php echo $l;?>" type="radio" name="level" <?php if($exerciseData['level']== $l) echo "checked";?>>
											<label for="single_level<?php echo $l;?>" name="level"><?php for($m=1; $m<=$l; $m++){?>★<?php }?></label>
									<?php } ?>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
									<?php 
										$time = explode(":", $exerciseData['time']);
										for($i=0; $i<=30; $i++){ 
											if($time[0]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
									<?php 
										for($i=0; $i<=50; $i+=10){ 
											if($time[1]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="opt">
									<input id="is_test_false_single" target="single" value="false" type="radio" name="is_test" <?php if($exerciseData['is_test']==false) echo "checked";?>>
									<label for="is_test_false_single" name="is_test">否</label>
									<input id="is_test_true_single" target="single" value="true" type="radio" name="is_test" <?php if($exerciseData['is_test']==true) echo "checked";?>>
									<label for="is_test_true_single" name="is_test">是</label>
								</div>
								
								<div id="section_single"  class="chapter_select <?php if($exerciseData['is_test']==true) echo "show";?>">
									<label for="section">適用章節</label>
									<select class="testSection_select" name="section">
									<?php
									foreach($contentData['chapters'] as $i => $chapter){
										$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
										<optgroup label='<?php echo $courseName; ?>'>
									<?php foreach($chapter['sections'] as $j => $section){
											$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
												if($section['uid'] == $exerciseData['test_section']){?>
													<option value="<?php echo $section['uid'];?>" selected><?php echo $sectionName;?></option>
										<?php }else{ ?>
													<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } } ?>
										</optgroup>
									<?php } ?>	
									</select>
								</div>
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="mongo_id" value="<?php echo $mongo_id;?>">
								<input type="hidden" name="type" value="SINGLE_CHOICE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<?php } else if($exerciseData['type']== "MULTI_CHOICE"){
									$exerciseOpt = $exerciseContent['options'];?>
						<!-- 多選題 -->
						<form id="multi_choice" class="" action="../api/update_exercise.php" method="POST">
							<div class="typeName">本題為<span>多選題</span></div>
							<div class="question_content_wrap">
								<label for="question">題目</label>
									<textarea class="question_textarea" name="question"><?php echo $exerciseContent['question'];?></textarea>
								<label for="single_opt_content">選項內容</label>
								<div class="opt_content">
									<?php foreach($exerciseOpt as $i => $opt){ ?>
									<div>
										<span><?php echo "(" .($i+1). ")";?></span>
										<textarea name="multi_opt_content_<?php echo ($i+1);?>"><?php echo $opt['content'];?></textarea>
									</div>
									<?php } ?>
								</div>
								<label for="single_answer">本題解答</label>
								<div class="opt">
									<?php foreach($exerciseOpt as $i => $opt){ ?>
									<input id="multi_opt<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" type="checkbox" name="answer[]" <?php if($opt['is_answer']==true) echo"checked";?>>
									<label for="multi_opt<?php echo ($i+1);?>" name="single_answer">(<?php echo ($i+1);?>)</label>
									<?php } ?>
								</div>
								<label for="tags">標籤</label>
									<input class="tagsInput" name="tags">
								<label for="level">難易度</label>
								<div class="opt">
									<?php for($l=1; $l<=5; $l++){ ?>
										<input id="multi_level<?php echo $l;?>" value="<?php echo $l;?>" type="radio" name="level" <?php if($exerciseData['level']== $l) echo "checked";?>>
											<label for="multi_level<?php echo $l;?>" name="level"><?php for($m=1; $m<=$l; $m++){?>★<?php }?></label>
									<?php } ?>
								</div>
								<label for="time">答題時間</label>
									<select name="min" class="time_select">
									<?php 
										$time = explode(":", $exerciseData['time']);
										for($i=0; $i<=30; $i++){ 
											if($time[0]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>	
									</select>
									<a class="time_char">分</a>
									<select name="sec" class="time_select">
									<?php 
										for($i=0; $i<=50; $i+=10){ 
											if($time[1]==$i)
												echo '<option value="' .$i. '" selected>' .$i. '</option>';
											else
												echo '<option value="' .$i. '">' .$i. '</option>';
									}?>
									</select>
									<a class="time_char">秒</a>
								<label for="is_test">選為隨堂練習</label>
								<div class="opt">
									<input id="is_test_false_multi" target="multi" value="false" type="radio" name="is_test" <?php if($exerciseData['is_test']==false) echo "checked";?>>
									<label for="is_test_false_multi" name="is_test">否</label>
									<input id="is_test_true_multi" target="multi" value="true" type="radio" name="is_test" <?php if($exerciseData['is_test']==true) echo "checked";?>>
									<label for="is_test_true_multi" name="is_test">是</label>
								</div>
								<div id="section_multi"  class="chapter_select <?php if($exerciseData['is_test']==true) echo "show";?>">
									<label for="section">適用章節</label>
									<select class="testSection_select" name="section">
									<?php
									foreach($contentData['chapters'] as $i => $chapter){
										$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] ); ?>
										<optgroup label='<?php echo $courseName; ?>'>
									<?php foreach($chapter['sections'] as $j => $section){
											$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
											if($section['uid'] == $exerciseData['test_section']){?>
													<option value="<?php echo $section['uid'];?>" selected><?php echo $sectionName;?></option>
										<?php }else{ ?>
													<option value="<?php echo $section['uid'];?>"><?php echo $sectionName;?></option>
									<?php } } ?>
										</optgroup>
									<?php } ?>	
									</select>
								</div>
								
								<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
								<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="mongo_id" value="<?php echo $mongo_id;?>">
								<input type="hidden" name="type" value="MULTI_CHOICE">
							</div>
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<?php } else if($exerciseData['type']== "SERIES_QUESTIONS"){ ?>
						<!-- 題組 -->
						<form id="series_question" class="" action="../api/update_exercise.php" method="POST">
							
							<input type="hidden" name="author_id" value="<?php echo $member_id;?>">
							<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
							<input type="hidden" name="type" value="SERIES_QUESTIONS">
							<div class="resultBtn">
								<button class="panelBtn save" type="submit" >儲存此題</button>
								<button class="panelBtn giveup" onclick="history.back()">離 開</button>
							</div>
						</form>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php require("../footer.php"); ?>
		</div>
		
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		        $(".tagsInput").tagit();
		</script>
		<?php require("../js/js_com.php"); ?>
		<script type="text/javascript" src="../js/addExercise.js"></script>
	</body>
</html>