<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/newCourse.css">
		<title>新增課程 - NUCourse</title>
	</head>

	
	<body>
		<?php require("header.php") ?>
		<div class="newCourseForm-wrap">
			<div id="title"><a>您想新增課程嗎？</a></div>
			<form id="newCourseForm" action="api/add_newCourse.php" method="POST">
				<label for="courseName">請輸入課程名稱</label>
				<input class="nameInput" name="courseName" required><br>
				<button id="giveupBtn" type="button" onclick="location.href='temode.php'">放 棄</button>
				<button id="nextBtn" type="submit">下一步</button>
			</form>
		</div>
		<?php require("footer.php") ?>
	</body>
</html>