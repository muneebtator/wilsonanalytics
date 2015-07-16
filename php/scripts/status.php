<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

	$query = mysqli_query($con, "SELECT * FROM websites WHERE origin <> '' ORDER by id DESC");
	$rowtemp = mysqli_fetch_array($query);
	$lastwebsiteid = $rowtemp['id'];
	$hits = 0;
	$lastwebsite = null;
	$morethenone = null;
	$query = mysqli_query($con, "SELECT * FROM websites WHERE origin <> ''");
	echo "[";
			while($row = mysqli_fetch_array($query)){
			$websiteid = $row['id'];
			$title = $row['title'];

				$gettraffic = mysqli_query($con, "SELECT * FROM traffic WHERE website='$websiteid'");
					while($roww = mysqli_fetch_array($gettraffic)){
						if($websiteid != $lastwebsite){
							$hits = 0;
							$morethenone = true;
						}
						$lastwebsite = $websiteid;
						$hits++;
					}

			$querye = mysqli_query($con, "SELECT * FROM traffic WHERE website='$websiteid' ORDER BY id DESC LIMIT 1");
			$rowe = mysqli_fetch_array($querye);
			$rawlasthit = $rowe['kala'];
			$lasthit = get_time_ago($rawlasthit);

					echo "{\"webistename\": \"$title\", \"hits\": \"$hits\", \"lasthit\": \"$lasthit\"}";
						if($morethenone == true && $lastwebsiteid != $websiteid){
							echo ",";
						}
		}
	echo "]";
?>