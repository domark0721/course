<?php
	include_once('api/auth.php');
	include_once('api/isLogin.php');
	
	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/newCourse.css">
		<title>新增課程 - NUCourse</title>
	</head>

	
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div class="newCourseForm-wrap">
					<div id="title"><a>您即將加入以下課程</a></div>
					<form id="newCourseForm" action="api/add_newCourse.php" method="POST">
						<label for="courseName">請輸入課程名稱</label>
						<input class="nameInput" name="courseName" required><br>
						<button id="giveupBtn" type="button" onclick="location.href='temode.php'">取 消</button>
						<button id="nextBtn" type="submit">下一步</button>
					</form>
				</div>
			</div>
		</div>
			<?php require("footer.php"); ?>
	</body>
</html>