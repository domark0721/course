<?php
	session_start();
	if($_SESSION['mode'] == "st"){
		$_SESSION['mode'] = "te";
		Header("Location: ../temode.php");
	}
	else if($_SESSION['mode'] == "te"){
		$_SESSION['mode'] = "st";
		Header("Location: ../stmode.php");
	}

	// var_dump($_SESSION['mode']);
	
?>