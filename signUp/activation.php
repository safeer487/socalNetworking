<?php
//If these values are set then only we gothrough the process 
if (isset($_GET['id']) && isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p'])) {
	require_once '../BD.class.php';
	$miDB = new DB();
	$id = preg_replace('#[^0-9]#i','', $_GET['id']);
	$u  = preg_replace('#[^a-z0-9]#i','',$_GET['u']);
	$e  = trim($_GET['e']);
	$p  = trim($_GET['p']);

	//check the record against the database
	$sSQL = "SELECT * FROM users WHERE id = '$id' AND username = '$u' AND email='$e' AND password='$p' LIMIT 1";
	
	$resul = $miDB->contarResultadosQuery($sSQL);
	//evaluate the match
	if ($resul == 0) {
		header('Location: message.php?msg=You credentials are not matching in our system');
		exit();
	}
	//match found you can activate them
	$sSQL = "UPDATE users SET activated='1' WHERE id='$id' LIMIT 1";
	$miDB->ejecutarQuery($sSQL);

	//optional double check to see if acivated infact now = 1
	$sSQL = "SELECT * FROM users WHERE id = '$id' AND activated = '1' LIMIT 1";
	$resul = $miDB->contarResultadosQuery($sSQL);

	//evaluate doble check
	if ($resul == 0) {
		header('Location: messege.php?msg=acivatioin_failure');
		exit();
	}else if($resul == 1){
		header('Location: message.php?msg=activation_success');
		exit();
	}

}else{
	//issue of missing get variables	
	header('Location: messege.php?msg=missing_Get_variables');
	exit();
}

 ?>