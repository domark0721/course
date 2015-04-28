<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/courseSections.css">
		<title>NUCourse</title>
	</head>

	
	<body>
		<?php require("header.php") ?>
		<div id="videoBar">
			<div class="content-wrap">
				<div id="courseChapterBar">
					<div id="leftChapter"><i class="fa fa-arrow-left"></i> 首頁</div>
					<div id="rightChapter">1-2 符號 <i class="fa fa-arrow-right"></i></div>
					<div id="thisChapter">1-1 複雜度</div>
				</div>
				<div id="video">
					<video controls >
					  <source src="videos/small.mp4" type="video/mp4">
					  Your browser does not support the video tag.
					</video>
				</div>
			</div>
		</div>
		<div id="courseTab-wrap" class="display-wrap">
			<div class="userControl">
				<ul>
					<li><a href="#">文字教材</a></li>
					<li><a href="#">隨堂練習</a></li>
					<li><a href="#">互動與討論</a></li>
					<li><a href="#">課程選單</a></li>
				</ul>
			</div>
		</div>
		<div id="courseContent-wrap" class="display-wrap displayAttribute">
			<div id="courseContent">測試</div>
		</div>
	
		<?php require("footer.php") ?>
	</body>
</html>