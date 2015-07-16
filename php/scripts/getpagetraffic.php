<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);


$data = json_decode(file_get_contents('php://input'), true);

$url = $data['url'];
$url = stripcslashes($url);
$url = strip_tags($url);
$url = htmlentities($url);
$url = mysqli_real_escape_string($con, $url);

$hits = 0;
$query = mysqli_query($con, "SELECT * FROM traffic WHERE fullurl='$url'");
	while($row = mysqli_fetch_array($query)){
		$hits++;
	}

echo json_encode(array('hits' => $hits));


?>