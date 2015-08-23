<!-- mysql連線 -->
<?php
	$con = mysql_connect('127.0.0.1', 'root', '10038') or die("DB connect fault!");
	// if(!$con){ echo "DB connect fault!";}
	// else echo "Connected!";
	mysql_select_db('course', $con);
	mysql_set_charset('utf8', $con);
?>