<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json");
session_start();
require_once("../../../php/connect.php");
checkwmlogin($con);
if(isset($_POST['wquery'])){
	if(!isset($_COOKIE['secret'])){
		echo json_encode(array('error' => 'The webmaster has not logged in.'));
		exit();
	}
$secret = $_COOKIE['secret'];
$username = null;
$password = null;
	if(isset($_COOKIE['username'])){
		$username = $_COOKIE['username'];
	}else{
		echo json_encode(array('error' => 'The webmaster has not logged in.'));
		exit();
	}

	if(isset($_COOKIE['password'])){
		$password = $_COOKIE['password'];
	}else{
		echo json_encode(array('error' => 'The webmaster has not logged in.'));
		exit();
	}
	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='username'");
	$row = mysqli_fetch_array($query);
	$userdb = $row['value'];
		if($username != $userdb){
			echo json_encode(array('error' => 'Enter the correct username'));
			exit();
		}

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='password'");
	$row = mysqli_fetch_array($query);
	$passdb = $row['value'];
		if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    		$query = mysqli_query($con, "SELECT * FROM settings WHERE name='salt'");
    		$row = mysqli_fetch_array($query);
    		$salt = $row['value'];
    		$password = crypt($password, $salt);
		}else{
			$password = md5($password);
		}

		if($password != $passdb){
			echo json_encode(array('error' => 'Enter the correct password'));
			exit();
		}

	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='secret'");
	$row = mysqli_fetch_array($query);
	$secretdb = $row['value'];
		if($secret != $secretdb){
			echo json_encode(array('error' => 'Enter the correct secret.'));
			exit();
		}
$wquery = $_POST['wquery'];
$count = $_POST['count'];
$count = stripcslashes($count);
$count = strip_tags($count);
$count = htmlentities($count);
$count = mysqli_real_escape_string($con, $count);

$tables = array('websites', 'traffic', 'share', 'referrer', 'pages', 'searchengines', 'datastore');

	if($username == null || $password == null || $secret == null || $wquery == null){
		exit();
	}

	if(strpos($wquery, $tables[0]) == false && strpos($wquery, $tables[1]) == false && strpos($wquery, $tables[2]) == false && strpos($wquery, $tables[3]) == false && strpos($wquery, $tables[4]) == false){
		echo json_encode(array('error' => 'You are using a table that is prohibited from access.'));
		exit();
	}

	if (strpos($wquery,'settings') !== false) {
		echo json_encode(array('error' => 'You are using a prohibited word in your WQL query. "settings" '));
		exit();
	}

	if (strpos($wquery,'apps') !== false) {
		echo json_encode(array('error' => 'You are using a prohibited word in your WQL query. "apps"'));
		exit();
	}


	if (strpos($wquery,'JOIN') !== false) {
		echo json_encode(array('error' => 'WQL does not support the SQL JOIN Operator.'));
		exit();
	}

	if (strpos($wquery,'UNION') !== false) {
		echo json_encode(array('error' => 'WQL does not support the SQL UNION Operator.'));
		exit();
	}

	if (strpos($wquery,'DELETE') !== false) {
		echo json_encode(array('error' => 'WQL does not support the SQL DELETE Operator.'));
		exit();
	}


	if (strpos($wquery,'%') !== false) {
		echo json_encode(array('error' => 'WQL does not support the % Operator.'));
		exit();
	}

	$query = mysqli_query($con, $wquery);
		if(mysqli_error($con) != null){
			echo json_encode(array('error' => 'There is an error in your WQL Query, Check the query and if that does not solve the problem refer to the docs or contact support.'));
			exit();
		}
		if(mysqli_num_rows($query) == 0){
			echo json_encode(array('warning' => 'No data was found.'));
		}else{
			if($count != true){
				$query = getjson($con, $wquery);
				echo $query;
			}else{
				$times = 0;
					while($row = mysqli_fetch_array($query)){
						$times++;
					}
				echo json_encode(array('instances' => $times));
			}
		}
}
?>
