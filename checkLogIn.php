<?php 
session_start();
//This page goes to user.php
require_once 'BD.class.php';
$miDB = new DB();
$user_ok = false;
$log_id = "";
$log_user = "";
$log_pass = "";

//user Verify function
function evalLoggedUser($conn,$id,$user,$pass){
	$sSQL = "SELECT ip FROM users WHERE id='$id' AND username = '$user' AND password = '$pass' AND activated ='1' LIMIT 1 " ;
	$resul = $conn->contarResultadosQuery($sSQL);
	if ($resul > 0) {
		return true;
	}
}

//This is for checking if user logged in
if (isset($_SESSION['userid']) && isset($_SESSION['username']) && isset($_SESSION['pass'])) {	
	$log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
	$log_user = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
	$log_pass = preg_replace('#[^a-z0-9]#i', '', $_SESSION['pass']);
	$user_ok = evalLoggedUser($miDB,$log_id,$log_user,$log_pass);	
}



 ?>