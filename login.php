<?php

?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
		<!-- <link type="text/css" rel="stylesheet" href="css/index.css"> -->
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div class="formCard">
					<form id="loginForm" class="stackForm" action="api/logincheck.php" method="POST">
						<label for="username">帳號</label>
						<input type="text" name="username"><br>
						<label for="userpwd">密碼</label>
						<input type="password" name="userpwd"><br>
						<button class="submitBtn" type="submit" >登入</button>
					</form>
			</div>
		</div>
		<?php require("footer.php") ?>
	</body>
</html>