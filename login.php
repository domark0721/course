<?php
	session_start();
	if(isset($_SESSION['isLogin']) && $_SESSION['isLogin']){
		if(isset($_SESSION['url']))
			$url = $_SESSION['url'];
		else
			$url = "stmode.php";

		Header("Location: $url");
	}
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<!-- <link type="text/css" rel="stylesheet" href="css/index.css"> -->
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>登入 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div class="content-wrap">
					<form id="loginForm" class="stackForm" action="api/loginCheck.php" method="POST">
						<label for="account" required>帳號</label>
						<input type="text" name="account"><br>
						<label for="password" required>密碼</label>
						<input type="password" name="password"><br>
						<button class="submitBtn" type="submit" >登入</button>
					</form>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		
	</body>
</html>