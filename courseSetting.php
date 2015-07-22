<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	include_once('api/isLogin.php');

	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
	
	$course_id = $_GET['course_id'];

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata = mysql_fetch_assoc($result);

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	// var_dump($courseData['description']);
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<!-- <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css"> -->
		<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<title>課程設定 - NUCourse</title>
	</head>	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="topBanner_wrap">
					<div class="content-wrap clearfix">
						<div class="editorBarIcon"><i class="fa fa-wrench"></i></div>
						<div class="courseHeader">
							<div class="topBanner_Title">課程設定</div>
							<div class="topBanner_CourseName"><?php echo $courseMetadata['course_name']; ?></div>
						</div>
					</div>
				</div>
				<div class="editSetting-wrap">
					<div class="editSetting">
						<form id="editSettingForm" class="" action="api/save_courseSetting.php?course_id=<?php echo $course_id;?>" method="POST">
							<div class="editorBox">
								<a>老師資訊</a>
								<label for="teacher_name">姓名</label>
								<input class="normalInput" name="teacher_name" value="<?php echo $courseMetadata['teacher_name']; ?>">
								<label class="margin-left" for="teacher_mail">E-Mail</label>
								<input class="normalInput teacher_mail_input" name="teacher_mail" value="<?php echo $courseMetadata['teacher_mail']; ?>"><br>
								<label for="website">網站</label>
								<input class="normalInput" name="website" value="<?php echo $courseMetadata['website']; ?>" style="width:90%;">
							</div>
							<div id="courseInfo" class="editorBox">
								<a>課程資訊</a>
								<label for="course_name">名稱</label>
									<input id="course_name_input" class="normalInput" name="course_name" value="<?php echo $courseMetadata['course_name']; ?>"><br>
								<label for="course_id">編號</label>
									<input id="course_id_input" class="normalInput" name="course_id" value="<?php echo $courseMetadata['course_id']; ?>" disabled>
								<label class="margin-left" for="type">類別</label>
									<input class="normalInput" name="type" value="<?php echo $courseMetadata['type']; ?>"><br>
								<label for="lang">語言</label>
									<input class="normalInput" name="lang" value="<?php echo $courseMetadata['lang']; ?>">
								<label class="margin-left" for="lang">課程狀態</label>
								<div class="class_status">	
									<input id="status2" type="radio" name="status" value="2" <?php if($courseMetadata['status'] == 2) echo "checked";?>>
									<label for="status2">編輯中</label>
									<input id="status1" type="radio" name="status" value="1" style="margin-left: 10px;" <?php if($courseMetadata['status'] == 1) echo "checked";?>>
									<label for="status1">開放</label>
									<input id="status0" type="radio" name="status" value="0" style="margin-left: 10px;" <?php if($courseMetadata['status'] == 0) echo "checked";?>>
									<label for="status0">結束</label>
								</div><br>
								<label for="start_time">開課時間</label>
									<input id="datepicker" class="start_time_input normalInput" name="start_time" value="<?php echo $courseMetadata['start_time']; ?>">
								<div class="tags_wrap">
									<label for="tags">標籤</label>
									<input class="courseTags" type="text" name="tags" value="<?php echo $courseMetadata['tags']; ?>">
								</div>
								<label for="pic">封面圖片</label>
									<input type="file" name="pic">
								<img src="<?php echo $courseMetadata['pic']; ?>">
							</div>

							<div class="nav-wrap">
								<div class="userControl">
									<ul class="tab-list">
										<li><a href="#textarea_description">課程描述</a></li>
										<li><a href="#textarea_syllabus">授課大綱</a></li>
										<li><a href="#textarea_teachingMathods">上課形式</a></li>
										<li><a href="#textarea_textbooks">指定用書</a></li>
										<li><a href="#textarea_references">參考資料</a></li>
									</ul>
								</div>
							</div>

							<div id="textarea_description" class="tab-content sectionEditorWrap">
								<textarea class="sectionEditor" name="description" style="width:100%"><?php echo $courseData['description'];?></textarea>
							</div>
							<div id="textarea_syllabus" class="tab-content sectionEditorWrap">
								<textarea class="sectionEditor" name="syllabus" style="width:100%"><?php echo $courseData['syllabus'];?></textarea>
							</div>
							<div id="textarea_teachingMathods" class="tab-content sectionEditorWrap">
								<textarea class="sectionEditor" name="teachingMethods" style="width:100%"><?php echo $courseData['teachingMethods'];?></textarea>
							</div>
							<div id="textarea_textbooks" class="tab-content sectionEditorWrap">
								<textarea class="sectionEditor" name="textbooks" style="width:100%"><?php echo $courseData['textbooks'];?></textarea>
							</div>
							<div id="textarea_references" class="tab-content sectionEditorWrap">
								<textarea class="sectionEditor" name="references" style="width:100%"><?php echo $courseData['references'];?></textarea>
							</div>
							
							<div class="resultBtn">
								<a id="submitFormBtn" class="panelBtn save">儲 存</a>
								<a class="panelBtn giveup" href="temode.php">離 開</a>								
							</div>
						</form>

					</div>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
		<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/tag-it.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		        $(".courseTags").tagit(); 
		</script>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
			$(function() {
				    $( "#datepicker" ).datepicker();
				    $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			});
		</script>
		<script type="text/javascript" src="js/courseSetting.js"></script>
	</body>
</html>


