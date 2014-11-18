<?php 
//for doble check so the user can not hack or change our content
require_once("../checkLogIn.php");
if ($user_ok != true || $log_user == '') {
	exit();
}
//if exists the post
if (isset($_POST['type']) && isset($_POST['blocke']) && $_POST['type'] == 'block') {
	// saving the variable
	$blocke = preg_replace('#[^a-z0-9]#i','', $_POST['blocke']);
	//checking the person who which we want to block is exists or not in the database if not then exit
	$sSQL = "SELECT id FROM users WHERE username='$blocke' AND activated='1' LIMIT 1";	
	$resul = $miDB->contarResultadosQuery($sSQL);
	if ($resul < 1) {
		echo "$blocke does not exists";
		exit();
	}
	//checking if the user already blogged by the user
	$sSQL = "SELECT id FROM blockedusers WHERE blocker = '$log_user' AND blockee = '$blocke' LIMIT 1";
	$resul = $miDB->contarResultadosQuery($sSQL);
	
	if ($resul > 0) {
		echo "You already have this member blocked";
		exit();
	}else{
		//if not blocked then we just insert
		$sSQL = "INSERT INTO blockedusers(blocker,blockee,blockdate) VALUES('$log_user','$blocke',now()) ";
		$miDB->ejecutarQuery($sSQL);
		echo "blocked_ok";
		exit();
	}
	
}else if($_POST['type'] == 'unBlock'){
	$blocke = preg_replace('#[^a-z0-9]#i','', $_POST['blocke']);
	$sSQL = "SELECT id FROM blockedusers WHERE blocker = '$log_user' AND blockee = '$blocke' LIMIT 1";
	
	$resul = $miDB->contarResultadosQuery($sSQL);	
	if ($resul == 0) {
		echo "you have already blocked this user";
	}else{
		$sSQL = "DELETE FROM blockedusers WHERE blocker = '$log_user' AND blockee = '$blocke' LIMIT 1";
		$miDB->ejecutarQuery($sSQL);
		echo "unblocked_ok";
		exit();
		}
	}
	


 ?>