<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	
	$course_id = $_GET['course_id'];
	
	//courseMetaData
	$sql = "SELECT * from course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata = $row;
		break;
	}	

	//studentList from mysql
	$sql = "SELECT * from attendent as a
			LEFT JOIN member as b ON a.member_id = b.member_id
			where course_id='$course_id'";
	$result = mysql_query($sql);
	$studentList = array();
	while($row = mysql_fetch_assoc($result)){
		$studentList[] = $row;
	}	

	
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="css/studentManage.css">
		<title>學生管理 - <?php echo $courseMetadata['course_name']; ?> - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-book fa_plus"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">學生管理</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="studentListStatus-wrap">
					<?php
						$noFinishNum = 0;
						if(!empty($studentList)){
							for($i=0; $i<count($studentList); $i++) {
								$studentData = $studentList[$i];
								if($studentData['is_finish']==0){
									$noFinishNum++;
								}
							}
						}
					?>
					<div class="totalStuNum">修課人數: <?php echo count($studentList);?></div>
					<div class="noFinishNum">正在修習: <?php echo $noFinishNum;?></div>
				</div>
				<div class="studentList-wrap">
					<div class="studentList_container">
					<?php
						if(!empty($studentList)){
							for($i=0; $i<count($studentList); $i++) {
								$studentData = $studentList[$i];
								$memberID = sprintf("%03d", $studentData['member_id']);
								
								if(!empty($studentData['score'])){
									$score = $studentData['score'];
								} else { 
									$score = '---';
								}
								
								$joinDate = explode(' ', $studentData['create_date']);

					?>
						<div class="student_item">
							<table class="student_table">
								<tr class="examInfo-row">
									<td class="student_id"><?php echo $memberID;?></td>
									<td class="student_name"><a><?php echo $studentData['name'];?></a></td>
									<td class="totalScore">總成績: <?php echo $score;?></td>
									<td class="joinDate">加入日期: <?php echo $joinDate[0];?></td>
									<td class="studentInfo"><a href="studentInfo.php?course_id=<?php echo $course_id;?>&student_id=<?php echo $studentData['member_id'];?>">詳細資料</a></td>
								</tr>						
							</table>
						</div>							
						
				<?php }}else{ ?>
						<div class="student_item">
							<a class="no_student">--- 該課程尚尚無學生 ---</a>
						</div>															
				<?php }?>
					</div>
					<div class="resultBtn">
						<a class="panelBtn giveup" href="temode.php">返 回</a>
					</div>	
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
	</body>
</html>