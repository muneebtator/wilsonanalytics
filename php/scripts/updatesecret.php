<?php
header("Content-type: application/json");
require("../connect.php");
$query = mysqli_query($con, "SELECT * FROM settings WHERE name='firstrun'");
$row = mysqli_fetch_array($query);
$firstrun = $row['value'];
if($firstrun == "true"){	
	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='secret'");
	$row = mysqli_fetch_array($query);
		if($row['value'] == 0){
			$length = 30;
			$result = genKey($length);


			$query = mysqli_query($con, "UPDATE settings SET value='$result' WHERE name='secret'");
		}
}

?>