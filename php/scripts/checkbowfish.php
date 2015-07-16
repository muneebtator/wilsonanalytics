<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);


	 if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            echo json_encode(array('has' => true));
        }else{
            echo json_encode(array('has' => false));
        }

?>