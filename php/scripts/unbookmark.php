<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);
$data = json_decode(file_get_contents('php://input'), true);

$applocation = $data['applocation'];
$applocation = stripcslashes($applocation);
$applocation = strip_tags($applocation);
$applocation = htmlentities($applocation);
$applocation = mysqli_real_escape_string($con, $applocation);

	if($applocation == null){
		exit();
	}

	$query = mysqli_query($con, "DELETE FROM apps WHERE applocation='$applocation'");
		if(!$query){
			echo json_encode(array('stat' => false));
		}else{
			echo json_encode(array('stat' => true));
		}
?>