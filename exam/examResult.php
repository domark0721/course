<?php
	include_once('../api/auth.php');
	include_once("../mongodb.php");
	include_once("../mysql.php");
	include_once('../api/isLogin.php');

	// session_start();
	$member_id = $_SESSION['member_id'];
	$result_id = $_GET['result_id'];
	//get the course Metadata from mysql
	$sql = "SELECT a.id, a.course_id, a.exam_id, a.correct_num, a.total_num, a.score, a.answer_snapshot, a.date, b.type, c.course_name, c.teacher_name
				FROM exam_result as a
				LEFT JOIN exam as b ON a.exam_id = b.id
				LEFT JOIN course as c ON a.course_id = c.course_id
				WHERE a.id='$result_id'";

	$result = mysql_query($sql);
	$examResultMeta = mysql_fetch_assoc($result);
	// var_dump($examResultMeta);
	$examMetadata['course_name'] = $examResultMeta['course_name']; //for different page
?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="../css/exam.css">
		<link type="text/css" rel="stylesheet" href="../css/examResult.css">
		<?php if($$examResultMeta['type']=='final') $examType = "期末考";
				else if($examResultMeta['type']=='mid') $examType = "期中考";
				else if($examResultMeta['type']=='test') $examType = "小考";?>
		<title><?php echo $examResultMeta['course_name']. ' ' . $examType;?> - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_com.php");?>
			<div class="container">
				<div class="score_wrap score_box">
					<div class="totalScoreTitle">總成績</div>
					<div class="score"><?php echo $examResultMeta['score'];?></div>
				</div>
				<div class="exercise_wrap examInfo_box">
					<div class="examInfo_wrap">
						<table class="examInfo">
							<tr class="examInfo-row">
								<th class="">總題數</th>
								<td><?php echo $examResultMeta['total_num'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">答對題數</th>
								<td><?php echo $examResultMeta['correct_num'];?></td>
							</tr>
							<tr class="examInfo-row">
								<th class="">答對率</th>
								<td><?php echo sprintf("%.2f%%", ($examResultMeta['correct_num']/$examResultMeta['total_num']) * 100);?></td>
							</tr>	
							<tr class="examInfo-row">
								<th class="">交卷時間</th>
								<td><?php echo $examResultMeta['date'];?></td>
							</tr>					
						</table>
					</div>
					<div class="examCheck_wrap">
						<a class="examCheckBtn startBtn" href="examView.php?id=<?php echo $examResultMeta['exam_id'];?>">批改結果</a>
						<a class="examCheckBtn giveUpBtn" href="../course.php?course_id=<?php echo $examResultMeta['course_id'];?>#exam">返回課程</a>
					</div>
				</div>
			</div>
			<?php require("../footer.php"); ?>
		</div>
	</body>
</html>