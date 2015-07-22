<?php
	include_once('api/auth.php');
	include_once("mongodb.php");
	include_once("mysql.php");

	$course_id = $_GET['course_id'];
	
	session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
	$member_id = $_SESSION['member_id'];

	//check this course have been saved in mysql
	$countResult = mysql_query("SELECT COUNT(*) as exist FROM attendent WHERE course_id='$course_id' and member_id='$member_id'");
	$existObj = mysql_fetch_assoc($countResult);

	//metadata from mysql
	$sql = "SELECT * FROM course WHERE course_id='$course_id'";
	$result = mysql_query($sql);
	$courseMetadata = mysql_fetch_assoc($result);

	//判斷如果status = 0 或 2時候不可以進入此頁面
	// echo $courseMetadata['status'];

	//course data from mongo
	$mongoQuery = array('course_id' => (int)$course_id);
	$mon = $collection -> find($mongoQuery);

	foreach($mon as $data){
		$courseData = $data;
		break;
	}

	$contentData = $courseData['content'];

	//check course if added favorite
	$sql = "SELECT * FROM favorite WHERE course_id='$course_id' AND member_id ='$member_id'";
	$result = mysql_query($sql);
	$favorite = mysql_fetch_assoc($result);
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
								<img src="<?php echo $courseMetadata['pic']; ?>">
							</div>
							<div id="courseHeader">
								<div id="courseName"><?php echo $courseMetadata['course_name']; ?></div>
								<div id="courseTeacher"><?php echo $courseMetadata['teacher_name']; ?></div>
							</div>
		<?php if($courseMetadata['status'] == 0){ ?>
								<div><a id="closeJoinCourseBtn" class="closeAddCourse"><i class="fa fa-graduation-cap"></i>&nbsp;&nbsp;&nbsp;結束授課</a></div>
		<?php } else { 
					if($existObj['exist'] != 1){ ?>
								<div><a id="joinCourseBtn" class="addCourse" href="joinCourse.php?course_id=<?php echo $course_id;?>"><i class="fa fa-graduation-cap"></i>&nbsp;&nbsp;&nbsp;修習本課程</a></div>
					<?php } else { ?>
								<div><a id="alreadyJoinCourseBtn" class="alreadyAddCourse"><i class="fa fa-graduation-cap"></i>&nbsp;&nbsp;&nbsp;已加入課程</a></div>
					<?php }
				}  ?>
		<?php if($favorite == false){ ?>
						<div><a id="favoriteCourseBtn" class="addCourse"><i class="fa fa-star">&nbsp;&nbsp;&nbsp;</i><span class="favoriteSpan">收藏課程</span></a><img class="loading" src="img/loader.gif"/></div>
		<?php }else{ ?>
						<div><a id="deleteFavoriteBtn" class="alreadyFavorite"><i class="fa fa-star">&nbsp;&nbsp;&nbsp;</i><span class="favoriteSpan">已收藏</span></a><img class="loading" src="img/loader.gif"/></div>
		<?php } ?>
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
											<td><a href="<?php echo $courseMetadata['website']; ?>" target="_blank"><?php echo $courseMetadata['website']; ?></a></td>
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
			<input id="course_id" type="hidden" value="<?php echo $course_id;?>">
			<input id="member_id" type="hidden" value="<?php echo $member_id;?>">
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
		<script src="js/courseIndex.js"></script>
	</body>
</html>