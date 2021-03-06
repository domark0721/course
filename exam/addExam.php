<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');
	date_default_timezone_set("Asia/Taipei");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$member_id = $_SESSION['member_id'];
	$course_id = $_GET['course_id'];

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	$courseMetadata = mysql_fetch_assoc($result);



	//search question number from mongodb in exercise collection
	$mongoQuery = array('$and' => array(array('type' => 'TRUE_FALSE'),
										array('course_id' => (int)$course_id) ));
	$trueFalseNum = $exercise -> find($mongoQuery)->count();

	$mongoQuery = array('$and' => array(array('type' => 'SHORT_ANSWER'),
										array('course_id' => (int)$course_id) ));
	$shortAnswerNum = $exercise -> find($mongoQuery)->count();

	$mongoQuery = array('$and' => array(array('type' => 'SINGLE_CHOICE'),
										array('course_id' => (int)$course_id) ));
	$singleNum = $exercise -> find($mongoQuery)->count();

	$mongoQuery = array('$and' => array(array('type' => 'MULTI_CHOICE'),
										array('course_id' => (int)$course_id) ));
	$multiNum = $exercise -> find($mongoQuery)->count();

	$mongoQuery = array('$and' => array(array('type' => 'SERIES_QUESTIONS'),
										array('course_id' => (int)$course_id) ));
	$seriesNum = $exercise -> find($mongoQuery)->count();

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
		<?php require("../meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="../css/mode.css">
		<link type="text/css" rel="stylesheet" href="../css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="../css/addExam.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
		<title>新增考試 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("../header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-file-text-o"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">新增測驗</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="generateMode_wrap">
					<h3>請選擇出題模式</h3>
					<ul id="generateType" class="typeList">
						<li type="#auto_form">自動</li>
						<li type="#manual_form">手動</li>
					</ul>
				</div>
				<div class="addExam_wrap">
					<!-- 自動 -->
					<form id="auto_form" class="tab_form" action="editExam.php" method="POST">
						<label class="label_style" for="course_name">測驗科目</label>
							<a id="course_name" name="course_name"><?php echo $courseMetadata['course_name']; ?></a>
							<input  name="course_name" value="<?php echo $courseMetadata['course_name']; ?>" type="hidden"><br>
						<label class="label_style" for="examType">測驗類別</label>
						<div class="examType_wrap">
							<input id="auto_examType1" value="test" type="radio" name="type">
							<label for="auto_examType1">小考</label>
							<input id="auto_examType2" value="mid" type="radio" name="type">
							<label for="auto_examType2">期中考</label>
							<input id="auto_examType3" value="final" type="radio" name="type">
							<label for="auto_examType3">期末考</label>
						</div><br>
<!-- 						<label class="label_style" for="examName">難易度</label>
						<div class="examLevel_wrap">
						<?php for($i=1; $i<=5; $i++){?>
							<input id="auto_examLevel<?php echo $i?>" value="<?php echo $i?>" type="radio" name="level">
							<label for="auto_examLevel<?php echo $i?>"><?php for($j=1; $j<=$i; $j++){ ?>★<?php }?></label>
						<?php } ?>
							
						</div><br> -->
						<label class="label_style examDate" for="time">測驗時程</label>
						<div class="exam_time_wrap">
							<label for="time">開始時間</label>
								<input type="text" name="start_date" class="start_date" readonly>
							<div class="exam_time_select">
								<select name="start_hour" class="time_select">
									<?php for($i=0; $i<=24; $i++){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>時</span>
								<select name="start_min" class="time_select">
									<?php for($i=0; $i<=55; $i+=5){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>分</span>
							</div><br>
							<label for="time">結束時間</label>
								<input type="text" name="end_date" class="end_date" readonly>
							<div class="exam_time_select">
								<select name="end_hour" class="time_select">
									<?php for($i=0; $i<=24; $i++){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>時</span>
								<select name="end_min" class="time_select">
									<?php for($i=0; $i<=55; $i+=5){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>分</span>
							</div>
						</div><br>
<!-- 						<label class="label_style" for="time">作答時間</label>
							<input type="number" value="10" min="10" class="time" name="time" ><a class="word_style"> 分</a>
						<label class="line_distent" for="check_time">檢查時間</label>
							<input type="number" value="0" min="0" class="time" name="check_time" ><a class="word_style"> 分</a>		 -->
						<div class="questionNum_wrap">
							<label for="trueFalse">是非題</label>
								<input type="number" value="0" min="0" max="<?php echo $trueFalseNum;?>" class="questionNum trueFalse" name="trueFalse">
								<a class="showNum">(目前有 <?php echo $trueFalseNum;?> 題)</a>
							<label for="shortAnswer">簡答題</label>
								<input type="number" value="0" min="0" max="<?php echo $shortAnswerNum;?>" class="questionNum shortAnswer" name="shortAnswer">
								<a class="showNum">(目前有 <?php echo $shortAnswerNum;?> 題)</a>
							<label for="singleChoice">單選題</label>
								<input type="number" value="0" min="0" max="<?php echo $singleNum;?>" class="questionNum singleChoice" name="singleChoice">
								<a class="showNum">(目前有 <?php echo $singleNum;?> 題)</a>
							<label for="multiChoice">多選題</label>
								<input type="number" value="0" min="0" max="<?php echo $multiNum;?>" class="questionNum multiChoice" name="multiChoice">
								<a class="showNum">(目前有 <?php echo $multiNum;?> 題)</a>
							<label for="seriesQues">題 組</label>
								<input type="number" value="0" min="0" max="<?php echo $seriesNum;?>" class="questionNum seriesQues" name="seriesQues">
								<a class="showNum">(目前有 <?php echo $seriesNum;?> 題)</a>	
						</div><br>
						<label class="label_style">章節範圍</label>
						<div class="chapter_range_wrap">
							<?php
							foreach($contentData['chapters'] as $i => $chapter){
							?>
								<div class="chapter_wrap">
									<div class="chapter_title">
										<span><?php echo sprintf("CH%d: %s", $i+1, $chapter['name'] );?></span>
										<span class="select_all_section_btn">選取整章節</span>
									</div>
							<?php
								foreach($chapter['sections'] as $j => $section){
									$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
									// $sectionNameArray[$section['uid']] = $sectionName;
							?>
									<div class="section_wrap">
										<label><input type="checkbox" name="chapter_range[]" value="<?php echo $section['uid'];?>"><?php echo $sectionName?></label>
									</div>
							<?php
								}
							?>
								</div>
							<?php
							}	
							?>
						</div>
						<label class="label_style" for="explanation">說明</label>
							<textarea class="explanation" name="explanation"> </textarea>
						
						<div class="formCheck"></div>

						<input type="hidden" name="generateType" value="autoMode">
						<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
						<div class="funcBtns_wrap">
							<button class="next" type="submit">下一步</button>
							<button class="giveup" onclick="history.back()">返 回</button>
						</div>
					</form>
					<!-- 手動 -->
					<form id="manual_form" class="tab_form" action="editExam.php" method="POST">
						<label class="label_style" for="examName">測驗科目</label>
							<a id="course_name" name="course_name"><?php echo $courseMetadata['course_name']; ?></a>
							<input  name="course_name" value="<?php echo $courseMetadata['course_name']; ?>" type="hidden"><br>
						<label class="label_style" for="examType">測驗類別</label>
						<div class="examType_wrap">
							<input id="manual_examType1" value="test" type="radio" name="type">
							<label for="manual_examType1">小考</label>
							<input id="manual_examType2" value="mid" type="radio" name="type">
							<label for="manual_examType2">期中考</label>
							<input id="manual_examType3" value="final" type="radio" name="type">
							<label for="manual_examType3">期末考</label>
						</div><br>
						<label class="label_style examDate" for="time">測驗時程</label>
						<div class="exam_time_wrap">
							<label for="time">開始時間</label>
								<input type="text" name="start_date" class="start_date" readonly>
							<div class="exam_time_select">
								<select name="start_hour" class="time_select">
									<?php for($i=0; $i<=24; $i++){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>時</span>
								<select name="start_min" class="time_select">
									<?php for($i=0; $i<=55; $i+=5){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>分</span>
							</div><br>
							<label for="time">結束時間</label>
								<input type="text" name="end_date" class="end_date" readonly>
							<div class="exam_time_select">
								<select name="end_hour" class="time_select">
									<?php for($i=0; $i<=24; $i++){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>時</span>
								<select name="end_min" class="time_select">
									<?php for($i=0; $i<=55; $i+=5){ ?> <option value="<?php if($i<10) echo "0".$i;else echo $i;?>"><?php echo $i;?></option> <?php } ?>
								</select><span>分</span>
							</div>
						</div><br>
						<label class="label_style" for="explanation">說明</label>
							<textarea class="explanation" name="explanation"> </textarea>
						
						<div class="formCheck"></div>
						<input type="hidden" name="generateType" value="manualMode">
						<input type="hidden" name="course_id" value="<?php echo $course_id;?>">
						<div class="funcBtns_wrap">
							<button class="next" type="submit">下一步</button>
							<button class="giveup" onclick="history.back()">返 回</button>
						</div>
					</form>
					<div class="funcBtns_wrap btnDisppeard">	
						<button class="giveup" onclick="history.back()">返 回</button>
					</div>
				</div>
			</div>
			<?php require("../footer.php"); ?>
		</div>
		<?php require("../js/js_com.php"); ?>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<script>
		$(function() {
			$( ".start_date" ).datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
				$( ".end_date" ).datepicker( "option", "minDate", selectedDate );
					}
			});
			$( ".end_date" ).datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
				$( ".start_date" ).datepicker( "option", "maxDate", selectedDate );
						}
				});
			});
		</script>
		<script type="text/javascript" src="../js/addExam.js"></script>
	</body>
</html>