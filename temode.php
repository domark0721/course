<?php
	include_once('api/auth.php');
	require ("mysql.php");

	//user can't enter this page if they match the mode
	if($_SESSION['mode'] == "st"){
		Header("location: stmode.php");
	}

	session_start();
	$member_id = $_SESSION['member_id'];

	$teaching_course = "SELECT * FROM course WHERE teacher_id='$member_id'";
	$result = mysql_query($teaching_course);

	$statusOn = array();
	$statusOff = array();

	while($row = mysql_fetch_assoc($result)){
		if($row['status']==0)
			$statusOff[] = $row;
		else
			$statusOn[] = $row;
	}

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<title>老師模式 - NUCourse</title>
	</head>

	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div id="role"><?php echo $Member_NAME ?>  老師您好！</div>
			<div class="userControl">
				<ul class="tab-list">
					<li><a href="#statusOn">正在授課</a></li>
					<li><a href="#statusOff">結束授課</a></li>
					<li><a>編輯中的課程</a></li>
					<li><a>開新課程</a></li>
				</ul>
			</div>

			<div id="statusOn" class="tab-content courseList">
<?php
	foreach ($statusOn as $value){
?>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="rightBtns">
						<a class="settingBtn" href="courseSetting.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;一般設定</a>
						<a class="editCourseBtn" href="editCourse.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-cog"></i>&nbsp;&nbsp;&nbsp;內容編寫</a>
						<a class="enterBtn" href="course.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;&nbsp;進入課程</a>
					</div>
<!-- 					<div class="itemRight"><a href="#">進入課程</a></div>
					<div class="itemRight"><a href="#">進入課程</a></div>
					<div class="itemRight"><a href="#">進入課程</a></div> -->
				</div>
<?php } ?>
			</div>

			<div id="statusOff" class="tab-content courseList">
<?php
	foreach ($statusOff as $value){
?>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="rightBtns">
						<a class="settingBtn" href="courseSetting.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-wrench"></i>&nbsp;&nbsp;&nbsp;一般設定</a>
						<a class="editCourseBtn" href="editCourse.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-cog"></i>&nbsp;&nbsp;&nbsp;內容編寫</a>
						<a class="enterBtn" href="course.php?course_id=<?php echo $value['course_id']?>"><i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;&nbsp;進入課程</a>
					</div>
				</div>
<?php } ?>
			</div>
			
		</div>
		<?php require("footer.php") ?>
		<?php require("js/js_com.php") ?>
		<script src="js/switch.js"></script>
	</body>
</html>