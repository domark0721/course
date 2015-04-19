<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/stmode.css">
		<title>NUCourse</title>
	</head>

	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div id="role"><?php echo $Member_NAME ?> 同學您好！</div>
			<div class="userControl">
				<ul>
					<li>目前課程</li>
					<li>修畢課程</li>
					<li>收藏課程</li>
					<li>互動討論</li>
				</ul>
			</div>

			<div id="courseList">
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name">資料結構</div>
						<div class="item-course-teacher">授課老師：Amy Wang</div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:50%"></div>
							</div>
							<span>50%</span>
						</div>
					</div>
					<div class="itemRight"><a href="course.php">進入課程</a></div>
				</div>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name">資料結構</div>
						<div class="item-course-teacher">授課老師：Amy Wang</div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:50%"></div>
							</div>
							<span>50%</span>
						</div>
					</div>
					<div class="itemRight"><a href="#">進入課程</a></div>
				</div>
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name">資料結構</div>
						<div class="item-course-teacher">授課老師：Amy Wang</div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:50%"></div>
							</div>
							<span>50%</span>
						</div>
					</div>
					<div class="itemRight"><a href="#">進入課程</a></div>
				</div>	
				<div class="courseItem clearfix">
					<div class="itemLeft"><img src="img/user-course.jpg"></div>
					<div class="item-course-info"> 
						<div class="item-course-name">資料結構</div>
						<div class="item-course-teacher">授課老師：Amy Wang</div>
						<div class="item-course-status">
							<div class="item-course-status-container">
								<div class="status" style="width:50%"></div>
							</div>
							<span>50%</span>
						</div>
					</div>
					<div class="itemRight"><a href="#">進入課程</a></div>
				</div>			
			</div>

			
		</div>
		<?php require("footer.php") ?>
	</body>
</html>