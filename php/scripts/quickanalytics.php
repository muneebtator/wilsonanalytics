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

$today = date('l jS F Y', time());
$yesterday = date('l jS F Y',strtotime("-1 days"));
$totalhits = 0;
$todayhits = 0;
$linkbacks = 0;
$lasthiton = "N/A";
$topref = "N/A";
$yesterdaytraffic = 0;
$trafficinrease = "There is no website traffic.";
	$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website'");
	while($row = mysqli_fetch_array($query)){
		$totalhits++;

		$time = $row['kala'];
		$time = date('l jS F Y', $time);
			if($time == $today){
				$todayhits++;
			}
	}

	$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC LIMIT 1");
	$row = mysqli_fetch_array($query);
	$rawlasthit = $row['kala'];
	$lasthit = get_time_ago($rawlasthit);
	//$lasthit = date('l jS F Y h:i:s A', $rawlasthit);
	$lasthiton = $lasthit;

	$query = mysqli_query($con, "SELECT * FROM referrer WHERE website='$website' ORDER BY totalrefs DESC LIMIT 1");
	$row = mysqli_fetch_array($query);
	$topref = $row['refwebsite'];

	$query = mysqli_query($con, "SELECT * FROM referrer WHERE website='$website'");
		while($row = mysqli_fetch_array($query)){
			$linkbacks = $row['totalrefs'] + $linkbacks;
		}

	$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website'");
		while($row = mysqli_fetch_array($query)){
			$kala = $row['kala'];
			$kala = date('l jS F Y', $kala);
				if($kala == $yesterday){
					$yesterdaytraffic++;
				}
		}



		if($yesterdaytraffic < $todayhits){
			$increased = $todayhits - $yesterdaytraffic;
			$trafficinrease = "The website traffic has increased from yesterday traffic by $increased hit(s).";
		}else if($yesterdaytraffic == $todayhits){
			$trafficinrease = "The website traffic is the same as yesterday traffic.";
		}else if($yesterdaytraffic > $todayhits){
			$descreased  = $yesterdaytraffic - $todayhits;
			$trafficinrease = "The website traffic is less from yesterday traffic by $descreased hit(s).";
		}

	$returningvisits = 0;
	$newvisits = 0;
	$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website'");
		while($row = mysqli_fetch_array($query)){
			$newvisitor = $row['newvisitor'];
				if($newvisitor == "yes"){
					$newvisits++;
				}else if($newvisitor == "no"){
					$returningvisits++;
				}
		}
	
	echo json_encode(array('totalhits' => $totalhits, 'todayhits' => $todayhits, 'lasthiton' => $lasthiton, 'topref' => $topref, 'linkbacks' => $linkbacks, 'msg1' => $trafficinrease, 'yesterdayhits' => $yesterdaytraffic, 'returningvisits' => $returningvisits, 'newvisits' => $newvisits));

?>