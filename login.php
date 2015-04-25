<?php

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<!-- <link type="text/css" rel="stylesheet" href="css/index.css"> -->
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div class="formCard">
					<form id="loginForm" class="stackForm" action="api/logincheck.php" method="POST">
						<label for="account">帳號</label>
						<input type="text" name="account"><br>
						<label for="password">密碼</label>
						<input type="password" name="password"><br>
						<button class="submitBtn" type="submit" >登入</button>
					</form>
			</div>
		</div>
		<?php require("footer.php") ?>
	</body>
</html>