<?php
	include_once('../api/auth.php');
	include_once('../mysql.php');
	include_once('../mongodb.php');
	include_once('../api/isLogin.php');
	date_default_timezone_set("Asia/Taipei");

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$member_id = $_SESSION['member_id'];
	$course_id = $_GET['course_id'];

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
?>
<!doctype html>
<html>
	<head>
		<?php require("../meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="../css/mode.css">
		<link type="text/css" rel="stylesheet" href="../css/courseSetting.css">
		<link type="text/css" rel="stylesheet" href="../css/addExam.css">
		<title>NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("../header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-file-text-o"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">新增考試</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<h3>請選擇出題模式</h3>
				<ul id="generateType" class="typeList">
					<li>自動</li>
					<li>手動</li>
				</ul>
				<div class="addExam_wrap">
					<form id="addExam_form" action="" method="POST">
						<label for="examName">考試科目</label>
							<a id="examName" name="examName"><?php echo $courseMetadata['course_name']; ?></a>
							<input  name="examName" value="<?php echo $courseMetadata['course_name']; ?>" type="hidden">
						<label for="examName">考試類別</label>
						<div class="examType_wrap">
							<input id="examType1" value="1" type="radio" name="type">
							<label for="examType1">小考</label>
							<input id="examType2" value="2" type="radio" name="type">
							<label for="examType2">期中考</label>
							<input id="examType3" value="3" type="radio" name="type">
							<label for="examType3">期末考</label>
						</div>
						<label for="time">作答總時間</label>
							<input id="time" name="time" >
						<label for="explanation">說明</label>
							<textarea class="explanation" name="explanation"> </textarea>
					</form>
				</div>		
			
			</div>
		</div>



		<?php require("footer.php"); ?>
	</body>
</html>