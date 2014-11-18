<?php 
	require_once 'checkLogIn.php';	

	if (isset($_GET['u'])) {
		$user = preg_replace('#[^a-z0-9]#', '', $_GET['u']);
		$noPhotoGal = 0;


			//for picking from the database the general ones
			$sSQL = "SELECT imgPath FROM photogallery WHERE user='$user' AND catagory='general' ORDER BY RAND()";
			$resul = $miDB->obtenerResultado($sSQL);
			if ($resul) {
				$numGenPic = count($resul);
				$genPath = $resul[0]['imgPath'];
				$genImg = <<<EOT
					<li class="imagess"><a href=""><img src='$genPath' alt='' width='140px' height='100px'></a>		
					<p style="text-indent:40px"><span class="bold">General</span>($numGenPic)</p>
					<input type="hidden" value="$user">
					</li>
EOT;
			}else{
				$noPhotoGal += 1;
			}

			//for picking from the database the general ones
			$sSQL = "SELECT imgPath FROM photogallery WHERE user='$user' AND catagory='friends' ORDER BY RAND()";
			$resul = $miDB->obtenerResultado($sSQL);
			if ($resul) {
				$numFriPic = count($resul);
				$friPath = $resul[0]['imgPath'];
				$friImg = <<<EOT
					<li class="imagess"><a href=""><img src='$friPath' alt='' width='140px' height='100px'></a>			
					<p style="text-indent:40px"><span class="bold">Friends</span>($numFriPic)</p>
					<input type="hidden" value="$user">
					</li>
EOT;
			}else{
				$noPhotoGal += 1;
			}

			//for picking from the database the general ones
			$sSQL = "SELECT imgPath FROM photogallery WHERE user='$user' AND catagory='family' ORDER BY RAND()";
			$resul = $miDB->obtenerResultado($sSQL);
			if ($resul) {
				$numFamPic = count($resul);
				$famPath = $resul[0]['imgPath'];
				$famImg = <<<EOT
					<li class="imagess"><a href=""><img src='$famPath' alt='' width='140px' height='100px'></a>			
					<p style="text-indent:40px"><span class="bold">Family</span>($numFamPic)</p>
					<input type="hidden" value="$user">	
					</li>
EOT;
			}else{
				$noPhotoGal += 1;
			}
			
			

		

		//check if the user logged in then give it upload form <button type=""></button>
		if($user_ok == true && $log_user == true){
			$logHtml = <<<EOT
			<div class="well">
				<span class="btn btn-primary btn-block btn-xs">hi $log_user,add a new photo into your galleries</span> <br><br>				
				<form enctype="multipart/from-data" id="fotoUp" method="post" action="phpParsers/photoSystem.php">
					
					<label for="gallery">Choose gallery:</label>	
					<select id="gallery" name="gallery"	>
						<option value="0"></option>
						<option value="general">general</option>
						<option value="friends">friends</option>
						<option value="family">family</option>
					</select><br>					
					<input type="hidden" name="user" value="$user">
					<input type="file" id="foto" name="foto" style="margin-bottom:7px" required>				
					<input type="submit" class="btn btn-primary btn-sm" value="Upload">
				</form>
				<span id="statusUp" class="s"></span>
			</div>

EOT;
		}










	}
	
	//check the user record if exists
	
	//give some more options for the logged user
	
	//give the logged user ability to upload a photo or more
	
	//normaly we give the user option to delete the photo also
	//
	//




$path = "views/imageGallery.view.php";
require_once 'views/randem.php';

 ?>