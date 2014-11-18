<?php 
	if ($_POST['userN']) {
			$username = $_POST['userN'];
			// if the first letter is a number 
			if (is_numeric($username[0])) {
				echo  "<strong style='color:red'>username should not start with number</strong>";
				exit();
			// if the string length is less than 3 and greater than 16
			}else if(strlen($username) < 3 || strlen($username) >16){
				echo "<strong style='color:red'>3 - 16 caracters</strong>";
				exit();
			}else{		
				require_once '../BD.class.php';
				$miDB = new DB();
				$sSQL = "SELECT username FROM users WHERE username = '$username'";
				$resul = $miDB->obtenerResultado($sSQL);
				if ($resul) {
				// If exists results
				echo   "<strong style='color:red'>".$username ." already taken</strong> ";
				exit();
				}else{
				// If doesnot exists results
				echo  $username ." is ok";
				exit();
				}
			}	
	}

 ?>