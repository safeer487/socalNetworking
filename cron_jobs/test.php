<?php 
$to = "safeer.mehmood@yahoo.com";
$from = "auto_responder@pruebando.netii.net";
$subject = "Testing Cron";
$messege = "<h2>Cron is working remove now</h2>";
$headers = "From: $from\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
mail($to, $subject, $messege,$headers);





 ?>