<?php
	ini_set('default_charset','utf-8');
	include_once('api/auth.php');
	require_once ('mysql.php');
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 

	//user can't enter this page if they match the mode
	if($_SESSION['mode'] == "st"){
		Header("location: stmode.php");
	}

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	$member_id = $_SESSION['member_id'];
	$teaching_course = "SELECT * FROM course WHERE teacher_id='$member_id'";
	$result = mysql_query($teaching_course);

	$statusOn = array();
	$statusOff = array();
	$statusEditing = array();

	while($row = mysql_fetch_assoc($result)){
		if($row['status']==0)
			$statusOff[] = $row;
		else if($row['status']==1)
			$statusOn[] = $row;
		else
			$statusEditing[] = $row;
	}

	// var_dump($statusEditing);
	// exit;

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/header_dark.css">
		<title>老師模式 - NUCourse</title>
	</head>

	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div class="content-wrap">
					<div id="role"><?php echo $Member_NAME; ?>  老師您好！</div>
					<a id="newCourse" class="newCourseBtn" href="newCourse.php"><i class="fa fa-plus fa_newCourse"></i>開新課程</a>
					<div class="userControl">
						<ul class="tab-list">
							<li><a href="#statusOn">正在授課</a></li>
							<li><a href="#statusEditing">編輯中的課程</a></li>
							<li><a href="#statusOff">結束授課</a></li>
						</ul>
					</div>
					<div id="statusEditing" class="tab-content courseList">
<?php
	if(count($statusEditing)){
		foreach ($statusEditing as $value){
?>
						<div class="courseItem clearfix">
							<div class="itemLeft"><img src="img/user-course.jpg"></div>
							<div class="item-course-info"> 
								<div class="item-course-name"><?php echo $value['course_name'];?></div>
								<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'];?></div>
							</div>
							<div class="middleBtns">
								<a class="settingBtn" href="courseSetting.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-cog fa_plus"></i> 一般設定</a>					
								<a class="manageStudentBtn" href="studentManage.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-book fa_plus"></i>學生管理</a>
								<a class="enterBtn" href="course.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-chevron-circle-right fa_plus"></i>進入課程</a>
							</div>
							<div class="rightBtns">
								
								<a class="editCourseBtn" href="editCourse.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-pencil fa_plus"></i> 內容編寫</a>
								<a class="questionManage" href="exam/exercise.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-leanpub fa_plus"></i>題庫管理</a>
								<a class="questionManage" href="exam/examList.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-file-text-o fa_plus"></i> 考試管理</a>
							</div>
						</div>
<?php }}else{ ?>
						<div class="noCourse">------ 該列表並無課程 ------</div>		
<?php }?>
			
					</div>

					<div id="statusOn" class="tab-content courseList">
<?php
	if(count($statusOn)){
		foreach ($statusOn as $value){
?>
						<div class="courseItem clearfix">
							<div class="itemLeft"><img src="img/user-course.jpg"></div>
							<div class="item-course-info"> 
								<div class="item-course-name"><?php echo $value['course_name'];?></div>
								<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'];?></div>
							</div>
							<div class="middleBtns">
								<a class="settingBtn" href="courseSetting.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-cog fa_plus"></i> 一般設定</a>				
								<a class="manageStudentBtn" href="studentManage.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-book fa_plus"></i>學生管理</a>
								<a class="enterBtn" href="course.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-chevron-circle-right fa_plus"></i>進入課程</a>	
							</div>
							<div class="rightBtns">
								<a class="editCourseBtn" href="editCourse.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-pencil fa_plus"></i> 內容編寫</a>
								<a class="questionManage" href="exercise.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-leanpub fa_plus"></i>題庫管理</a>
								<a class="questionManage" href="exam/examList.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-file-text-o fa_plus"></i> 考試管理</a>
							</div>
						</div>
<?php }}else{ ?>
						<div class="noCourse">------ 該列表並無課程 ------</div>		
<?php }?>
					</div>


					<div id="statusOff" class="tab-content courseList">
<?php
	if(count($statusOff)){
		foreach ($statusOff as $value){
?>
						<div class="courseItem clearfix">
							<div class="itemLeft"><img src="img/user-course.jpg"></div>
							<div class="item-course-info"> 
								<div class="item-course-name"><?php echo $value['course_name'];?></div>
								<div class="item-course-teacher">授課老師：<?php echo $value['teacher_name'];?></div>
							</div>
							<div class="middleBtns">
								<a class="settingBtn" href="courseSetting.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-cog fa_plus"></i> 一般設定</a>
								<a class="manageStudentBtn" href="studentManage.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-book fa_plus"></i>學生管理</a>
								<a class="enterBtn" href="course.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-chevron-circle-right fa_plus"></i>進入課程</a>
							</div>
							<div class="rightBtns">
								<a class="editCourseBtn" href="editCourse.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-pencil fa_plus"></i> 內容編寫</a>
								<a class="questionManage" href="exercise.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-leanpub fa_plus"></i>題庫管理</a>
								<a class="questionManage" href="exam/examList.php?course_id=<?php echo $value['course_id'];?>"><i class="fa fa-file-text-o fa_plus"></i> 考試管理</a>
							</div>
						</div>
<?php }}else{ ?>
						<div class="noCourse">------ 該列表並無課程 ------</div>		
<?php }?>
					</div>					
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
		<script src="js/switch.js"></script>
	</body>
</html>