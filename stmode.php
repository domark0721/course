<?php
	include_once('api/auth.php');
	require ("mysql.php");
	
	session_start();

	$member_id = $_SESSION['member_id'];

	//current and finish course
	$current_lesson = "SELECT * FROM attendent as a LEFT JOIN course as b ON a.course_id = b.course_id WHERE member_id='$member_id'";
	$result = mysql_query($current_lesson);

	$attened_course = array(); //inital
	$finish_course = array();

	//result will be saved in array
	while($row = mysql_fetch_assoc($result)){
		if($row['finish']==0)
			$attened_course[] = $row;
		else
			$finish_course[] = $row;
	}

	//favorite course 
	$favorite_lesson = "SELECT * FROM favorite as a LEFT JOIN course as b ON a.course_id = b.course_id WHERE member_id='$member_id'";	
	$result = mysql_query($favorite_lesson);
	
	$favorite_course = array();
	while($row = mysql_fetch_assoc($result)){
		$favorite_course[] = $row;
	}	
	// var_dump($favorite_course);
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/stmode.css">
		<title>NUCourse</title>
	</head>

	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div id="role"><?php echo $Member_NAME ?> 同學您好！</div>
			<div class="userControl">
				<ul>
					<li id="currentTag">目前課程</li>
					<li id="finishTag">修畢課程</li>
					<li id="favoriteTag">收藏課程</li>
					<li>互動討論</li>
				</ul>
			</div>

			<div id="attened_course" class="courseList">  
<?php 
	foreach($attened_course as $value){
		// var_dump($value);
?>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name'] ?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'] ?></div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:<?php echo $value['progress']?>%"></div>
							</div>
							<span><?php echo $value['progress']?>%</span>
						</div>
					</div>
					<div class="itemRight"><a href="course.php">進入課程</a></div>
				</div>
<?php }?>
			</div>
			<div id="finish_course" class="courseList" style="display:none;">  
<?php 
	foreach($finish_course as $value){
		// var_dump($value);
?>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name'] ?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'] ?></div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:<?php echo $value['progress']?>%"></div>
							</div>
							<span><?php echo $value['progress']?>%</span>
						</div>
					</div>
					<div class="itemRight"><a href="course.php">進入課程</a></div>
				</div>
<?php }?>
			</div>
			<div id="favorite_course" class="courseList" style="display:none;">  
<?php 
	foreach($favorite_course as $value){
		// var_dump($value);
?>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name"><?php echo $value['course_name'] ?></div>
						<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'] ?></div>
					</div>
					<div class="itemRight"><a href="course.php">進入課程</a></div>
				</div>
<?php }?>
						
			</div>

			
		</div>
		<?php require("footer.php") ?>
		<?php require("js_com.php") ?>
		<script src="js/stmode.js"></script>
	</body>
</html>