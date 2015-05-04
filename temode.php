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
	// var_dump($statusOff);
	// exit;
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
<?php
	foreach ($statusOn as $value){

?>
			<div id="statusOn" class="tab-content courseList">
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="itemRight"><a href="#">進入課程</a></div>
					<div class="itemRight"><a href="#">進入課程</a></div>
					<div class="itemRight"><a href="#">進入課程</a></div>
				</div>
			</div>
<?php } ?>
<?php
	foreach ($statusOff as $value){

?>
			<div id="statusOff" class="tab-content courseList">
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="itemRight"><a href="#">進入課程</a></div>
					<div class="itemRight"><a href="#">進入課程</a></div>
				</div>
			</div>
<?php } ?>
			
		</div>
		<?php require("footer.php") ?>
		<?php require("js/js_com.php") ?>
		<script src="js/switch.js"></script>
	</body>
</html>