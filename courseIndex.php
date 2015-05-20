<?php
	include_once("mongodb.php");
	include_once("mysql.php");

	$course_id = $_GET['course_id'];
	
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
	}

	$contentData = $courseData['content'];
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/mode.css">
		<link type="text/css" rel="stylesheet" href="css/course.css">
		<link type="text/css" rel="stylesheet" href="css/courseIndex.css">
		<title><?php echo $courseMetadata['course_name']; ?> 課程入口 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div id="courseBar">
					<div id="courseBarPosition" class="clearfix">
						<div class="content-wrap">
							<div id="coursePic">
								<img src="img/user-course.jpg">
							</div>
							<div id="courseHeader">
								<div id="courseName"><?php echo $courseMetadata['course_name']; ?></div>
								<div id="courseTeacher"><?php echo $courseMetadata['teacher_name']; ?></div>
							</div>
							<div><a id="joinCourseBtn" class="addCourse" href="joinCourse.php?course_id=<?php echo $course_id;?>"><i class="fa fa-graduation-cap"></i>&nbsp;&nbsp;&nbsp;修習本課程</a></div>
							<div><a id="favoriteCourseBtn" class="addCourse"><i class="fa fa-star"></i>&nbsp;&nbsp;&nbsp;收藏課程</a></div>
						</div>				
					</div>
				</div>

				<div id="courseInfo" class="content-wrap clearfix content-wrap-courseinfo">
					<div id="left-wrap" >
						<div class="asidebox">
							<div class="asideTitle">課程資訊</div>
							<div class="asideContent">
								<table class="infotable">
									<tbody>
										<tr class="infotable-row">
											<th class="">課程編號</th>
											<td><?php echo $courseMetadata['course_id']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">開課時間</th>
											<td><?php echo date("Y-m-d" ,strtotime($courseMetadata['start_time'])); ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">課程語言</th>
											<td><?php echo $courseMetadata['lang']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">課程類別</th>
											<td><?php echo $courseMetadata['type']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">學習人次</th>
											<td><?php echo $courseMetadata['student_num']; ?></td>
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
											<td><?php echo $courseMetadata['teacher_name']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">E-mail</th>
											<td><?php echo $courseMetadata['teacher_mail']; ?></td>
										</tr>
										<tr class="infotable-row">
											<th class="">個人網站</th>
											<td><?php echo $courseMetadata['website']; ?></td>
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
									<p><?php echo $courseData['description']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>授課大綱</h2>
									<p><?php echo $courseData['syllabus']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>上課形式</h2>
									<p><?php echo $courseData['teachingMethods']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>指定用書</h2>
									<p><?php echo $courseData['textbooks']; ?></p>
								</div>
								<div class="section-header" id="arti-sec1">
									<h2>參考資料</h2>
									<p><?php echo $courseData['references']; ?></p>
								</div>

							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require("footer.php"); ?>
		<?php require("js/js_com.php"); ?>
		<!-- <script src="js/switch.js"></script> -->
	</body>
</html>