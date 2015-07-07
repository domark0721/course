<?php
	include_once('api/auth.php');
	include_once('mysql.php');
	include_once('mongodb.php');
	include_once('api/isLogin.php');

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
	$mon = $exercise -> find($mongoQuery) -> sort(array('create_date' => -1));

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
				<div class="addExerciseTitle">新增題目</div>
				<div class="add_userControl">
					<ul class="add_tab-list">
						<li><a href="#add_true_false">是非題</a></li>
						<li><a href="#add_single_choice">單選題</a></li>
						<li><a href="#add_multi_choice">多選題</a></li>
						<li><a href="#add_series_question">題組</a></li>
					</ul>
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
						<div class="resultBtn_wrap">
							<a class="resultBtn save tfSave">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
						<div class="spinner_wrap" style="display:none;">
							<div class="spinner" >
							  <div class="cube1"></div>
							  <div class="cube2"></div>
							</div>
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
								<select name="min" class="time_select">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select">
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
						<div class="resultBtn_wrap">
							<a class="resultBtn save">新增此題</a>
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
								<select name="min" class="time_select">
									<?php for($i=0; $i<=30; $i++){ ?> <option value="<?php echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select>
								<a class="time_char">分</a>
								<select name="sec" class="time_select">
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
						<div class="resultBtn_wrap">
							<a class="resultBtn save">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
					</form>
					<!-- 題組 -->
					<form id="add_series_question" class="add_tab-content">
				
						<div class="resultBtn_wrap">
							<a class="resultBtn save">新增此題</a>
							<a class="resultBtn closeBox">關 閉</a>
						</div>
					</form>
					<input id="author_id" type="hidden" value="<?php echo $author_id;?>">
					<?php
						$sectionNameArray_Iterator = new ArrayIterator($sectionNameArray);
						$courseURLArray_Iterator = new ArrayIterator($courseURLArray);
						$combine = new MultipleIterator;
						$combine->attachIterator($sectionNameArray_Iterator);
						$combine->attachIterator($courseURLArray_Iterator);
						foreach($combine as $key => $Item){ ?>
						<input id="key<?php echo $key[0];?>" type="hidden" data-section-name="<?php echo $Item[0];?>" data-course-url="<?php echo $Item[1];?>">
					<?php } ?>
				</div>
