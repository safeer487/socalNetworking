<?php 
//saving the variables
$u = "";
$sex = "Male";
$userlevel = "";
$birthDate = "";
$age = "";
$joindate = "";
$lastsession = "";





//saving the variables for messege box
	$envelope = "<span id='showNot' class='glyphicon glyphicon-envelope navbar-text' style='cursor:pointer;'></span>";
	$sSQL = "SELECT notescheck FROM users WHERE username = '$log_user' LIMIT 1";
	$resul = $miDB->obtenerResultado($sSQL);
	if ($resul) {
		$notescheck = $resul[0]['notescheck'];
		//checking for the updated notification
		$sSQL = "SELECT id FROM notification WHERE username = '$log_user' AND date_time > '$notescheck' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$numros = $resul;
		if ($numros > 0) {	
			$envelope = "<span id='showNot' class='bright glyphicon glyphicon-envelope navbar-text' style='cursor:pointer;'></span>";
		}
	}

	//checking for the friend requests
	$sSQL = "SELECT id FROM friends WHERE user2 = '$log_user' AND accepted = '0'";
	$resul = $miDB->contarResultadosQuery($sSQL);
	if ($resul > 0) {
		$envelope = "<span id='showNot' class='bright glyphicon glyphicon-envelope navbar-text' style='cursor:pointer;'></span>";
	}
	
	//make sure get username is set and sanitize it
	if (isset($_GET['u'])) {
		$u = preg_replace('#[^a-z0-9]#i','',$_GET['u']);		
	}else{
		header('Location: index.php');
		exit();
	}

	//check if the user is online or not
	$sSQL = "SELECT status FROM users WHERE username = '$u' LIMIT 1";
	$resul = $miDB->obtenerResultado($sSQL);
	$status = $resul[0]['status'];
	if ($status == 1) {
		$user_status = "<p class='s'>online</p>";
	}else{
		$user_status = "<p class='e'>ofline</p>";
	}


	//select the users from the table
	$sSQL = "SELECT * FROM users WHERE username = '$u' AND activated = '1' LIMIT 1";
	$resul = $miDB->obtenerResultado($sSQL);
	if (!$resul) {
		echo "The user does not exist or not yet activated";
		exit();
	}


	//check if the user is account owner
	$isOwner = "no";
	if ($u == $log_user && $user_ok == true) {
		$isOwner = 'yes';
		$profilePicButton = "<button id='pButton' class='btn btn-default btn-sm' style='position:absolute;z-index:1'>Change picture</button>";
		$avatarForm = <<<EOT
			<form enctype="multipart/from-data" id="uploadF" method="post" action="phpParsers/photoSystem.php">
				<h4>Change your picture</h4>
				<input type="file" id="avPic" name="avatar" required>
				<input type="submit" class="btn btn-default" value="Upload">
			</form>
EOT;
	}




	//saving the local variables;
	$profileId = $resul[0]['id'];

	// $u = $resul[0]['username'];
	$gender = $resul[0]['gender'];
	$userlevel = $resul[0]['userlevel'];

	//birthdate
	$birthD = $resul[0]['birthDate'];
	$birthDate = date("d-m-Y", strtotime($birthD));

	//calculating the age;
	$today = date('d-m-Y');
	list($d1,$m1, $y1) = explode('-', $birthDate);
	list($d2,$m2, $y2) = explode('-', $today);
	$age = $y2 - $y1;

	$signup = $resul[0]['signup'];
	//to convert date in prefered style we use srftime
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastlogin = $resul[0]['lastlogin'];
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));

	//checking if its male or female by default is male
	if ($gender == 'f') {
		$sex = 'Female';
	}
	//save the avatar 
	$pPicture = $resul[0]['avatar'];

	if ($pPicture == '') {
		$pPicture = <<<EOT
		<img id="pChange" src="images/1.png" alt="$u" width="50px" height="50px">

EOT;
	}else{
		$pPicture = <<<EOT
		<img id="pChange" src="$pPicture" alt="$log_user" width="150px" height="150px">
EOT;
}

 ?>
<?php 
	$isFriend = false;
	$ownerBlockViewer = false;
	$viewerBlockOwner = false;


	//validate if if the user exists in the database  but not logged in
	if($u != $log_user && $user_ok == true){
		//check if the friend exists or not
		$checkFriend = "SELECT id FROM friends WHERE user1 = '$log_user' AND user2 = '$u' AND accepted = '1' OR user1 = '$u' AND user2= '$log_user' AND accepted = '1' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($checkFriend);
		if ($resul > 0) {
			$isFriend = true;
		}
		//check if the owner blocked you to view
		$checkBlock1 = "SELECT id FROM blockedusers WHERE blocker = '$log_user' AND blockee = '$u' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($checkBlock1);
		if ($resul > 0) {
			$viewerBlockOwner = true;
		}

		//check if the viewver block the user
		$checkBlock2 = "SELECT id FROM blockedusers WHERE blocker = '$u' AND blockee = '$log_user' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($checkBlock2);
		if ($resul > 0) {			
			$ownerBlockViewer = true;
		}
	}
?>
<?php 

 	//creating the button according to the logic
 	if ($isFriend == true) {
 		$friend_button = <<<EOT
		<form id="fForm" action="">
			<input type="hidden" name="type" value="unFriend">
			<input type="hidden" name="user" value="$u">
			<button type="submit" name="fButton" id="fButton" style="width:100px;" class="btn btn-danger">unfriend user</button>
		</form>

EOT;
		//logic for buttons
 		//check if the user exists in the database and username not = logeed in and owner not blocked
 	}else if ($user_ok == true && $u != $log_user && $ownerBlockViewer == false) {
 		$friend_button = <<<EOT
		<form id="fForm" action="">
			<input type="hidden" name="type" value="addFriend">
			<input type="hidden" name="user" value="$u">
			<button type="submit" name="fButton" id="fButton" style="width:100px;" class="btn btn-primary">Add as friend</button>
		</form>

EOT;
 	}else if($user_ok == true && $u != $log_user && $ownerBlockViewer == true){
 		$info = "<p class='e'>$u has blocked you cannot send him friend request</p>";
 	}

 	//Logic for block button
 	//if the viewer has blocked the user then just unblock it
 	if ($viewerBlockOwner == true) {
  		$block_button = <<<EOT
		<form id="bForm" action="">
			<input type="hidden" name="type" value="unBlock">
			<input type="hidden" name="blocke" value="$u">
			<button type="submit" name="bButton" id="bButton" style="width:100px;" class="btn btn-primary">Unblock user</button>
		</form>

EOT;
 	}else if($user_ok == true && $u != $log_user){
  		$block_button = <<<EOT
		<form id="bForm" action="">
			<input type="hidden" name="type" value="block">
			<input type="hidden" name="blocke" value="$u">
			<button type="submit" id="bButton" style="width:100px;"" class="btn btn-danger">Block user</button>
		</form>
EOT;
 	}



  ?>
<?php 

	$friends = '';
	$noFriends = '';
	$allFriends = array();
	//count the number of friends
	$sSQL = "SELECT id from friends WHERE user1 = '$u' AND accepted = '1' OR user2 = '$u' AND accepted = '1'";
	$resul = $miDB->contarResultadosQuery($sSQL);
	
	if ($resul < 1) {
		$noFriends = "User has no friends";

	}else{
		//obtaining the friends
		$sSQL = "SELECT user2 FROM friends WHERE  user1 = '$u' AND accepted = '1'";
		$resul = $miDB->obtenerResultado($sSQL);
		if($resul){
			foreach ($resul as $value) {
				//saving all the friends in array for getting its name and avatar
				array_push($allFriends, $value['0']);
			}
		}
		$sSQL = "SELECT user1 FROM friends WHERE user2 = '$u' AND accepted = '1' ";
		$resul = $miDB->obtenerResultado($sSQL);
		if($resul){
			foreach ($resul as $value) {
				//saving all the friends in array for getting its name and avatar
				array_push($allFriends, $value['0']);
			}
		}	
	}

	if ($allFriends) {
	$orLogic = '';
	foreach ($allFriends as $key => $user) {
		$orLogic .= "username='$user' OR ";
	}
		
	//chop function will remove the last character of the array.
	$orLogic = chop($orLogic,"OR ");
	//sql for obtainting username and avatar
	$sSQL = "SELECT username,avatar,status FROM users WHERE $orLogic";
	$resul = $miDB->obtenerResultado($sSQL);

	foreach ($resul as $value) {
		$user = $value[0];
		$avatar = $value[1];
		$status = $value[2];
	
		if ($avatar == '') {
			$pPic = "<img src='images/1.png' height='50' width='50' alt=''>";
		}else{
			$pPic = <<<EOT
			 <img src="$avatar" alt="$user" width="50px" height="50px">
EOT;
		}

		$friends .= <<<EOT
			<a class="chat" href="#" title="$user">$pPic</a>
EOT;
	

	}

}

?>


<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="container">
	<div class="col-md-6">
    	<a class="navbar-brand" id="chatty" href="#">Chatty</a>
	</div>
	<div class="col-md-6">
		<?php 
		if($u == $log_user && $user_ok == true){
		$logout = "<a href='logIn/logout.php' class='navbar-text' style='font-weight:bold;color:red'>Logout</a>";
			if(isset($envelope)) echo $envelope; 
		    if(isset($logout)) echo $logout; 
		}
		?>
		
	</div>
</div>
</nav>

<div class="container">
	<div class="col-md-3">
		<p>
		<?php if (isset($avatarForm))echo $avatarForm;  if (isset($profilePicButton)) echo $profilePicButton;  if (isset($pPicture))echo $pPicture; ?><br>
		<p class="imgError s"></p>
		<!-- sending the user to the image gallery using its username -->
		<h2><a href="imageGallery.php?u=<?php echo $u;?>"><span class="glyphicon glyphicon-camera"></span> Gallery</a></h2>
		<?php echo $user_status; ?>
		<span class="bold">Is the viewer page owner?</span><?php echo $isOwner; ?></p>
		<p> <span class="bold">Username: </span><?php echo $u; ?></p>
		<p><span class="bold">Age: </span><?php echo $age; ?></p>
		<p><span class="bold">Join date: </span><?php echo $joindate; ?></p>
		<p><span class="bold">Gender</span> <?php echo $sex; ?></p>
		<p><span class="bold">Last session: </span><?php echo $lastsession; ?> </p>
		<!-- here will be your page index for our website for example chatty.com -->			
		<p><?php  if (isset($friend_button)) echo $friend_button; ?></p>
		<?php if (isset($info)) echo $info; ?>
		<p class="status" style="font-style:italic;color:green"></p>
		<p><?php  if (isset($block_button))  echo $block_button; ?></p>	
		<hr>	

		
		<p><?php if (isset($friends)) echo "<h3>Friends</h3>". $friends; ?></p>	
		<p><?php echo $noFriends; ?></p>
		<p><a class="glyphicon glyphicon-chevron-left btn btn-primary" href="javascript:history.back()"></a></p>
	</div>
	<div class="col-md-9 notification">
		<div class="well" id="notification">
			<?php
				if ($log_user == true) {
			 		require_once 'notification.php'; 	
				}
			 ?>
		</div>
	</div>
	<!-- for blogPosting  -->
		<div class="col-md-9">

			<div class="well" style="min-height:500px;display:none">
			
		</div>
		<!-- for chat panel -->
		<div class="col-md-3">
			<div class="well">
				
			</div>
		</div>



	</div>
</div>