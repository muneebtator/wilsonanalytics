<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

	$query = mysqli_query($con, "SELECT * FROM apps ORDER BY id DESC");
		while($row = mysqli_fetch_array($query)){
			$applocation = $row['applocation'];
			$applocationn = "../../" . $applocation;
				if(file_exists($applocationn) == false){
				  $delete = mysqli_query($con, "DELETE FROM apps WHERE applocation='$applocation'");

				}
		}
		if(mysqli_num_rows($query) == 0){
			echo json_encode(array('noapps' => true));
		}else{
			$query = getjson($con, "SELECT * FROM apps ORDER BY id DESC");
			echo $query;
		}

?>