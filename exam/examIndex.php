<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	session_start();
	$course_id = $_GET['course_id'];
	//get the course Metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	$course_id = $_GET['course_id'];

	//get the objectID of the question from mysql
	$exam_id = $_GET['id'];
	$sql = "SELECT * FROM exam WHERE id='$exam_id'";
	$result = mysql_query($sql);
	$examMetadata = mysql_fetch_assoc($result);

	//all question will save in below array
	$trueFalseQues = array();
	$singleChoiceQues = array();
	$multiChoiceQues = array();
	$seriesQues = array();

	// query exercise from mongodb
	foreach($questionArray as $question){
		$mongoQuery = array('_id' => new MongoId($question));
		$mon = $exercise -> find($mongoQuery);
		// var_dump($mon);

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
	}
?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="../css/exam.css">
		<title>測驗 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_com.php");?>
			<div class="container">
				<div class="exercise_wrap examInfo_box">
					<div class="examInfo_wrap">
						<table class="examInfo">
							<tr class="examInfo-row">
								<th class="">科 目</th>
								<td><?php echo $examMetadata['course_name'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">授課老師</th>
								<td><?php echo $courseMetadata['teacher_name'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">類 別</th>
								<td><?php if($examMetadata['type'] == 'test') echo '小考';
										  else if($examMetadata['type'] == 'mid') echo '期中考';
										  else if($examMetadata['type'] == 'final') echo '期末考';?></td>
							</tr>	
							<tr class="examInfo-row">
								<th class="">時 間</th>
								<td><?php
										$time = explode(':', $examMetadata['time']);
										if($time[0]==0){
											$displayTime = $time[1] . '分';
										}else{
											$displayTime = $time[0] . '時' . $time[1] . '分';
										}
				 						echo $displayTime;?>
		 						</td>
							</tr>	
							<tr class="examInfo-row">
								<th class="">說 明</th>
								<td><?php echo $examMetadata['explanation'];?></td>
							</tr>							
						</table>
					</div>
					<div class="examCheck_wrap">
						<a class="examCheckBtn startBtn" href="exam.php?course_id=<?php echo $course_id;?>&id=<?php echo $exam_id;?>">開始考試</a>
						<a class="examCheckBtn giveUpBtn" href="../course.php?course_id=<?php echo $course_id;?>#exam">放 棄</a>
					</div>
				</div>
			</div>
			<?php require("../footer.php"); ?>
		</div>
		<?php require("../js/js_com.php"); ?>
		<script src="../js/exam.js"></script>
	</body>
</html>