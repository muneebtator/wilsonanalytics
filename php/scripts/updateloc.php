<?php
header("Content-type: application/json");
require("../connect.php");
$data = json_decode(file_get_contents('php://input'), true);

$loc = $data['loc'];
$loc = stripcslashes($loc);
$loc = strip_tags($loc);
$loc = htmlentities($loc);
$loc = mysqli_real_escape_string($con, $loc);

	if($loc == null){
		exit();
	}
	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='firstrun'");
	$row = mysqli_fetch_array($query);

		if($row['value'] == "true"){
			//$query = mysqli_query($con, "INSERT INTO settings (name, value) VALUES('rootloc', '$loc')");
			$query = mysqli_query($con, "UPDATE settings SET value='$loc' WHERE name='rootloc'");
		}

?>