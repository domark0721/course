<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	session_start();
	//get the course Metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='123'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	$id = $_GET['id'];
	$course_id = $_GET['course_id'];
	//get the objectID of the question from mysql
	$sql = "SELECT * FROM exam WHERE id='$id'";
	$result = mysql_query($sql);
	$examMetadata = mysql_fetch_assoc($result);

	$questionList = $examMetadata['questions'];
	$questionArray = explode(",", $questionList);

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
			<div class="container">
				<div class="headerOutside_wrap">
					<div class="header exam_wrap">
						<div class="headerLogo"><a><img src="../img/logo.png"/></a></div>
						<div class="courseName"><a>資料結構<span class="testName"> - Midterm</span></a></div>
						<div class="studentInfo">
							<a>考生：<?php echo $Member_NAME;?></a>
						</div>
					</div>
				</div>
				<div class="exercise_wrap examInfo_box">
						<div class="examInfo_wrap">
							<table class="examInfo">
								<tr class="examInfo-row">
									<th class="">科 目</th>
									<td><?php echo $courseMetadata['course_name'];?></td>
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
									<td><?php echo $examMetadata['time'];?></td>
								</tr>	
								<tr class="examInfo-row">
									<th class="">說 明</th>
									<td><?php echo $examMetadata['explanation'];?></td>
								</tr>							
							</table>
						</div>
						<div class="examCheck_wrap">
							<a class="examCheckBtn startBtn" href="exam.php?id=<?php echo $id;?>">開始考試</a>
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