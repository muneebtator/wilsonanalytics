<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);


$data = json_decode(file_get_contents('php://input'), true);


$website = $data['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);

$today = date('l jS F Y', time());

	$query = mysqli_query($con, "SELECT * FROM websites WHERE id='$website'");
		if(mysqli_num_rows($query) == 0){
			exit();
		}



	$query = getjsondate($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC");
	echo $query;

?>