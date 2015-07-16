<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);
$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'];
$title = stripcslashes($title);
$title = strip_tags($title);
$title = htmlentities($title);
$title = mysqli_real_escape_string($con, $title);

$website = $data['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);
	
	if($website == null || $title == null){
		exit();
	}

	$query = mysqli_query($con, "SELECT * FROM websites WHERE url='$website'");
		if(mysqli_num_rows($query) != 0){
			echo json_encode(array('stat' => false));
			exit();
		}

	$query = mysqli_query($con, "INSERT INTO websites (title, url) VALUES('$title', '$website')");
		if(!$query){
			echo json_encode(array('stat' => false));
		}else{
			$hits = 0;
			$query = mysqli_query($con, "SELECT * FROM websites WHERE url='$website'");
			$row = mysqli_fetch_array($query);
			$website = $row['id'];
			mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('Google', '$hits', '$website')");
			mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('Yahoo', '$hits', '$website')");
		 	mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('Ask', '$hits', '$website')");
			mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('Aol Search', '$hits', '$website')");
			mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('Bing', '$hits', '$website')");
			mysqli_query($con, "INSERT INTO searchengines (name, hits, website) VALUES('DuckDuckGo', '$hits', '$website')");
			echo json_encode(array('stat' => true));
		}
?>