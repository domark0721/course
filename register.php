<?php

?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div class="formCard">
					<form id="registerForm" class="stackForm" action="api/registercheck.php" method="POST" >
						<label for="name">姓名</label>
						<input type="text" name="name" required><br>
						<label for="account">新帳號</label>
						<input type="text" name="account" required><br>
						<label for="password">新密碼</label>
						<input type="password" name="password" required><br>
						<label for="password_check">確認密碼</label>
						<input type="password" name="password_check" required><br>
						<button class="registerBtn" type="submit" >註冊</button>
						<div class="loginMsg"></div>
					</form>
			</div>
		</div>
		<?php require("footer.php") ?>
		<script type="text/javascript">

		</script>
	</body>
</html>