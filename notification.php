<?php 
	include_once 'checkLogIn.php';
 	
 	//check if the user log in 
 	if ($user_ok != true || $log_user == '') {
 		header('Location:index.php');
 		exit();
 	}

 	//obtaining the notification list
	$notification_list = '';
	$sSQL = "SELECT * FROM notification WHERE username = '$log_user' ORDER BY date_time DESC";
	$resul = $miDB->contarResultadosQuery($sSQL);
	if ($resul < 1) {
		$notification_list = "You do not have any notification";
	}else{
	$notes = $miDB->obtenerResultado($sSQL);
		foreach ($notes as $value) {
			extract($values);
			$notesid = $id;
			$initiator = $initiator;
			$app = $app;
			$note = $note;
			$date_time = $date_time;
			$date_time = strftime("%b %d, %Y", strtotime($date_time));
			//here we are appending all the variables in a paragraph loop
			$notification_list .= <<<EOT
				<p><a href="user.php?u=$initiator">$initiator</a> | $app<br/>$notes</p>
EOT;
		}
	}
	//updating the notescheck for next use
	$sSQL = "UPDATE users SET notescheck=now() WHERE username = '$log_user' LIMIT 1";
	$miDB->ejecutarQuery($sSQL);
?>


 <?php
 	//checking for friend requests
	$friend_requests = "";
	$sSQL = "SELECT * FROM friends WHERE user2='$log_user' AND accepted = '0' ORDER BY datemade ASC";	
	$resul = $miDB->obtenerResultado($sSQL);
	//if there is no friend requests then
	if (!$resul) {
 		$friend_requests = "No friend requests";
 	}else{
 		foreach ($resul as $value) {
	 		extract($value);
	 		$reqid = $id;
	 		$user1 = $user1;
	 		$datemade = $datemade;
	 		$datemade = strftime("%B %d", strtotime($datemade));
	 		$thumbquery = "SELECT avatar from users WHERE username = '$user1' LIMIT 1";
	 		$thumbResul = $miDB->obtenerResultado($thumbquery);
	 		$user1Avatar = $thumbResul[0]['avatar'];
	 		$user1Pic = <<<EOT
				<img src="$user1Avatar" alt="$user1" class="pImg user_pic" width="50px" height="50px">
EOT;
				//if there is no picture then select the default one
				if ($user1Avatar == NULL) {
					$user1Pic = <<<EOT
					<img src="images/1.png" class="pImg" alt="$user1">
EOT;
				}
			//making buttons for accepts or reject the request
			$friend_requests = <<<EOT
				<div class="well">
				$user1Pic <br>
				<button class="btn btn-info btn-xs" onclick="friendReqHandler('accept','$reqid','$user1')">Accept</button>
				<button class="btn btn-danger btn-xs" onclick="friendReqHandler('reject','$reqid','$user1')">Reject</button>
				<p>$user1 has requested you</p>
				</div>
			
EOT;
		 
		}	
 	}


require_once 'views/notification.view.php';
 ?>



