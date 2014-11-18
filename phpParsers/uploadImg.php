<?php 
	require_once '../BD.class.php';
	$miDB = new DB();
	if (isset($_POST['gallery']) && isset($_POST['user']) && isset($_FILES) ) {
		//saving into variables
		$gallery = trim($_POST['gallery']);

		//saving the gallery according to the user providid
		if($gallery == 'general'){
			$gallery = 'general';
		}else if($gallery == 'family'){
			$gallery = 'family';
		}else if($gallery == 'friends'){
			$gallery = 'friends';
		}else if($gallery == 0){
			echo "please select a gallery";
			exit();
		}

		$user = trim($_POST['user']);
		$pName = $_FILES['foto']['name'];
		$pType = $_FILES['foto']['type'];
		$pPath = $_FILES['foto']['tmp_name'];
		$pSize = $_FILES['foto']['size'];


		//validations
		//
		$kaboom = explode(".", $pName);
		$fileExt = end($kaboom);
		$dbFileName = rand(10000000000,99999999999).".".$fileExt;

		if ($pSize > 1048576) {
			echo "Your image size is larger than 1mb";
			exit();
		}else if(!preg_match("/\.(gif|jpg|png)$/i", $pName)){
			echo "Your file is not png, gif or png";
			exit();
		}

		$filePath = "gallery/$dbFileName";

		$moveResul = move_uploaded_file($pPath, "../gallery/$dbFileName");
		if ($moveResul) {
			
		}else{
			echo "its failed";
		}
		$sSQL = "INSERT INTO photogallery VALUES(NULL,'$user','$gallery','$filePath')";
		$miDB->ejecutarQuery($sSQL);
			echo "your image uploaded successfully";		
			exit();



	}








 ?>