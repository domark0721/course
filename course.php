<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");
	$course_id = $_GET['course_id'];

	if($Member_NAME!=NULL){

	}
	else{
		Header("Location: login.php");
	}
	
	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);

	$courseMetadata_temp = array();
	while($row = mysql_fetch_assoc($result)){
		$courseMetadata_temp[] = $row;
	}	

	foreach($courseMetadata_temp as $courseMetadata){

	}
	
	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
		// var_dump($courseData);
	}

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
					<div id="courseHeader">
						<div id="courseName"><?php echo $courseMetadata['course_name'] ?></div>
						<div id="courseTeacher"><?php echo $courseMetadata['teacher_name'] ?></div>
					</div>
				</div>				
			</div>
		</div>
		<div class="nav-wrap">
			<div class="userControl">
				<ul class="tab-list">
					<li><a href="#announceList">公告事項</a></li>
					<li><a href="#courseInfo">本課資訊</a></li>
					<li><a href="#courseSchedule">開始上課</a></li>
					<!-- <li><a>互動與討論</a></li> -->
				</ul>
			</div>
		</div>
		<div id="announceList" class="tab-content announce-wrap">
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

		<div id="courseInfo" class="tab-content content-wrap clearfix content-wrap-courseinfo">
			<div id="left-wrap" >
				<div class="asidebox">
					<div class="asideTitle">課程資訊</div>
					<div class="asideContent">
						<table class="infotable">
							<tbody>
								<tr class="infotable-row">
									<th class="">課程編號</th>
									<td><?php echo $courseMetadata['course_id'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">開課時間</th>
									<td><?php echo date("Y-m-d" ,strtotime($courseMetadata['start_time'])) ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">課程語言</th>
									<td><?php echo $courseMetadata['lang'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">課程類別</th>
									<td><?php echo $courseMetadata['type'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">學習人次</th>
									<td><?php echo $courseMetadata['student_num'] ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="asidebox">
					<div class="asideTitle">老師資訊</div>
					<div class="asideContent">
						<table class="infotable">
							<tbody>
								<tr class="infotable-row">
									<th class="">老師姓名</th>
									<td><?php echo $courseMetadata['teacher_name'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">E-mail</th>
									<td><?php echo $courseMetadata['teacher_mail'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">個人網站</th>
									<td><?php echo $courseMetadata['website'] ?></td>
								</tr>
								<tr class="infotable-row">
									<th class="">其他</th>
									<td>marktsai.tw</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div id="right-wrap">
				<div class="courseIntro">
					<section class="article-section">
						<div class="section-header" id="arti-sec1">
							<h2>課程概述</h2>
							<p><?php echo $courseData['description'] ?></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>授課大綱</h2>
							<p><?php echo $courseData['syllabus'] ?></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>上課形式</h2>
							<p><?php echo $courseData['teachingMethods'] ?></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>指定用書</h2>
							<p><?php echo $courseData['textbooks'] ?></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>參考資料</h2>
							<p><?php echo $courseData['references'] ?></p>
						</div>

					</section>
				</div>
			</div>
		</div>
		<div id="courseSchedule" class="tab-content content-wrap clearfix content-wrap-schedule">
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
						$courseName = sprintf("CH%d: %s", $i+1, $chapter['name'] );
						echo '<div class="scheduleItem">';
						echo '<div class="chapterTitle" id="chapter-'. $i .'"><i class="fa fa-bookmark-o"></i> '. $courseName .'</div>';

						foreach($chapter['sections'] as $j => $section){
							$sectionName = sprintf("%d-%d %s", $i+1, $j+1, $section['name']);
							$courseURL = sprintf("courseSections.php?course_id=%d&chapter_id=%d&section_id=%d"
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
		<?php require("js/js_com.php") ?>
		<script src="js/switch.js"></script>
	</body>
</html>