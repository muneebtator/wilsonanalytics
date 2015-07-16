<?php
header("Content-type: application/json");
require("../connect.php");
	
	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='firstrun'");
	$row = mysqli_fetch_array($query);
		if($row['value'] == "true"){
			$resultpass = null;

			 if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
			 	$result = genSlat();
				$salt = '$$2a$07$' . "$result" . '$';
            	$resultpass = crypt('admin', $salt);
       		}else{
            	$resultpass = md5("admin");
        	}

        	$query = mysqli_query($con, "UPDATE settings SET value='$salt' WHERE name='salt'");
        	$query = mysqli_query($con, "UPDATE settings SET value='$resultpass' WHERE name='password'");

			echo json_encode(array('first' => true));
		}else{
			echo json_encode(array('first' => false));
		}

?>