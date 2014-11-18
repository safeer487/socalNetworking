<?php 
$messege = "";
$msg = preg_replace('#[^a-z 0-9.:_()]#i','', $_GET['msg']);
if ($msg == "activation_failure") {
	$messege = "<h2>It seems to be server error we will contact you via email</h2>";
}else if($msg == "activation_success"){
	$messege = '<h2>Activation success</h2> Your account is now activated. <a href="../index.php" title="">Click here to log in</a>';
}else{
	$messege = $msg;
}
?>
 <div><?php echo $messege; ?></div>