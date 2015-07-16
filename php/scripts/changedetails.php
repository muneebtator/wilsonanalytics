<?php
header("Content-type: application/json");
require("../connect.php");
$data = json_decode(file_get_contents('php://input'), true);
$olduser = $data['olduser'];
$olduser = stripcslashes($olduser);
$olduser = strip_tags($olduser);
$olduser = htmlentities($olduser);
$olduser = mysqli_real_escape_string($con, $olduser);

$oldpas = $data['oldpas'];
$oldpas = stripcslashes($oldpas);
$oldpas = strip_tags($oldpas);
$oldpas = htmlentities($oldpas);
$oldpas = mysqli_real_escape_string($con, $oldpas);

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='salt'");
	$row = mysqli_fetch_array($query);
	$salt = $row['value'];

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='username'");
	$row = mysqli_fetch_array($query);
	$userdb = $row['value'];

	if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    	$passhashold = crypt($oldpas, $salt);
	}else{
		$passhashold = md5($oldpas);
	}	

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='password'");
	$row = mysqli_fetch_array($query);
	$passdb = $row['value'];

	if($olduser != $userdb && $passdb != $passhashold){
		exit();
	}


$username = $data['username'];
$username = stripcslashes($username);
$username = strip_tags($username);
$username = htmlentities($username);
$username = mysqli_real_escape_string($con, $username);

$password = $data['password'];
$password = stripcslashes($password);
$password = strip_tags($password);
$password = htmlentities($password);
$password = mysqli_real_escape_string($con, $password);

if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $passwordhash = crypt($password, $salt);
}else{
	$passwordhash = md5($password);
}
	if($password == null || $username == null){
		exit();
	}
	$query = mysqli_query($con, "UPDATE settings SET value='$username' WHERE name='username'");
	$query = mysqli_query($con, "UPDATE settings SET value='$passwordhash' WHERE name='password'");
		if(!$query || !$query){
			echo json_encode(array('stat' => false));
		}else{
			echo json_encode(array('stat' => true, 'hashh' => $password));
			setcookie('username', $username, time() + 60*60*24*30, '/wilson');
			setcookie('password', $password, time() + 60*60*24*30, '/wilson');
		}
?>