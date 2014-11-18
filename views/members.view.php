
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
		<a class="navbar-brand" id="chatty" href="#">Chatty</a>
	</div>
</nav>

<div class="container">
		<div class="col-md-3">
		<div class="well">
			
		<span class="btn btn-danger" style="width:100%">Members</span><br><br>
		<?php 
			require_once 'BD.class.php';
			$miDB = new DB();
			$sSQL = "SELECT username FROM users";
			$resul = $miDB->obtenerResultado($sSQL);
		 		$sHtml = '';
		 			
		 	foreach ($resul as $value) {
		 		extract($value);
		 		$sHtml .= <<<EOT
					<a href="user.php?u={$username}" class="btn btn-primary" style="width:100%; margin-bottom:2px">$username</a>									
EOT;
		 	}
		 	echo $sHtml;		 	
		 ?>
		 </div>
		 <p><a class="glyphicon glyphicon-chevron-left btn btn-primary" href="javascript:history.back()"></a></p>
		 </div>
 </div>
