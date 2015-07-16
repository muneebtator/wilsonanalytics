<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);
$data = json_decode(file_get_contents('php://input'), true);

$appname = $data['appname'];
$appname = stripcslashes($appname);
$appname = strip_tags($appname);
$appname = htmlentities($appname);
$appname = mysqli_real_escape_string($con, $appname);

$applocation = $data['applocation'];
$applocation = stripcslashes($applocation);
$applocation = strip_tags($applocation);
$applocation = htmlentities($applocation);
$applocation = mysqli_real_escape_string($con, $applocation);

$appicon = $data['appicon'];
$appicon = stripcslashes($appicon);
$appicon = strip_tags($appicon);
$appicon = htmlentities($appicon);
$appicon = mysqli_real_escape_string($con, $appicon);
	
	if($applocation == null || $appname == null || $appicon == null){
		exit();
	}

	$query = mysqli_query($con, "SELECT * FROM apps WHERE applocation='$applocation'");
		if(mysqli_num_rows($query) != 0){
			echo json_encode(array('isthere' => true));
			exit();
		}

	$query = mysqli_query($con, "INSERT INTO apps (appname, applocation, appicon) VALUES('$appname', '$applocation', '$appicon')");
		if(!$query){
			echo json_encode(array('stat' => false));
		}else{
			echo json_encode(array('stat' => true));
		}
?>