<?php

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
						<button class="searchbtn">搜尋</button>
					</form>
				</div>
			</div>
		</div>
		<div id="section-hotcourse">
			<div class="content-wrap">
				<div class="indexLabel">熱門課程</div>
					<div id="classlist-container">
						<ul>
							<li></li>
						</ul>
					</div>
			</div>
		</div>
	</body>
</html>
