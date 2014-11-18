<?php 

require_once '../BD.class.php';
//This code block all of the account that does not activate after 3 days
$miDB = new DB();
$sSQL = "SELECT id,username FROM users WHERE signup <=CURRENT_DATE - INTERVAL 3 DAY AND activated = '0'";
$resul = $miDB->obtenerResultado($sSQL);
if($resul){
	foreach ($resul as $key => $value) {
		$id 	  =	$value['id'];
		$username = $value['username'];
		$userFolder = "../user/$username";
		if (is_dir($userFolder)) {
			rmdir($userFolder);
		}
		
		$sSQLm = "DELETE FROM users WHERE id = '$id' AND username = '$username' AND activated = '0' LIMIT 1";
		$miDB->ejecutarQuery($sSQLm);
		$sSQLo = "DELETE FROM usersoptions WHERE username = '$username' LIMIT 1 ";
		$miDB->ejecutarQuery($sSQLo);
		 
	}

}


 ?>