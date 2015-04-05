<?php

?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/course.css">
		<link type="text/css" rel="stylesheet" href="css/stmode.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div id="courseBar">
			<div id="courseBarPosition" class="clearfix">
				<div class="content-wrap">
					<div id="coursePic">
						<img src="img/user-course.jpg">
					</div>
					<div id="courseInfo">
						<div id="courseName">資料結構</div>
						<div id="courseTeacher">Amy Wang</div>
					</div>
					<div class="courseControl">
						<ul>
							<li>課程公告</li>
							<li>課程資訊</li>
							<li>課程內容</li>
							<li>互動與討論</li>
						</ul>
					</div>
				</div>				
			</div>
		</div>
		<div class="announce-wrap">
			<div id="announceList-container">
				<div class="announceItem">
					<div class="announceTitle"><i class="fa fa-bullhorn"> 最新課程已經更新</i></div>
					<div class="announceDate">2015-05-18</div>
					<div class="announceContent">由於之前課程教材有錯誤，目前課程已經更新。</div>
				</div>
				<div class="announceItem">
					<div class="announceTitle"><i class="fa fa-bullhorn"> 最新課程已經更新</i></div>
					<div class="announceDate">2015-05-18</div>
					<div class="announceContent">由於之前課程教材有錯誤，目前課程已經更新。</div>
				</div>
				<div class="announceItem">
					<div class="announceTitle"><i class="fa fa-bullhorn"> 最新課程已經更新</i></div>
					<div class="announceDate">2015-05-18</div>
					<div class="announceContent">由於之前課程教材有錯誤，目前課程已經更新。</div>
				</div>	
				<div class="announceItem">
					<div class="announceTitle"><i class="fa fa-bullhorn"> 最新課程已經更新</i></div>
					<div class="announceDate">2015-05-18</div>
					<div class="announceContent">由於之前課程教材有錯誤，目前課程已經更新。</div>
				</div>				
			</div>
		</div>
		<?php require("footer.php") ?>
	</body>
</html>