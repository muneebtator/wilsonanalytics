<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

$data = json_decode(file_get_contents('php://input'), true);

	$query = mysqli_query($con, "UPDATE settings SET value='$passhashold' WHERE name='password'");

	echo json_encode(array('newpass' => $passhashold));


?>