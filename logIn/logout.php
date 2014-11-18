<?php
session_start();
$username = $_SESSION['username'];
require_once '../BD.class.php';
$miDB = new DB();
$sSQL = "UPDATE users SET status = '0' WHERE username = '$username'";
$miDB->ejecutarQuery($sSQL); 


$_SESSION = array();

// Expire the cookies if exists
// if (isset($_COOKIE['id']) && isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
// 		$iTime = time() -(864000);
// 		setcookie('id','',$iTime);
// 		setcookie('user','',$iTime);
// 		setcookie('pass','',$iTime);
// }

//destrying the session
session_destroy();

//doble check security purpost
if (isset($_SESSION['username'])) {
	header('Location: ../signUp/message.php?msg=Error:_logout_Failed');
	exit();
}else{
	//we will change this into our web name
	header('Location: ../index.php');
	exit();
}





 ?>