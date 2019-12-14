<?php
session_start();

if(isset($_SESSION['expired'])&&$_SESSION['expired'] === true){
	$_SESSION = array();
	$_SESSION["error"]="Session expired.";
	 
	header("location: login.php");
	exit;
}

 $_SESSION = array();
 session_destroy();
 
header("location: login.php");
exit;
?>