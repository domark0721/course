<?php
	include_once('api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<link type="text/css" rel="stylesheet" href="css/videos.css">
		<title>NUCourse</title>
	</head>

	
	<body>
		<?php require("header.php") ?>
		<div id="videoBar">
			<div class="content-wrap">
				<div id="video">
					<video controls >
					  <source src="videos/small.mp4" type="video/mp4">
					  Your browser does not support the video tag.
					</video>
				</div>
			</div>
		</div>
		<div id="videoBar">
		<?php require("footer.php") ?>
	</body>
</html>