<?php 
session_start();

//repair 
	// if user already loginned then we just redirect him
	// if (isset($_SESSION['username'])) {
	//  	header("Location: ../user.php?u=".$_SESSION['username']);
	// 	exit();
	// }

	//if exists post
	if ($_POST) {
		require_once '../BD.class.php';
		$miDB = new DB();
		//getting the user ip address
		$ip = preg_replace('#[^0-9.]#','', getenv('REMOTE_ADDR'));
		$e = trim($_POST['eLog']);
		$p = md5($_POST['pLog']);

		
		//validations
		if ($e == '' || $p == '') {
			echo "login_fail";
			exit();	
		}else{
			//picking necessary data
			$sSQL = "SELECT id,username,password FROM users WHERE email ='$e' AND password = '$p' AND activated = '1' LIMIT 1";
			$resul = $miDB->obtenerResultado($sSQL);			
			//if no exist then
				if (!$resul) {
					echo "login_fail";
					exit();
				}else{
					//saving the variables
					$dbId = $resul[0]['id'];
					$dbUsername = $resul[0]['username'];
					$dbPass = $resul[0]['password'];

					//setting the session
					$_SESSION['userid'] = $dbId;
					$_SESSION['username'] = $dbUsername;
					$_SESSION['pass'] = $dbPass;
					

				//Para futuro				
					//setting the cookie for one day
					// $iTime =   time() + (60* 60 * 24 * 30) ;
					// setcookie('id', $dbId,$iTime);
					// setcookie('user', $dbUsername,$iTime);
					// setcookie('pass', $dbPass,$iTime);
					
					// update the ip and the last login fields
					$sSQL = "UPDATE users SET ip = '$ip',lastlogin =now() WHERE username = '$dbUsername' LIMIT 1";	
					$miDB->ejecutarQuery($sSQL);
					$sSQL = "UPDATE users SET status = '1' WHERE username = '$dbUsername' LIMIT 1";
					$miDB->ejecutarQuery($sSQL);
					echo $dbUsername;
				}
		}
	}


 ?>