<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	
	$course_id = $_GET['course_id'];
	// $course_id = 123;

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $tempData){
		$courseMetadata = $tempData;
		break;
	}

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	// var_dump($courseData['description']);
	// exit;

// 	SELECT * from attendent as a
// LEFT JOIN member as b ON a.member_id = b.member_id
// where course_id="123"
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<title>課程一般設定 - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-wrench"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">學生管理</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="editSetting-wrap">
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
	</body>
</html>