<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/stmode.css">
		<link type="text/css" rel="stylesheet" href="css/course.css">
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
				</div>				
			</div>
		</div>
		<?php require("course-nav.php") ?>
		<div class="content-wrap clearfix content-wrap-schedule" style="">
			<nav id="schedule-nav">
				<ul class="schedule-nav-fix">
					<li><a href="#chpater-1">CH1: 基本概念c</a></li>
					<li><a href="#chpater-2">CH2: 陣列</a></li>
					<li><a href="#chpater-3">CH3: 堆疊與佇列</a></li>
					<li><a href="#chpater-4">CH4: 鏈結</a></li>
					<li><a href="#chpater-5">CH5: 樹</a></li>
				</ul>
			</nav>
			<div class="schedule-wrap">
				<div id="schedule-container">
					<div class="scheduleItem">
						<div class="chapterTitle" id="chpater-1"><i class="fa fa-bookmark-o"></i> CH1: 基本概念</div>
						<div class="subChapter" id="chpater-1-1"><a href="coursesection.php">1-1<span> 複雜度</span></a></div>
						<div class="subChapter" id="chpater-1-2">1-2<span> 符號</span></div>
						<div class="subChapter" id="chpater-1-3">1-3<span> 符號</span></div>
						<div class="subChapter" id="chpater-1-4">1-4<span> 符號</span></div>
						<div class="subChapter" id="chpater-1-5">1-5<span> 符號</span></div>
					</div>
					<div class="scheduleItem">
						<div class="chapterTitle" id="chpater-2"><i class="fa fa-bookmark-o"></i> CH2: 陣列</div>
						<div class="subChapter" id="chpater-2-1">1-1<span> 複雜度</span></div>
						<div class="subChapter" id="chpater-2-2">1-2<span> 符號</span></div>
						<div class="subChapter" id="chpater-2-3">1-3<span> 符號</span></div>
						<div class="subChapter" id="chpater-2-4">1-4<span> 符號</span></div>
						<div class="subChapter" id="chpater-2-5">1-5<span> 符號</span></div>
					</div>
					<div class="scheduleItem">
						<div class="chapterTitle" id="chpater-3"><i class="fa fa-bookmark-o"></i> CH3: 堆疊與佇列</div>
						<div class="subChapter" id="chpater-3-1">1-1<span> 複雜度</span></div>
						<div class="subChapter" id="chpater-3-2">1-2<span> 符號</span></div>
						<div class="subChapter" id="chpater-3-3">1-3<span> 符號</span></div>
						<div class="subChapter" id="chpater-3-4">1-4<span> 符號</span></div>
						<div class="subChapter" id="chpater-3-5">1-5<span> 符號</span></div>
					</div>
					<div class="scheduleItem">
						<div class="chapterTitle" id="chpater-4"><i class="fa fa-bookmark-o"></i> CH4: 鏈結</div>
						<div class="subChapter" id="chpater-4-1">1-1<span> 複雜度</span></div>
						<div class="subChapter" id="chpater-4-2">1-2<span> 符號</span></div>
						<div class="subChapter" id="chpater-4-3">1-3<span> 符號</span></div>
						<div class="subChapter" id="chpater-4-4">1-4<span> 符號</span></div>
						<div class="subChapter" id="chpater-4-5">1-5<span> 符號</span></div>
					</div>
					<div class="scheduleItem">
						<div class="chapterTitle" id="chpater-5"><i class="fa fa-bookmark-o"></i> CH5: 樹</div>
						<div class="subChapter" id="chpater-5-1">1-1<span> 複雜度</span></div>
						<div class="subChapter" id="chpater-5-2">1-2<span> 符號</span></div>
						<div class="subChapter" id="chpater-5-3">1-3<span> 符號</span></div>
						<div class="subChapter" id="chpater-5-4">1-4<span> 符號</span></div>
						<div class="subChapter" id="chpater-5-5">1-5<span> 符號</span></div>
					</div>												
				</div>
			</div>
		</div>
		<?php require("footer.php") ?>
		<?php require("js_com.php") ?>
	</body>
</html>