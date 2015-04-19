<?php
	session_start();
	$IS_LOGIN = isLogin();
	$USER_NAME = getUserName();

	//判斷是否登入
	function isLoing(){
		if(isset($_SESSION['isLogin']) && $_SESSION=['isLogin']){
			return true;
		else
			return false;
		}
	}
?>