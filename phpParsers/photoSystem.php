	<?php 
	require_once '../checkLogIn.php';
	if ($log_user == '' || $log_user != true) {
		exit();
	}


	if (isset($_FILES['avatar']['name']) || isset($_FILES['avatar']['tmp_name'])) {
		
		//saving variables
		$fName = $_FILES['avatar']['name'];
		$fType = $_FILES['avatar']['type'];
		$fTemp = $_FILES['avatar']['tmp_name'];
		$fSize = $_FILES['avatar']['size'];
	
		//saving the ending into file extension
		$kaboom = explode(".", $fName);
		$fileExt = end($kaboom);
		$dbFileName = rand(10000000000,99999999999).".".$fileExt;


		//validations
		if ($fSize > 1048576) {
			echo "Your image size is larger than 1mb";
			exit();
		}else if(!preg_match("/\.(gif|jpg|png)$/i", $fName)){
			echo "Your file is not png, gif or png ";
			exit();
		}

		$filePath = "user/$log_user/$dbFileName";

		//sql for obtaininng the avatar
		$sSQL = "SELECT avatar FROM users WHERE username = '$log_user' LIMIT 1";
		$resul = $miDB->obtenerResultado($sSQL);
		if ($resul) {
			$avatar = $resul[0]['avatar'];
			if ($avatar != NULL) {
				$pic1 = "../".$avatar;
				if (file($pic1)) {
					unlink($pic1);
					
				}
			}
			

			$moveResul = move_uploaded_file($fTemp, "../user/$log_user/$dbFileName");
			
			if ($moveResul == true) {
				$sSQL = "UPDATE users SET avatar = '$filePath' WHERE username = '$log_user' LIMIT 1";

				$miDB->ejecutarQuery($sSQL);
				echo "your picture changed sucessfully";
				exit();
				
			}else{
				header('Location: ../signUp/message.php?msg=Error:file upload fail');
				exit();
			}
		}
	}



 ?>
