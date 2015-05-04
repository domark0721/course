<?php
	include_once('api/auth.php');
	include_once("mongodb.php");

	$course_id = $_GET['course_id'];
	$chapter = $_GET['chapter_id'];
	$section = $_GET['section_id'];
	// var_dump($chapter);
	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}
	$sectionData = $courseData['content']['chapters'];
	
	$goalChapter = $sectionData[$chapter]['sections'][$section];

	// var_dump($goalChapter['name']);
	// exit;

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
					<div id="leftChapter">
					<?php
						if ($chapter == 0 && $section == 0){
							echo '<a href="course.php#courseSchedule"><i class="fa fa-arrow-left"></i> 回課程首頁</a>';
						}
						else if ($chapter > 0 && $section == 0) {
							$preChapter_LastSection = count($sectionData[($chapter-1)]['sections']);
							$preChapter_LastSection_Name = $sectionData[($chapter-1)]['sections'][($preChapter_LastSection-1)]['name'];
							$preFullName = sprintf("%d-%d %s", $chapter, $preChapter_LastSection , $preChapter_LastSection_Name);
							echo '<a href="courseSections.php?course_id=' .$course_id. '&chapter_id=' .($chapter-1). '&section_id='.($preChapter_LastSection-1).'"><i class="fa fa-arrow-left"></i> ' .$preFullName. '</a>';
						}
						else if($chapter >= 0 && $section > 0){
							$preSectionName = $sectionData[$chapter]['sections'][($section-1)]['name'];
							$preFullName = sprintf("%d-%d %s", ($chapter+1), $section ,$preSectionName);
							echo '<a href="courseSections.php?course_id=' .$course_id. '&chapter_id=' .$chapter. '&section_id='.($section-1).'"><i class="fa fa-arrow-left"></i> ' .$preFullName. '</a>';
						}
					?>
					</div>
					<div id="rightChapter">
					<?php
						$lastChpater = count($sectionData);
						$lastChpater_lastSection = count($sectionData[($lastChpater-1)]['sections']);
						$currentMaxSecsstion = count($sectionData[$chapter]['sections'])-1; //have been changed to index number
						// var_dump($currentMaxSecsstion);

						if($chapter == ($lastChpater-1) && $section == ($lastChpater_lastSection-1)){
							echo '<a href="course.php#courseSchedule">回課程首頁 <i class="fa fa-arrow-right"></i></a>';
						}
						else if($section < $currentMaxSecsstion){
							$nextSectionName = $sectionData[$chapter]['sections'][($section+1)]['name'];
							$nextFullName = sprintf("%d-%d %s", ($chapter+1), ($section+2), $nextSectionName);
							echo '<a href="courseSections.php?course_id=' .$course_id. '&chapter_id=' .$chapter. '&section_id=' .($section+1). '">'.$nextFullName .' <i class="fa fa-arrow-right"></i></a>';
						}else if($section == $currentMaxSecsstion){
							$nextSectionName = $sectionData[($chapter+1)]['sections'][0]['name'];
							$nextFullName = sprintf("%d-1 %s", ($chapter+2) , $nextSectionName);
							echo '<a href="courseSections.php?course_id=' .$course_id. '&chapter_id=' .($chapter+1). '&section_id=0">'.$nextFullName .' <i class="fa fa-arrow-right"></i></a>';
						}
					?>
					</div>
					<div id="thisChapter"><?php echo ($chapter+1) . "-" . ($section+1) . " " . $goalChapter['name']; ?></div>
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
				<ul class="tab-list">
					<li><a href="#">文字教材</a></li>
					<li><a href="#">隨堂練習</a></li>
					<li><a href="#">互動與討論</a></li>
					<li><a href="#">課程選單</a></li>
				</ul>
			</div>
		</div>
		<div id="courseContent-wrap" class="display-wrap displayAttribute">
			<div id="courseContent"><?php echo $goalChapter['content']; ?></div>
		</div>
	
		<?php require("footer.php") ?>
	</body>
</html>