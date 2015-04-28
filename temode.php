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
		<title>NUCourse</title>
	</head>

	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div id="role"><?php echo $Member_NAME ?>  老師您好！</div>
			<div class="userControl">
				<ul class="courseTypeTab">
					<li id="statusOnTag">正在授課</li>
					<li id="statusOffTag">結束授課</li>
					<li>開新課程</li>
				</ul>
			</div>
<?php
	foreach ($statusOn as $value){

?>
			<div id="statusOn" class="courseList">
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="itemRight"><a href="#">修改課程</a></div>
				</div>
			</div>
<?php } ?>
<?php
	foreach ($statusOff as $value){

?>
			<div id="statusOff" class="courseList" style="display:none">
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name']?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name']?></div>
					</div>
					<div class="itemRight"><a href="#">修改課程</a></div>
				</div>
			</div>
<?php } ?>
			
		</div>
		<?php require("footer.php") ?>
		<?php require("js/js_com.php") ?>
		<script src="js/modeShow.js"></script>
	</body>
</html>