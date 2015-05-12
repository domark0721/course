<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
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
	$mongoQuery = array('course_id' => $course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}
	// var_dump();
	// exit;
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSetting.css">
		<title>課程一般設定 - NUCourse</title>
	</head>

	
	<body>
		<?php require("header.php") ?>
		<div id="editorBar-wrap">
			<div class="content-wrap clearfix">
				<div class="editorBarWrench"><i class="fa fa-wrench"></i></div>
				<div class="courseHeader">
					<div id="editorBarTitle">課程一般設定</div>
				</div>
			</div>
		</div>
		<div class="editSetting-wrap">
			<div class="editSetting">
				<form id="editSettingForm" class="" action="api/save_courseSetting.php?course_id=<?php echo $course_id;?>" method="POST">
					<div class="editorBox">
						<a>老師資訊</a>
						<label for="teacher_name">姓名</label>
						<input class="" name="teacher_name" value="<?php echo $courseMetadata['teacher_name']; ?>">
						<label class="margin-left" for="teacher_mail">E-Mail</label>
						<input id="teacher_mail_input" class="" name="teacher_mail" value="<?php echo $courseMetadata['teacher_mail']; ?>"><br>
						<label for="website">網站</label>
						<input class="" name="website" value="<?php echo $courseMetadata['website']; ?>">
					</div>
					<div id="courseInfo" class="editorBox">
						<a>課程資訊</a>
						<label for="course_name">名稱</label>
						<input id="course_name_input" name="course_name" value="<?php echo $courseMetadata['course_name']; ?>"><br>
						<label for="course_id">編號</label>
						<input id="course_id_input"  name="course_id" value="<?php echo $courseMetadata['course_id']; ?>" disabled>
						<label class="margin-left" for="type">類別</label>
						<input class="" name="type" value="<?php echo $courseMetadata['type']; ?>"><br>
						<label for="lang">語言</label>
						<input class="" name="lang" value="<?php echo $courseMetadata['lang']; ?>">
						<label class="margin-left" for="lang">課程狀態</label>
						<?php if($courseMetadata['status'] == 0){ 
									echo '<input type="radio" name="status" value="2"> 編輯中';
									echo '<input type="radio" name="status" value="1" style="margin-left: 10px;"> 開放';
									echo '<input type="radio" name="status" value="0" checked style="margin-left: 10px;"> 結束 <br>';
								}else if($courseMetadata['status'] == 1){
									echo '<input type="radio" name="status" value="2"> 編輯中';
									echo '<input type="radio" name="status" value="1" checked style="margin-left: 10px;"> 開放';
									echo '<input type="radio" name="status" value="0" style="margin-left: 10px;"> 結束 <br>';
								}else {
									echo '<input type="radio" name="status" value="2" checked> 編輯中';
									echo '<input type="radio" name="status" value="1" style="margin-left: 10px;"> 開放';
									echo '<input type="radio" name="status" value="0" style="margin-left: 10px;"> 結束 <br>';
								}
						?>

						<label for="start_time">開課時間</label>
						<input id="start_time_input" class="" name="start_time" value="<?php echo $courseMetadata['start_time']; ?>"><br>
						<label for="pic">封面圖片</label>
						<input type="file" name="pic">
						<img src="img/user-course.jpg">
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
						<a class="panelBtn giveup" href="temode.php">離 開</a>
						<a id="submitFormBtn" class="panelBtn save">儲 存</a>
					</div>
				</form>

			</div>
		</div>
		<?php require("footer.php") ?>
		<?php require("js/js_com.php") ?>
		<script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="js/courseSetting.js"></script>
	</body>
</html>