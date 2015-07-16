<?php
header("Content-type: application/json");
require("../connect.php");
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

if($type == "normal"){
	$query = getjson($con, "SELECT * FROM referrer WHERE website='$website' ORDER BY totalrefs DESC LIMIT 6");
	echo $query;
}else{
	$query = getjson($con, "SELECT * FROM referrer WHERE website='$website' ORDER BY totalrefs DESC LIMIT 40");
	echo $query;
}
?>