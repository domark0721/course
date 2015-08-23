<!-- 使用者登入頁 -->
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
					<form id="loginForm" class="stackForm">
						<label for="account" required>帳號</label>
							<input id="account" type="text" name="account"><br>
						<label for="password" required>密碼</label>
							<input id="password" type="password" name="password"><br>
						<div class="loginStatus"> </div>
						<button class="submitBtn">登入</button>
					</form>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
	</body>
	<?php require("js/js_com.php"); ?>
	<script type="text/javaScript" src="js/login_register.js"></script>
</html>