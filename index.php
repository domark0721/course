<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div id="section-search">
		       <div class="content-wrap">
				<div id="searchControl">
					<form id="searchForm"method="GET" action="">
						<input id="inputCourse" type="text" placeholder="您想要學習什麼課程？">
						<input class="searchbtn" type="submit" value="搜尋">
					</form>
				</div>
			</div>
		</div>
			<div id="section-hotcourse" class="content-wrap">
				<div class="indexLabel">熱門課程</div>
					<div id="courselist-container">
						<ul id="courselist">
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>	
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>
							<li class="courseItem">
								<div class="courseCard">
									<div class="courseImg"><img src="img/Big-Data.png"></div>
									<div class="courseName"><i class="fa fa-book"></i> 資料結構</div>
								</div>
							</li>
						</ul>
					</div>
			</div>
		<div>
		<?php require("footer.php") ?>
	</body>
</html>
