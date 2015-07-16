<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

$query = mysqli_query($con, "SELECT * FROM websites");
	if(mysqli_num_rows($query) == 0){
		echo json_encode(array('haswebsites' => false));
	}else{
		$ret = getjson($con, "SELECT * FROM websites");
		echo $ret;
	}
?>