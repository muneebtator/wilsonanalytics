<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);
$data = json_decode(file_get_contents('php://input'), true);

$secret = $data['secret'];
$secret = stripcslashes($secret);
$secret = strip_tags($secret);
$secret = htmlentities($secret);
$secret = mysqli_real_escape_string($con, $secret);
	
	if($secret == null){
		exit();
	}

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='secret'");
	$row = mysqli_fetch_array($query);
	$secretdb = $row['value'];
		if($secret != $secretdb){
			echo json_encode(array('wrong' => true));
		}else{
			//setcookie('secret', $secret, time() + 60*60*24*30, '/wilson');
			echo json_encode(array('wrong' => false));
		}
?>