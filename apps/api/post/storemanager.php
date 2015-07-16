<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json");
require_once("../../../php/connect.php");
$from = $_POST['from'];
$from = stripcslashes($from);
$from = strip_tags($from);
$from = htmlentities($from);
$from = mysqli_real_escape_string($con, $from);

	if($from != "monitor_script"){
		checkwmlogin($con);
		if(!isset($_COOKIE['secret'])){
			echo json_encode(array('error' => 'The webmaster has not logged in.'));
			exit();
		}

	if (empty($_POST) === true) {
		echo json_encode(array('error' => 'No POST data was supplied. See the documentation for storemanager.php for more info.'));
		exit();
	}
}
$storekey = $_POST['storekey'];
$storekey = stripcslashes($storekey);
$storekey = strip_tags($storekey);
$storekey = htmlentities($storekey);
$storekey = mysqli_real_escape_string($con, $storekey);

$data = $_POST['data'];
$data = stripcslashes($data);
$data = strip_tags($data);
$data = htmlentities($data);
$data = mysqli_real_escape_string($con, $data);

$name = $_POST['name'];
$name = stripcslashes($name);
$name = strip_tags($name);
$name = htmlentities($name);
$name = mysqli_real_escape_string($con, $name);

$cat = $_POST['cat'];
$cat = stripcslashes($cat);
$cat = strip_tags($cat);
$cat = htmlentities($cat);
$cat = mysqli_real_escape_string($con, $cat);

$website = $_POST['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);


$action = $_POST['action'];
$action = stripcslashes($action);
$action = strip_tags($action);
$action = htmlentities($action);
$action = mysqli_real_escape_string($con, $action);

	if($storekey == null){
		echo json_encode(array('error' => "You have not provided the store key for storing data."));
		exit();
	}
	if($action == "STORE"){
		if($name == null || $data == null || $storekey == null){
			echo json_encode(array('error' => "You have not provided the required data."));
			exit();
		}

		$query = mysqli_query($con, "INSERT INTO datastore (name, data, storekey, cate, website) VALUES('$name', '$data', '$storekey', '$cat', '$website')");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
	}else if($action == "GET"){
		if($storekey == null){
			echo json_encode(array('error' => "You have not provided the store key."));
			exit();
		}

		if($name != null && $cat != null && $website == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat'");
					echo $query;
					}else{
						echo json_encode(array('warning' => "No data was found by the name $name", 'nodata' => true));
						exit();
					}
		}
			if($name != null && $cat == null && $website == null){
				$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE name='$name'");
						echo $query;
					}else{
						echo json_encode(array('warning' => "No data was found by the name $name", 'nodata' => true));
						exit();
					}
			} 
			if($cat != null && $name == null && $website == null){
				$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='$cat'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE cate='$cat'");
						echo $query;
					}else{
						echo json_encode(array('warning' => "No category was found by the name $cat", 'nodata' => true));
						exit();
					}
			} 
			if($website != null && $name != null && $cat == null){
				$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND website='$website'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE name='$name' AND website='$website'");
						echo $query;
					}else{
						echo json_encode(array('warning' => "No data was found by the name $name", 'nodata' => true));
						exit();
					}
			}
			if($name == null && $website != null && $cat != null){
				$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='$cat' AND website='$website'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE cate='$cat' AND website='$website'");
						echo $query;
					}else{
						echo json_encode(array('warning' => "No data was found by the name $name", 'nodata' => true));
						exit();
					}
			}

			if($website != null && $cat != null && $name != null){
				$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat' AND website='$website'");
					if(mysqli_num_rows($query) != null){
						$query = getjson($con, "SELECT * FROM datastore WHERE cate='$cat' AND website='$website'");
						echo $query;
					}else{
						echo json_encode(array('warning' => "No data was found by the name $name", 'nodata' => true));
						exit();
					}
			}

	}else if($action == "MONITOR"){
		if($cat != "monitor"){
			echo json_encode(array('error' => "Monitor code requires to be in the monitor category."));
			exit();
		}

		if($name == null || $data == null || $storekey == null){
			echo json_encode(array('error' => "You have not provided the required data."));
			exit();
		}

		$query = mysqli_query($con, "INSERT INTO datastore (name, data, storekey, cate) VALUES('$name', '$data', '$storekey', '$cat')");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}

	}else if($action == "UPDATE"){

		if($name != null && $website != null && $cat == null){
			$query = mysqli_query($con, "UPDATE datastore SET data='$data' WHERE name='$name' AND website='$website' AND cate='' AND storekey='$storekey'");
				if(!$query){
					echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again.", "success" => false));
				}else{
					echo json_encode(array('success' => true));
		}
	}

		if($name != null && $website == null && $cat == null){
			$query = mysqli_query($con, "UPDATE datastore SET data='$data' WHERE name='$name' AND website='' AND cate='' AND storekey='$storekey'");
				if(!$query){
					echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again.", "success" => false));
				}else{
					echo json_encode(array('success' => true));
				}
		}

		if($name == null && $website == null && $cat != null){
			$query = mysqli_query($con, "UPDATE datastore SET data='$data' WHERE cate='$cat' AND storekey='$storekey'");
				if(!$query){
					echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again.", "success" => false));
				}else{
					echo json_encode(array('success' => true));
				}
		}

		if($name != null && $website == null && $cat != null){
			$query = mysqli_query($con, "UPDATE datastore SET data='$data' WHERE name='$name' AND cate='$cat' AND storekey='$storekey'");
				if(!$query){
					echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again.", "success" => false));
				}else{
					echo json_encode(array('success' => true));
				}
		}

		if($name != null && $website != null && $cat != null){
			$query = mysqli_query($con, "UPDATE datastore SET data='$data' WHERE cate='$cat' AND website='$website' AND storekey='$storekey'");
				if(!$query){
					echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again.", "success" => false));
				}else{
					echo json_encode(array('success' => true));
				}
		}
	}else if($action == "CHECK"){
		if($name != null && $website == null && $cat == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND website='$website' AND cate='' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}
		if($name != null && $website == null && $cat != null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat' AND website='' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}
		if($cat != null && $name == null && $website == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='$cat' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}
		if($cat != null && $website != null && $name == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='$cat' AND website='$website' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}
		if($name != null && $website != null && $cat == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND website='$website' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}

		if($name != null && $website != null && $cat != null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat' AND website='$website' AND storekey='$storekey'");
			if(mysqli_num_rows($query) != 0){
				echo json_encode(array('exist' => true));
			}else{
				echo json_encode(array('exist' => false));
			}
		}
	}else if($action == "DELETE"){
		

		if($name != null && $website == null && $cat == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND website='' AND cate='' AND storekey='$storekey'");
				if(mysqli_num_rows($query) == 0){
					echo json_encode(array('error' => "The data you are trying to delete does not exist.", 'nodata' => true));
					exit();
		}
			$query = mysqli_query($con, "DELETE FROM datastore WHERE name='$name' AND website='' AND cate='' AND storekey='$storekey'");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
		}

		if($name != null && $website == null && $cat != null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat' AND website='' AND storekey='$storekey'");
				if(mysqli_num_rows($query) == 0){
					echo json_encode(array('error' => "The data you are trying to delete does not exist.", 'nodata' => true));
					exit();
		}
			$query = mysqli_query($con, "DELETE FROM datastore WHERE name='$name' AND cate='$cat' AND website='' AND storekey='$storekey'");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
		}

		if($cat != null && $name == null && $website == null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='$cat' AND website='' AND name='' AND storekey='$storekey'");
				if(mysqli_num_rows($query) == 0){
					echo json_encode(array('error' => "The data you are trying to delete does not exist.", 'nodata' => true));
					exit();
				}
			$query = mysqli_query($con, "DELETE FROM datastore WHERE cate='$cat' AND storekey='$storekey'");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
		}

		if($cat == null && $name != null && $website != null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND website='$website' AND cate='' AND storekey='$storekey'");
				if(mysqli_num_rows($query) == 0){
					echo json_encode(array('error' => "The data you are trying to delete does not exist."));
					exit();
				}
			$query = mysqli_query($con, "DELETE FROM datastore WHERE name='$name' AND website='$website' AND storekey='$storekey'");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
		}

		if($cat != null && $name != null && $website != null){
			$query = mysqli_query($con, "SELECT * FROM datastore WHERE name='$name' AND cate='$cat' AND website='$website' AND storekey='$storekey'");
				if(mysqli_num_rows($query) == 0){
					echo json_encode(array('error' => "The data you are trying to delete does not exist."));
					exit();
				}
			$query = mysqli_query($con, "DELETE FROM datastore WHERE name='$name' AND cate='$cat' AND website='$website' AND storekey='$storekey'");
			if(!$query){
				echo json_encode(array('error' => "There was a problem querying to the table. Check the storemanager.php file or try again."));
				exit();
			}else{
				echo json_encode(array('success' => true));
			}
		}
	}
?>