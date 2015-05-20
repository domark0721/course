<?php
	ini_set('default_charset','utf-8');
	session_start();
	$IS_LOGIN = isLogin();
	$Member_NAME = getMemberName();
	
	//判斷是否登入
	function isLogin(){
		if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'])
			return true;
		else
			return false;
	}

	//判斷已登入後，抓取使用者姓名
	function getMemberName(){
		if(isset($_SESSION['isLogin']) && isset($_SESSION['account']) && isset($_SESSION['name']))
			return $_SESSION['name'];
		else
			return null;
	}


?>