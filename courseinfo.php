<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
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
		<div class="content-wrap clearfix content-wrap-courseinfo">
			<div id="left-wrap" >
				<div class="asidebox">
					<div class="asideTitle">課程資訊</div>
					<div class="asideContent">
						<table class="infotable">
							<tbody>
								<tr class="infotable-row">
									<th class="">課程編號</th>
									<td>C201508902</td>
								</tr>
								<tr class="infotable-row">
									<th class="">開課時間</th>
									<td>2015年7月30日</td>
								</tr>
								<tr class="infotable-row">
									<th class="">課程語言</th>
									<td>中文</td>
								</tr>
								<tr class="infotable-row">
									<th class="">課程類別</th>
									<td>資訊工程</td>
								</tr>
								<tr class="infotable-row">
									<th class="">學習人次</th>
									<td>13000</td>
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
									<td>蔡馬克</td>
								</tr>
								<tr class="infotable-row">
									<th class="">E-mail</th>
									<td>tds@gaislab.cs.ccu</td>
								</tr>
								<tr class="infotable-row">
									<th class="">個人網站</th>
									<td>marktsai.tw</td>
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
							<p>test<br>test<br>test<br></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>課程大綱</h2>
							<p>test<br>test<br>test<br></p>
						</div>
						<div class="section-header" id="arti-sec1">
							<h2>指定用書</h2>
							<p>test<br>test<br>test<br></p>
						</div>

					</section>
				</div>
			</div>
		</div>
		<?php require("footer.php") ?>
	</body>
</html>