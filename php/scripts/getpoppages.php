<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$type = stripcslashes($type);
$type = strip_tags($type);
$type = htmlentities($type);
$type = mysqli_real_escape_string($con, $type);

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
if($type == "normal"){
	$query = getjson($con, "SELECT * FROM pages WHERE website='$website' ORDER BY hits DESC LIMIT 6");
	echo $query;
}else{
	$query = getjson($con, "SELECT * FROM pages WHERE website='$website' ORDER BY hits DESC LIMIT 40");
	echo $query;
}
?>