<?php
header("Content-type: application/json");
require("../connect.php");
$data = json_decode(file_get_contents('php://input'), true);

	if($data['username'] == null && $data['password'] == null){
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

$hasbowfish = false;

if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $hasbowfish = true;
}

$query = mysqli_query($con, "SELECT * FROM settings WHERE name='salt'");
$row = mysqli_fetch_array($query);
$salt = $row['value'];
if($hasbowfish == true){
		$passordhash = crypt($password, $salt);
	}else{
		$passordhash = md5($password);
	}

	$query = mysqli_query($con, "SELECT * FROM settings WHERE value='$username'");
	$query2 = mysqli_query($con, "SELECT * FROM settings WHERE name='password'");
	$row = mysqli_fetch_array($query2);
	$passdb = $row['value'];
		if(mysqli_num_rows($query) != 0 && $passordhash == $passdb){
			if(!isset($_COOKIE['username'])){
				setcookie('username', $username, time() + 60*60*24*30, '/wilson');
			}
			if(!isset($_COOKIE['password'])){
				setcookie('password', $password, time() + 60*60*24*30, '/wilson');
			}
			echo json_encode(array('status' => true, 'pass' => $password));
		}else{
			echo json_encode(array('status' => false));
		}
?>