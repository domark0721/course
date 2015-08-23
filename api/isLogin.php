<!-- 有些頁面需要登入才可以顯示，所以需要做驗證 -->
<?php
	if($Member_NAME!=NULL){

	}
	else{
		Header("Location: /www/course/login.php");
	}
?>