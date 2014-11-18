

<?php 
require_once '../BD.class.php';
$miDB = new DB();

//check for security
if (isset($_POST['catagory']) && isset($_POST['user'])) {
	//saving it into a variable
	$cat = trim($_POST['catagory']);
	$user = trim($_POST['user']);
	$cat = strtolower($cat);

	//obtaining from the database
	$sSQL = "SELECT imgPath FROM photogallery WHERE user = '$user' AND catagory = '$cat'";
	$resul = $miDB->obtenerResultado($sSQL);
	echo json_encode($resul);
}
 ?>
