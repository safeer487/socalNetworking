<?php 
include 'checkLogIn.php';
//if user already logged in then header it away
if ($user_ok == true) {
	header('Location: user.php?u='.	$_SESSION['username']);
	exit();
}

// ajax request deal
if (isset($_POST['emailR'])) {
	//saving variable
	$e = trim($_POST['emailR']);
	
	//checking the email
	if (!strstr($e, '@') || !strstr($e, '.')) {
		echo "emailNotValid";
		exit();
	}
	//checking the email if exists or not
	$sSQL = "SELECT id, username FROM users WHERE email = '$e' AND activated = '1' LIMIT 1";
	$resul = $miDB->obtenerResultado($sSQL);
	if($resul){
		//saving the variables
		$i = $resul[0]['id'];
		$u = $resul[0]['username'];
		
		//generating a random pass
		$emailCut = substr($e, 0,4);
		$randNum = rand(10000,99999);
		$temPass = "$emailCut$randNum";
		
		//hashing them 
		$hashTemPass = md5($temPass);

		//updating the useroptions temp_pass field
		$sSQL = "UPDATE useroptions SET temp_pass ='$hashTemPass' WHERE username ='$u' LIMIT 1";
		$miDB->ejecutarQuery($sSQL);
		
		//email the user pasword
		//we cannot save a variable into anothe variable using single commas ''
		$to = "$e";
		$from = "auto_responder@pruebando.netii.net";
		$headers = "From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type : text/html; charset=iso-8859-1\n";
		$subject = "Web interset temporary password";
		$msg =	'<h2>Hello '.$u.'</h2><p>This is an automated message from yoursite. If you did not recently initiate the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$temPass.'</b></p><p><a href="http://pruebando.netii.net/forgotPass.php?u='.$u.'&p='.$hashTemPass.'">Click here now to apply the temporary password shown below to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
		$msg = trim($msg);
			$mail = mail($to, $subject, $msg, $headers);
			if ($mail) {
				echo "success";
				exit();
			}else{
				echo "emailSentFail";
				exit();
			}
	}else{
		echo "noExists";
	}
	exit();
}

//Email links click will execute this code
if (isset($_GET['u']) && isset($_GET['p'])) {
	$u = preg_replace('#[^a-z0-9]#i','', $_GET['u']);
	$pHash = preg_replace('#[^a-z0-9]#i','', $_GET['p']);
	if (strlen($pHash) < 10) {
		exit();
	}
	$sSQL = "SELECT id FROM useroptions WHERE username ='$u' AND temp_pass = '$pHash' LIMIT 1";
	
	$resul = $miDB->obtenerResultado($sSQL);
	if (!$resul) {
		header('Location: signUp/message.php?msg = There is no match for that username with temporary password ');
		exit();
	}else{
		$id = $resul[0]['id'];
		
		$sSQL = "UPDATE users SET password ='$pHash' WHERE id='$id' AND username ='$u' LIMIT 1 ";
		$miDB->ejecutarQuery($sSQL);

		$sSQL = "UPDATE useroptions SET temp_pass = '' WHERE username = '$u' LIMIT 1 ";
		$miDB->ejecutarQuery($sSQL);

		header('Location: index.php');
		exit();
	}
}

$path = 'views/forgotPass.view.php';
include_once 'views/randem.php';


 ?>