<?php require("css_com.php") ?>
<?php
	$con = mysql_connect('localhost', 'root', '10038') or die("DB connect fault!");
	// if(!$con){ echo "DB connect fault!";}
	// else echo "Connected!";
	mysql_select_db('course', $con);
	mysql_set_charset('utf8', $con);
?>