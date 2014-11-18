<?php 
if ($_POST) {
	//including db
	require_once '../BD.class.php';
	$miDB = new DB();
	//saving them into variables
	//preg_replace will only accepts the pattern required
	$u = preg_replace('#[^a-z0-9]#i','', $_POST['userN']);
	$e = $_POST['email'];
	$p = $_POST['pass'];
	$b = $_POST['birthday_year'].'-'.$_POST['birthday_month'].'-'.$_POST['birthday_day'];
	//replace anything exepts alphabets from a to z
	$g = preg_replace('#[^a-z]#','', $_POST['gender'][0]);
	//getting ip address of the user
	$ip = preg_replace('#[^0-9.]#','', getenv('REMOTE_ADDR'));

	//Checking the duplicate data for username and mail
	$sSQL = "SELECT id FROM users WHERE username='$u' LIMIT 1";
	$uResul = $miDB->obtenerResultado($sSQL);
	/* mail */
	$sSQL = "SELECT id FROM users WHERE email = '$e' LIMIT 1";
	$eResul =  $miDB->obtenerResultado($sSQL);
	
	//ERROR HANDLING
	if ($u == '' || $e == '' || $p == '' || $b == '') {
		echo "Please fill all the filels";
		exit();
	}else if($uResul){
		echo "Username already taken please choose other";
		exit();
	}else if($eResul){
		echo "Email already registered please choose other";
		exit();
	}else if(strlen($u) < 3 || strlen($u) > 16){
		echo "Username must be 3 to 16 caracters";
		exit();
	}else if(is_numeric($u[0])){
		echo "First letter cannot be a number";
		exit();
	}else{
		//encript your code
		$mPass = md5($p);

		//add userinfo into the database for main site
		$sSQL = "INSERT INTO users(username,email,password,gender,birthDate,ip,signup,lastlogin,notescheck)
				VALUES('$u','$e','$mPass','$g','$b','$ip',now(),now(),now())";
		$resul = $miDB->ejecutarQuery($sSQL);

		//getting the inserted user id
		$sSQL = "SELECT id FROM users WHERE username='$u' LIMIT 1";
		$resul = $miDB->obtenerResultado($sSQL);
		$uid = $resul[0]['id'];
		
		//Establish their row in the useroptions table
		$sSQL = "INSERT INTO useroptions(id, username,background) VALUES('$uid','$u','original')";
		$miDB->ejecutarQuery($sSQL);

		//create directroy(folder) to hold each users file(pics, mp3 etc.)
		//A folder will be created with his name.
		if (!file_exists("../user/$u")) {
			mkdir("../user/$u",' 0755');
		}
		

		//Email the user the activation link
		$to = "$e";
		$from = "auto_responder@pruebando.netii.net";
		$subject = 'Chatty Account Activation';
		$messege =<<<EOT
			<!DOCTYPE html>
			<html lang="en">

			<head>
			<meta charset="utf-8">
			<title>Chatty messege</title>
			<meta name="description" content="A">
			<meta name="author" content="Safeer mehmood">

			<!-- Mobile Specific Meta -->
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

			<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

			<!-- Optional theme -->
			<!-- <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css"> -->

			<style>
				
				span.chatty{
					color:gray;
					font-weight: bold;
					font-size:25px;
				}
				

			</style>
					</head>
					<body>
			<header>		
				<div class="well">		
					<span class="chatty">Chatty</span>
				</div>
			</header>
			<div class="container">
				<h2>Hello $u</h2>
				<h2>Chatty Account Activation</h2>
				<h4>Click on the link below to activate your account</h4>
				<a href="http://www.pruebando.netii.net/signUp/activation.php?id={$uid}&u={$u}&e={$e}&p={$mPass}">
				Click here to activate your account now
				</a>
				<p><b>log in after activation using your email and password Thanks</b></p>
				
			</div>

					<!-- Latest compiled and minified JavaScript -->
			<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
			<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>	
				</body>
				</html>


EOT;
		$messege = trim($messege);
		$headers = "From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type : text/html; charset=iso-8859-1\n";
		$mail = mail($to, $subject, $messege,$headers);
		if ($mail) {
			echo "signup_success";
			exit();
		}else{
			echo "there is a problem signing up";
		}
	}
}





 ?>