<?php
	require('course_com.php');
	$contentData = $courseData['content'];
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
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
					<?php
						foreach($contentData['chapters'] as $i => $chapter){
							$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] );
							echo '<li><a href="#chpater-'. $i .'">'. $courseName .'</a></li>';
						}
					?>
				</ul>
			</nav>
			<div class="schedule-wrap">
				<div id="schedule-container">
				<?php
					foreach($contentData['chapters'] as $i => $chapter){
						$courseName = sprintf("CH%d:%s", $i+1, $chapter['name'] );
						echo '<div class="scheduleItem">';
						echo '<div class="chapterTitle" id="chapter-'. $i .'"><i class="fa fa-bookmark-o"></i> '. $courseName .'</div>';

						foreach($chapter['sections'] as $j => $section){
							$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
							$courseURL = sprintf("courseSections.php?course_id=%d&chpater_id=%d&section_id=%d"
													,$courseData['course_id'], $i, $j);
							echo '<div class="subChapter"><a href="'. $courseURL .'">'. $sectionName .'</a></div>';
						}
						echo '</div>';
					}
				?>										
				</div>
			</div>
		</div>
		<?php require("footer.php") ?>
		<?php require("js_com.php") ?>
	</body>
</html>