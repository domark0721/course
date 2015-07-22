<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<link type="text/css" rel="stylesheet" href="css/header_index.css">
		<title>NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header_index.php"); ?>
			<div class="index-container">
				<div class="index-bg">
				</div>
				<div id="section-search">
				       <div class="content-wrap">
						<div id="searchControl">
							<form id="searchForm" method="GET" action="search.php">
								<input id="inputCourse" type="text" name="keyword" placeholder="您想要學習什麼課程？">
								<input class="searchbtn" type="submit" value="搜尋">
							</form>
						</div>
					</div>
				</div>
				<!-- <div id="section-hotcourse" class="content-wrap" style="display:none;">
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
				</div> -->
			</div>
			<footer>
				<div id="section-footer">
					 <div class="content-wrap">
					 	<div id="footerLabel">
						 	<div><a href="https://www.flickr.com/photos/alejandropinto/10671406484">Photo</a> by Alejandro Pinto / CC <a href="https://creativecommons.org/licenses/by/2.0/">BY</a></div>
							<div>&copy 2015 Mark Tsai@GAIS LAB .All Rights Reserved.</div>
						</div>
					 </div>
				</div>
			</footer>
		</div>
	</body>
</html>
