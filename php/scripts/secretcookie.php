<?php
require("../connect.php");
checkwmlogin($con);

$secret = $_GET['secret'];
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
			header("Location: /wilson");
		}else{
			setcookie('secret', $secret, time() + 60*60*24*30, '/wilson');
			header("Location: /wilson");
		}
?>