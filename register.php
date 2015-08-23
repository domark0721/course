<!-- 註冊會員頁面 -->
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<!-- <link type="text/css" rel="stylesheet" href="css/index.css"> -->
		<link type="text/css" rel="stylesheet" href="css/login.css">
		<title>註冊 - NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>
			<div class="container">
				<div class="content-wrap">
					<div class="formCard">
						<form id="registerForm" class="stackForm">
							<div class="hidetag">
							<label for="name">姓名</label>
								<input id="userName" type="text" name="name"><br>
							<label for="account">新帳號</label>
								<input id="account" type="text" name="account"><br>
							<label for="password">新密碼</label>
								<input id="npwd" type="password" name="password"><br>
							<label for="password_check">確認密碼</label>
								<input id="checkpwd" type="password" name="password_check"><br>
							<div class="loginStatus"> </div>
							<button class="registerBtn" type="submit" >註冊</button>
							</div>
							<div class="registerStatus"> </div>
							<div class="spinner_wrap" style="display:none;">
								<div class="spinner">
									<div class="bounce1"></div>
									<div class="bounce2"></div>
									<div class="bounce3"></div>
								</div>
							</div>	
						</form>					
					</div>
				</div>
			</div>
			<?php require("footer.php"); ?>
		</div>
		<?php require("js/js_com.php"); ?>
		<script type="text/javaScript" src="js/login_register.js"></script>
	</body>
</html>