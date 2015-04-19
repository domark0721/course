<?php

?>
<!doctype html>
<html>
	<head>
		<?php require("css_com.php") ?>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>NUCourse</title>
	</head>
	<body>
		<?php require("header.php") ?>
		<div class="content-wrap">
			<div class="formCard">
					<form id="registerForm" class="stackForm" action="api/registercheck.php" method="POST" >
						<label for="userTrueName">姓名</label>
						<input type="text" name="membername"><br>
						<label for="username">新帳號</label>
						<input type="text" name="memberid"><br>
						<label for="userpwd">新密碼</label>
						<input type="password" name="userpwd"><br>
						<label for="userpwdCheck">確認密碼</label>
						<input type="password" name="userpwdCheck"><br>
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