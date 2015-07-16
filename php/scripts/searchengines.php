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

$query = getjson($con, "SELECT * FROM searchengines WHERE website='$website'");
echo $query;

?>