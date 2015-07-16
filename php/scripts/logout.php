<?php
header("Content-type: application/json");
require("../connect.php");
checkwmlogin($con);

	   if (isset($_COOKIE['username'])) {
            unset($_COOKIE['username']);    	
            unset($_COOKIE['password']);
            setcookie('username', null, -1, '/wilson');
            setcookie('password', null, -1, '/wilson');
            	   if (isset($_COOKIE['secret'])) {
            	   		unset($_COOKIE['secret']);
            	   		setcookie('secret', null, -1, '/wilson');
            	   }
            return true;
        } else {
            return false;

        }
?>