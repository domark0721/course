<!-- 加入課程成功後跳轉 -->
<?php
	include_once('api/auth.php');
	$course_id = $_GET['course_id'];
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link type="text/css" rel="stylesheet" href="css/joinSuccess.css">
		<title>NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container content-wrap">
				<div class="message">
					<span>加入課程成功！</span>
					<span>網頁跳轉中</span>
				</div>
				<div class="spinner_wrap" style="display:;">
					<div class="spinner">
					  <div class="bounce1"></div>
					  <div class="bounce2"></div>
					  <div class="bounce3"></div>
					</div>
				</div>
			</div>
			<?php require("footer.php"); ?>
			<input id="course_id" type="hidden" value="<?php echo $course_id;?>">
		</div>
		<?php require("js/js_com.php"); ?>
		<script type="text/javascript" src="js/joinSuccess.js"></script>
	</body>
</html>