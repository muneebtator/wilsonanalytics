<?php
/*
You have to edit this file before you upload Wilson Analytics to your web server. Provide your MySQL Database Information.
To create the tables, use the tables.sql. Use phpMyAdmin import function.
*/

$mysql_server = null;
$mysql_user = null;
$mysql_password = null;
$mysql_db = null;

$con = new mysqli($mysql_server, $mysql_user, $mysql_password, $mysql_db);
if ($con->connect_errno) {
	printf("Connection failed: %s \n", $con->connect_error);
	exit();
}

function getjson($con, $query) {
    $data_sql = mysqli_query($con, $query) or die("'';//" . mysql_error());
    $json_str = "";
    $total = mysqli_num_rows($data_sql);
    $json_str .= "[\n";
	$row_count = 0;
        while($data = mysqli_fetch_assoc($data_sql)) {
            if(count($data) > 1) $json_str .= "{\n";
			$count = 0;
            foreach($data as $key => $value) {
                if(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
                else $json_str .= "\"$value\"";
                $count++;
                if($count < count($data)) $json_str .= ",\n";
            }         
            $row_count++;
            if(count($data) > 1) $json_str .= "}\n";
            if($row_count < $total) $json_str .= ",\n";
        }
    $json_str .= "]\n";
    	$json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
    	return $json_str;
}


function get_time_ago($time_stamp){
    $time_difference = strtotime('now') - $time_stamp;

    if ($time_difference >= 60 * 60 * 24 * 365.242199)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 365.242199 days/year
         * This means that the time difference is 1 year or more
         */
        return get_time_ago_string($time_stamp, 60 * 60 * 24 * 365.242199, 'year');
    }
    elseif ($time_difference >= 60 * 60 * 24 * 30.4368499)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 30.4368499 days/month
         * This means that the time difference is 1 month or more
         */
        return get_time_ago_string($time_stamp, 60 * 60 * 24 * 30.4368499, 'month');
    }
    elseif ($time_difference >= 60 * 60 * 24 * 7)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 7 days/week
         * This means that the time difference is 1 week or more
         */
        return get_time_ago_string($time_stamp, 60 * 60 * 24 * 7, 'week');
    }
    elseif ($time_difference >= 60 * 60 * 24)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour * 24 hours/day
         * This means that the time difference is 1 day or more
         */
        return get_time_ago_string($time_stamp, 60 * 60 * 24, 'day');
    }
    elseif ($time_difference >= 60 * 60)
    {
        /*
         * 60 seconds/minute * 60 minutes/hour
         * This means that the time difference is 1 hour or more
         */
        return get_time_ago_string($time_stamp, 60 * 60, 'hour');
    }
    else
    {
        /*
         * 60 seconds/minute
         * This means that the time difference is a matter of minutes
         */
        return get_time_ago_string($time_stamp, 60, 'minute');
    }
}

function get_time_ago_string($time_stamp, $divisor, $time_unit)
{
    $time_difference = strtotime("now") - $time_stamp;
    $time_units      = floor($time_difference / $divisor);

    settype($time_units, 'string');

    if ($time_units === '0')
    {
        return 'less than 1 ' . $time_unit . ' ago';
    }
    elseif ($time_units === '1')
    {
        return '1 ' . $time_unit . ' ago';
    }
    else
    {
        /*
         * More than "1" $time_unit. This is the "plural" message.
         */
        // TODO: This pluralizes the time unit, which is done by adding "s" at the end; this will not work for i18n!
        return $time_units . ' ' . $time_unit . 's ago';
    }
}

function getjsondate($con, $query) {
    $data_sql = mysqli_query($con, $query) or die("'';//" . mysql_error());
    $json_str = "";
    $total = mysqli_num_rows($data_sql);
    $json_str .= "[\n";
    $row_count = 0;
        while($data = mysqli_fetch_assoc($data_sql)) {
            if(count($data) > 1) $json_str .= "{\n";
            $count = 0;
            foreach($data as $key => $value) {
                if($key != "kala"){
                if(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
                else $json_str .= "\"$value\"";
                }else{
                    $kala = get_time_ago($value);
                     if(count($data) > 1) $json_str .= "\"$key\":\"$kala\"";
                else $json_str .= "\"$kala\"";
                }
                $count++;
                if($count < count($data)) $json_str .= ",\n";
            }         
            $row_count++;
            if(count($data) > 1) $json_str .= "}\n";
            if($row_count < $total) $json_str .= ",\n";
        }
    $json_str .= "]\n";
        $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
        return $json_str;
}


function getjsondatenormal($con, $query) {
    $data_sql = mysqli_query($con, $query) or die("'';//" . mysql_error());
    $json_str = "";
    $total = mysqli_num_rows($data_sql);
    $json_str .= "[\n";
    $row_count = 0;
        while($data = mysqli_fetch_assoc($data_sql)) {
            if(count($data) > 1) $json_str .= "{\n";
            $count = 0;
            foreach($data as $key => $value) {
                if($key != "kala"){
                if(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
                else $json_str .= "\"$value\"";
                }else{
                    $kala = date('l jS F Y h:i A', $value);

                     if(count($data) > 1) $json_str .= "\"$key\":\"$kala\"";
                else $json_str .= "\"$kala\"";
                }
                $count++;
                if($count < count($data)) $json_str .= ",\n";
            }         
            $row_count++;
            if(count($data) > 1) $json_str .= "}\n";
            if($row_count < $total) $json_str .= ",\n";
        }
    $json_str .= "]\n";
        $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
        return $json_str;
}


function genKey($length) {
     $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < 10; $i++){
        $result .= $characters[mt_rand(0, 61)];
    }
    return $result;
}

function assign_rand_value($num) {
  switch($num) {
    case "1":
     $rand_value = "a";
    break;
    case "2":
     $rand_value = "b";
    break;
    case "3":
     $rand_value = "c";
    break;
    case "4":
     $rand_value = "d";
    break;
    case "5":
     $rand_value = "e";
    break;
    case "6":
     $rand_value = "f";
    break;
    case "7":
     $rand_value = "g";
    break;
    case "8":
     $rand_value = "h";
    break;
    case "9":
     $rand_value = "i";
    break;
    case "10":
     $rand_value = "j";
    break;
    case "11":
     $rand_value = "k";
    break;
    case "12":
     $rand_value = "l";
    break;
    case "13":
     $rand_value = "m";
    break;
    case "14":
     $rand_value = "n";
    break;
    case "15":
     $rand_value = "o";
    break;
    case "16":
     $rand_value = "p";
    break;
    case "17":
     $rand_value = "q";
    break;
    case "18":
     $rand_value = "r";
    break;
    case "19":
     $rand_value = "s";
    break;
    case "20":
     $rand_value = "t";
    break;
    case "21":
     $rand_value = "u";
    break;
    case "22":
     $rand_value = "v";
    break;
    case "23":
     $rand_value = "w";
    break;
    case "24":
     $rand_value = "x";
    break;
    case "25":
     $rand_value = "y";
    break;
    case "26":
     $rand_value = "z";
    break;
    case "27":
     $rand_value = "0";
    break;
    case "28":
     $rand_value = "1";
    break;
    case "29":
     $rand_value = "2";
    break;
    case "30":
     $rand_value = "3";
    break;
    case "31":
     $rand_value = "4";
    break;
    case "32":
     $rand_value = "5";
    break;
    case "33":
     $rand_value = "6";
    break;
    case "34":
     $rand_value = "7";
    break;
    case "35":
     $rand_value = "8";
    break;
    case "36":
     $rand_value = "9";
    break;
    case "37":
     $rand_value = "*";
    break;
    case "38":
     $rand_value = "~";
    break;
    case "39":
     $rand_value = "-";
    break;
    case "40":
     $rand_value = "|";
    break;
    case "41":
     $rand_value = "^";
    break;
    case "42":
     $rand_value = "%";
    break;
    case "43":
     $rand_value = " ";
    break;
    case "44":
     $rand_value = "_";
    break;
    case "45":
     $rand_value = "+";
    break;
    case "46":
     $rand_value = "=";
    break;
    case "47":
     $rand_value = "A";
    break;
    case "48":
     $rand_value = "B";
    break;
    case "49":
     $rand_value = "C";
    break;
    case "50":
     $rand_value = "D";
    break;
    case "51":
     $rand_value = "E";
    break;
    case "52":
     $rand_value = "F";
    break;
    case "53":
     $rand_value = "G";
    break;
    case "54":
     $rand_value = "H";
    break;
    case "55":
     $rand_value = "I";
    break;
    case "56":
     $rand_value = "J";
    break;
    case "57":
     $rand_value = "K";
    break;
    case "58":
     $rand_value = "L";
    break;
    case "59":
     $rand_value = "M";
    break;
    case "60":
     $rand_value = "N";
    break;
    case "61":
     $rand_value = "O";
    break;
    case "62":
     $rand_value = "P";
    break;
    case "63":
     $rand_value = "Q";
    break;
    case "64":
     $rand_value = "R";
    break;
    case "65":
     $rand_value = "S";
    break;
    case "66":
     $rand_value = "T";
    break;
    case "67":
     $rand_value = "U";
    break;
    case "68":
     $rand_value = "V";
    break;
    case "69":
     $rand_value = "W";
    break;
    case "70":
     $rand_value = "X";
    break;
    case "71":
     $rand_value = "Y";
    break;
    case "72":
     $rand_value = "Z";
    break;
  }
return $rand_value;
}
    function checkwmlogin($con){
        if(!isset($_COOKIE['username'])){
            exit();
        }
        if(!isset($_COOKIE['password'])){
            exit();
        }

        $username = $_COOKIE['username'];
        $username = stripcslashes($username);
        $username = strip_tags($username);  
        $username = htmlentities($username);
        $username = mysqli_real_escape_string($con, $username);

        $password = $_COOKIE['password'];
        $password = stripcslashes($password);
        $password = strip_tags($password);
        $password = htmlentities($password);
        $password = mysqli_real_escape_string($con, $password);

        $query = mysqli_query($con, "SELECT * FROM settings WHERE name='salt'");
        $row = mysqli_fetch_array($query);
        $salt = $row['value'];

        $query = mysqli_query($con, "SELECT * FROM settings WHERE name='username'");
        $row = mysqli_fetch_array($query);
        $userdb = $row['value'];

        $query = mysqli_query($con, "SELECT * FROM settings WHERE name='password'");
        $row = mysqli_fetch_array($query);
        $passdb = $row['value'];

        if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $password = crypt($password, $salt);
        }else{
            $password = md5($password);
        }

        if($username != $userdb && $password != $passdb){
            exit();
        }
    }
function genSlat() {
        $max = 50;
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
}

?>
