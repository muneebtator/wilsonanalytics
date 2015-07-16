<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json");
require_once("../../../php/connect.php");
checkwmlogin($con);
if(!isset($_COOKIE['secret'])){
	echo json_encode(array('error' => 'The webmaster has not logged in.'));
	exit();
}

$website = $_POST['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);

$orderby = $_POST['orderby'];
$orderby = stripcslashes($orderby);
$orderby = strip_tags($orderby);
$orderby = htmlentities($orderby);
$orderby = mysqli_real_escape_string($con, $orderby);

$type = $_POST['type'];
$type = stripcslashes($type);
$type = strip_tags($type);
$type = htmlentities($type);
$type = mysqli_real_escape_string($con, $type);



	if($website == null){
		exit();
	}
	$query = mysqli_query($con, "SELECT * FROM websites WHERE id='$website'");
		if(mysqli_num_rows($query) == 0){
			exit();
		}

	$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website'");
	if(mysqli_num_rows($query) == 0){
			echo json_encode(array('notraffic' => true));
	}

		if($type == "today"){
		
			if($orderby == "newest"){
				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER by id DESC");
				while($row = mysqli_fetch_array($query)){
					$today = date('l jS F Y', time());
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $today){
							$lastwebsiteid = $row['id'];
						}
				}

					$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC");
					echo "[";
					while($row = mysqli_fetch_array($query)){
						$today = date('l jS F Y', time());
						$time = $row['kala'];
						$time = date('l jS F Y', $time);
							if($time == $today){
								$id = $row['id'];
								$fullurl  = $row['fullurl'];
								$page = $row['page'];
								$ref = $row['ref'];
								$kala = $row['kala'];
								$refid = $row['refid'];
								$device = $row['device'];
								$pagetitle = $row['pagetitle'];
								$smallurl = $row['smallurl'];
								$newvisitor = $row['newvisitor'];
								$kala = date('l jS F Y', $kala);
								echo json_encode(array("id" => "$id", "website"=> "$website", "fullurl" => "$fullurl", "page" => "$page", "ref" => "$ref", "kala" => "$kala", "refid" => "$refid", "device" => "$device", "pagetitle" => "$pagetitle", "smallurl" => "$smallurl", "newvisitor" => "$newvisitor"));
									if($lastwebsiteid != $row['id']){
										echo ",";
									}
					}
				}
				echo "]";
			}else if($orderby == "oldest"){
				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER by id ASC");
				while($row = mysqli_fetch_array($query)){
					$today = date('l jS F Y', time());
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $today){
							$lastwebsiteid = $row['id'];
						}
				}

				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id ASC");
				echo "[";
				while($row = mysqli_fetch_array($query)){
					$today = date('l jS F Y', time());
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $today){
							$id = $row['id'];
							$fullurl  = $row['fullurl'];
							$page = $row['page'];
							$ref = $row['ref'];
							$kala = $row['kala'];
							$refid = $row['refid'];
							$device = $row['device'];
							$pagetitle = $row['pagetitle'];
							$smallurl = $row['smallurl'];
							$newvisitor = $row['newvisitor'];
							$kala = date('l jS F Y', $kala);
							echo json_encode(array("id" => "$id", "website"=> "$website", "fullurl" => "$fullurl", "page" => "$page", "ref" => "$ref", "kala" => "$kala", "refid" => "$refid", "device" => "$device", "pagetitle" => "$pagetitle", "smallurl" => "$smallurl", "newvisitor" => "$newvisitor"));
								if($lastwebsiteid != $row['id']){
									echo ",";
								}
					}
			}
				echo "]";
		}
		
		}else if($type != null){

					if($orderby == "newest"){
				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER by id DESC");
				while($row = mysqli_fetch_array($query)){
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $type){
							$lastwebsiteid = $row['id'];
						}
				}

					$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC");
					echo "[";
					while($row = mysqli_fetch_array($query)){
						$time = $row['kala'];
						$time = date('l jS F Y', $time);
							if($time == $type){
								$id = $row['id'];
								$fullurl  = $row['fullurl'];
								$page = $row['page'];
								$ref = $row['ref'];
								$kala = $row['kala'];
								$refid = $row['refid'];
								$device = $row['device'];
								$pagetitle = $row['pagetitle'];
								$smallurl = $row['smallurl'];
								$newvisitor = $row['newvisitor'];
								$kala = date('l jS F Y', $kala);
								echo json_encode(array("id" => "$id", "website"=> "$website", "fullurl" => "$fullurl", "page" => "$page", "ref" => "$ref", "kala" => "$kala", "refid" => "$refid", "device" => "$device", "pagetitle" => "$pagetitle", "smallurl" => "$smallurl", "newvisitor" => "$newvisitor"));
									if($lastwebsiteid != $row['id']){
										echo ",";
									}
					}
				}
				echo "]";
			}else if($orderby == "oldest"){
				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER by id ASC");
				while($row = mysqli_fetch_array($query)){
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $type){
							$lastwebsiteid = $row['id'];
						}
				}

				$query = mysqli_query($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id ASC");
				echo "[";
				while($row = mysqli_fetch_array($query)){
					$time = $row['kala'];
					$time = date('l jS F Y', $time);
						if($time == $type){
							$id = $row['id'];
							$fullurl  = $row['fullurl'];
							$page = $row['page'];
							$ref = $row['ref'];
							$kala = $row['kala'];
							$refid = $row['refid'];
							$device = $row['device'];
							$pagetitle = $row['pagetitle'];
							$smallurl = $row['smallurl'];
							$newvisitor = $row['newvisitor'];
							$kala = date('l jS F Y', $kala);
							echo json_encode(array("id" => "$id", "website"=> "$website", "fullurl" => "$fullurl", "page" => "$page", "ref" => "$ref", "kala" => "$kala", "refid" => "$refid", "device" => "$device", "pagetitle" => "$pagetitle", "smallurl" => "$smallurl", "newvisitor" => "$newvisitor"));
								if($lastwebsiteid != $row['id']){
									echo ",";
								}
					}
			}
				echo "]";
		}

		}else{
			if($orderby == "newest"){
				$query = getjsondatenormal($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id DESC");
				echo $query;
			}else if($orderby == "oldest"){
				$query = getjsondatenormal($con, "SELECT * FROM traffic WHERE website='$website' ORDER BY id ASC");
				echo $query;
			}
		}

?>