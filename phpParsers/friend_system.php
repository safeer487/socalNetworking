<?php 

//for doble check so the user can not hack or change our content
require_once("../checkLogIn.php");
if ($user_ok != true || $log_user == '') {
	exit();
}

if (isset($_POST['type']) && isset($_POST['user'])) {
	$user = preg_replace('#[^a-z0-9]#i','', $_POST['user']);
	$sSQL = "SELECT id FROM users WHERE username = '$user' LIMIT 1";
	$resul = $miDB->contarResultadosQuery($sSQL);
	if ($resul < 1) {
		echo "user does not exist";
		exit();
	}
	
		// first we count the number of friends
		$sSQL = "SELECT id FROM friends WHERE user1 = '$user' AND accepted = '1' OR user2 = '$user' AND accepted = '1' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$friend_count = $resul;

		//check if the owner has blocked the visiter
		$sSQL = "SELECT id FROM blockedusers WHERE blocker ='$log_user' AND blockee = '$user' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);	
		$blockcount1 = $resul;


		//check if the vister has blocked owner
		$sSQL = "SELECT id FROM blockedusers WHERE blocker = '$user' AND blockee = '$log_user' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$blockcount2 = $resul;

		//check if the viewer is already friend
		$sSQL = "SELECT id FROM friends WHERE user1 = '$log_user' AND user2 = '$user' AND accepted = '1' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$checkcount1 = $resul;

		//check if the owner is already friend
		$sSQL = "SELECT id FROM friends WHERE user1 = '$user' AND user2 = '$log_user' AND accepted = '1' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$checkcount2 = $resul;

		//check if the owner has already sended you the friend request
		$sSQL = "SELECT id FROM friends WHERE user1 = '$log_user' AND user2 = '$user' AND accepted = '0' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$checkcount3 = $resul;

		//check if the viewer has already sended the friend request
		$sSQL ="SELECT id FROM friends WHERE user1 = '$user' AND user2 = '$log_user' AND accepted = '0' LIMIT 1";
		$resul = $miDB->contarResultadosQuery($sSQL);
		$checkcount4 = $resul;



		if ($_POST['type'] == 'addFriend') {
			
			//applying
			if ($friend_count > 99) {
				echo "Reached the maximum limit of friend cannot add more";
				exit();
			}

			if ($blockcount1 > 0) {
				echo "you has blocked $user.please unblock to friend with $user";
				exit();
			}

			if ($blockcount2 > 0) {
				echo "$user has blocked you,cannt be friend with him";			
				exit();
			}

			if ($checkcount1 > 0 || $checkcount2[0] > 0) {
				echo "you are already friend with $user";
				exit();
			}

			if ($checkcount3 > 0) {
				echo "You have send friend request to $user, not accepted so far";
				exit();
			}

			if ($checkcount4 > 0) {
				echo  "$user has sended you friend request please accept";
				exit();
			}else{
				$sSQL = "INSERT INTO friends(user1,user2,datemade) VALUES('$log_user','$user',now())";
				$miDB->ejecutarQuery($sSQL);
				echo "friend_request_send";
				exit();
			}

		}else if($_POST['type'] == 'unFriend'){
			if ($checkcount1 > 0) {
				$sSQL = "DELETE FROM friends WHERE user1 = '$log_user' AND user2 = '$user' AND accepted = '1' LIMIT 1";				
				$miDB->ejecutarQuery($sSQL);
				echo "unfriend successfully";
				exit();
			}else if($checkcount2 > 0){
				$sSQL = "DELETE FROM friends WHERE user1 = '$user' AND user2 = '$log_user' AND accepted = '1' LIMIT 1";
				$miDB->ejecutarQuery($sSQL);
				echo "unfriend successfully";
				exit();
			}else{
				echo "you are not friend with $user so far,therefor we cant unfriend you";
			}
		}
}
 ?>
 <?php 
 	if (isset($_POST['action']) && isset($_POST['reqid']) && isset($_POST['user1'])) {
 		
 		$reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
 		$user = preg_replace('#[^a-z0-9]#', '', $_POST['user1']);
 		$sSQL = "SELECT id FROM users WHERE username = '$user' AND activated = '1' LIMIT 1";
 		

 		$resul = $miDB->contarResultadosQuery($sSQL);
 		if ($resul == 0) {
 			echo "$user does not exists";
 			exit();
 		}

 		if ($_POST['action'] == 'accept') {
 				$sSQL = "SELECT id FROM friends WHERE user1 = '$log_user' AND user2 = '$user' AND accepted = '1' LIMIT 1";
				$resul = $miDB->contarResultadosQuery($sSQL);
				$checkcount1 = $resul;

		//check if the owner is already friend
				$sSQL = "SELECT id FROM friends WHERE user1 = '$user' AND user2 = '$log_user' AND accepted = '1' LIMIT 1";
				$resul = $miDB->contarResultadosQuery($sSQL);
				$checkcount2 = $resul;





 			if ($checkcount1 > 0 || $checkcount2 > 0) {
 				echo "you are already friend with $user";
 				exit();
 			}else{
 				$sSQL = "UPDATE friends SET accepted = '1' WHERE id ='$reqid' AND user1 = '$user' AND user2 = '$log_user' LIMIT 1";
 				$miDB->ejecutarQuery($sSQL);
 				echo "you are now friends";
 				exit(); 
 			}
 		}else if($_POST['action'] == 'reject'){
 			$sSQL = "DELETE FROM friends WHERE id = '$reqid' AND user1 = '$user' AND user2 = '$log_user' AND accepted = '0' LIMIT 1 ";
 			$miDB->ejecutarQuery($sSQL);
 			echo "friend request successfully rejected";
 			exit();
 		}
 	}	





  ?>