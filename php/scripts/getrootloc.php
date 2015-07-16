<?php
header("Content-type: application/json");
require("../connect.php");

$query = mysqli_query($con, "SELECT * FROM settings WHERE name='rootloc'");
$row = mysqli_fetch_array($query);
$loc = $row['value'];

echo json_encode(array('loc' => $loc));

?>