<?php
header("Content-type: application/json");
require("../connect.php");
$data = json_decode(file_get_contents('php://input'), true);

checkwmlogin($con);

$loc = $data['loc'];
$loc = stripcslashes($loc);
$loc = strip_tags($loc);
$loc = htmlentities($loc);
$loc = mysqli_real_escape_string($con, $loc);

	if($loc == null){
		exit();
	}

$query = mysqli_query($con, "UPDATE settings SET value='$loc' WHERE name='rootloc'");


if(!$query){
	echo json_encode(array('stat' => false));
}else{
	echo json_encode(array('stat' => true));
}
?>