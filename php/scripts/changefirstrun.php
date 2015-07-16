<?php
header("Content-type: application/json");
require("../connect.php");
$data = json_decode(file_get_contents('php://input'), true);


	$query = mysqli_query($con, "SELECT * FROM settings WHERE name='firstrun'");
	$row = mysqli_fetch_array($query);

		if($row['value'] == true){
			mysqli_query($con, "UPDATE settings SET value='false' WHERE name='firstrun'");
		}
?>