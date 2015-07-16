<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json");
require("../connect.php");

$website = $_POST['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);

$fullurl = $_POST['fullurl'];
$fullurl = stripcslashes($fullurl);
$fullurl = strip_tags($fullurl);
$fullurl = htmlentities($fullurl);
$fullurl = mysqli_real_escape_string($con, $fullurl);

$parse = parse_url($fullurl);
$smallurl = $parse['host'];

$title = $_POST['title'];
$title = stripcslashes($title);
$title = strip_tags($title);
$title = htmlentities($title);
$title = mysqli_real_escape_string($con, $title);

$page = $_POST['page'];
$page = stripcslashes($page);
$page = strip_tags($page);
$page = htmlentities($page);
$page = mysqli_real_escape_string($con, $page);


$currenthost = $_POST['currenthost'];
$currenthost = stripcslashes($currenthost);
$currenthost = strip_tags($currenthost);
$currenthost = htmlentities($currenthost);
$currenthost = mysqli_real_escape_string($con, $currenthost);

$newvisit = $_POST['newvisit'];
$newvisit = stripcslashes($newvisit);
$newvisit = strip_tags($newvisit);
$newvisit = htmlentities($newvisit);
$newvisit = mysqli_real_escape_string($con, $newvisit);

$from = $_POST['from'];
$from = stripcslashes($from);
$from = strip_tags($from);
$from = htmlentities($from);
$from = mysqli_real_escape_string($con, $from);

if($from != null){
$parse = parse_url($from);
$fromsmall = $parse['host'];

if (strpos($fromsmall,'google') !== false) {
	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='Google' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines SET hits='$hits' WHERE name='Google' AND website='$website'");

}else if(strpos($fromsmall,'yahoo') !== false) {
	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='Yahoo' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines SET hits='$hits' WHERE name='Yahoo' AND website='$website'");

}else if(strpos($fromsmall,'ask') !== false) {

	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='Ask' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines SET hits='$hits' WHERE name='Ask' AND website='$website'");

}else if(strpos($fromsmall,'aol') !== false) {

	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='Aol Search' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines set hits='$hits' WHERE name='Aol Search' AND website='$website'");


}else if(strpos($fromsmall,'bing') !== false) {

	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='Bing' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines set hits='$hits' WHERE name='Bing' AND website='$website'");

}else if(strpos($fromsmall,'duckduckgo') !== false) {

   	$query = mysqli_query($con, "SELECT * FROM searchengines WHERE name='DuckDuckGo' AND website='$website'");
	$row = mysqli_fetch_array($query);
	$hits = $row['hits'] + 1;
	$query = mysqli_query($con, "UPDATE searchengines set hits='$hits' WHERE name='DuckDuckGo' AND website='$website'");
}

}else{
	$fromsmall = "Direct Entry";
}

$client = $_POST['client'];
$client = stripcslashes($client);
$client = strip_tags($client);
$client = htmlentities($client);
$client = mysqli_real_escape_string($con, $client);


$fromhost = null;
$refid = 0;

$now = time();
	$query = mysqli_query($con, "SELECT * FROM websites WHERE id='$website'");

		if(mysqli_num_rows($query) == 0){
			exit();
		}

	$row = mysqli_fetch_array($query);
	$origin = $row['origin'];
		if($origin == null){
			$query = mysqli_query($con, "UPDATE websites SET origin='$currenthost' WHERE id='$website'");
		}else{
			if($origin != $currenthost){
				exit();
			}
		}
	$query = mysqli_query($con, "SELECT * FROM device WHERE name='$client'");
		if(mysqli_num_rows($query) != 0){
			$query = mysqli_query($con, "SELECT * FROM share WHERE device='$client' AND website='$website'");
				if(mysqli_num_rows($query) != 0){
					$row = mysqli_fetch_array($query);
					$share = $row['hits'];
					$share = $share + 1;
					$query = mysqli_query($con, "UPDATE share SET hits='$share' WHERE website='$website' AND device='$client'");
				}else{
					$hits = 1;
					$query = mysqli_query($con, "INSERT INTO share (website, device, hits) VALUES('$website', '$client', '$hits')");
				}
		}

	if($from == null){
		$from = "Direct Entry";
	}else{
		$parse = parse_url($from);
		$fromhost = $parse['host'];

		$query = mysqli_query($con, "SELECT * FROM referrer WHERE refwebsite='$fromhost' AND website='$website'");
			if(mysqli_num_rows($query) == 0){
				$hits = 1;
				$query = mysqli_query($con, "INSERT INTO referrer (refwebsite, website, totalrefs) VALUES('$fromhost', '$website', '$hits')");
			}else{
				$row = mysqli_fetch_array($query);
				$hits = $row['totalrefs'];
				$hits = $hits + 1;
				$query = mysqli_query($con, "UPDATE referrer SET totalrefs='$hits' WHERE refwebsite='$fromhost' AND website='$website'");

				$query = mysqli_query($con, "SELECT * FROM referrer WHERE refwebsite='$fromhost' AND website='$website'");
				$row = mysqli_fetch_array($query);
				$refid = $row['id'];
			}
	}
	if($page == "/"){
		$page = "Index Page";
	}

	$query = mysqli_query($con, "SELECT * FROM pages WHERE page='$page' AND website='$website'");
		if(mysqli_num_rows($query) == 0){
			$hitt = 1;
			$query = mysqli_query($con, "INSERT INTO pages (page, website, hits, title) VALUES('$page', '$website', '$hitt', '$title')");
		}else{
			$row = mysqli_fetch_array($query);
			$idd = $row['id'];
			$hitt = $row['hits'];
			$hitt = $hitt + 1;
			$query = mysqli_query($con, "UPDATE pages SET hits='$hitt' WHERE id='$idd'");
		}

	$isnewuser = "no";
		if($newvisit == "true"){
			$isnewuser = "yes";
		}

	$query = mysqli_query($con, "INSERT INTO traffic (website, fullurl, page, kala, ref, refid, device, pagetitle, smallurl, newvisitor) VALUES('$website', '$fullurl', '$page', '$now', '$from', '$refid', '$client', '$title', '$fromsmall', '$isnewuser')");
?>