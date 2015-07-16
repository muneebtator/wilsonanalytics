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

$type = $data['type'];
$type = stripcslashes($type);
$type = strip_tags($type);
$type = htmlentities($type);
$type = mysqli_real_escape_string($con, $type);

$today = date('l jS F Y', time());

	$query = mysqli_query($con, "SELECT * FROM websites WHERE id='$website'");
		if(mysqli_num_rows($query) == 0){
			exit();
		}
if($type == "normal"){
	$query = getjsondatenormal($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC LIMIT 10");
	echo $query;
}else{
	$query = getjson($con, "SELECT * FROM pages WHERE website='$website' ORDER BY hits DESC LIMIT 40");
	echo $query;
}
?>