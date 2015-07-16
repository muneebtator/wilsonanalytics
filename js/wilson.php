<?php
header('Access-Control-Allow-Origin: *');
require('../php/connect.php');
if(!isset($_GET['website'])){
	exit();
}
$website = $_GET['website'];
$website = stripcslashes($website);
$website = strip_tags($website);
$website = htmlentities($website);
$website = mysqli_real_escape_string($con, $website);


$query = mysqli_query($con, "SELECT * FROM websites WHERE id='$website'");
if(mysqli_num_rows($query) == 0){
	exit();
}
$query = mysqli_query($con, "SELECT * FROM settings WHERE name='rootloc'");
$row = mysqli_fetch_array($query);
$rootloc = $row['value'];
echo <<<END

  function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }else{
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    return unescape(dc.substring(begin + prefix.length, end));
} 

website = $website;

  fullurl = window.location.href;
  page = window.location.pathname;
  protocol = window.location.protocol;
  from = document.referrer;
  client = null;
  pagetitle = document.title;
  currenthost = window.location.origin;
  isAndroid = /android/i.test(navigator.userAgent.toLowerCase());
  isiDevice = /ipad|iphone|ipod/i.test(navigator.userAgent.toLowerCase());
  newvisit = true;

    if(getCookie('newvisit') != null){
      newvisit = false;
    }else{
      document.cookie="newvisit=true; expires=Thu, 18 Dec 2018 12:00:00 GMT; path=/";
    }
  if(isAndroid){
    client = "Android";
  }else if(isiDevice){
    client = "iOS"; 
  }else if(navigator.userAgent.indexOf('Mac OS X') != -1) {
      client = "Mac";
  }else if(navigator.userAgent.indexOf('Windows') != -1){
      client = "Windows";
  }


if(typeof jQuery != 'undefined'){


	$.ajax({
		url: '$rootloc' + 'php/scripts/wilson.php',
		type: 'POST',
		dataType: 'JSON',
		data: {website: $website, title: pagetitle, fullurl: fullurl, page: page, from: from, currenthost: currenthost, client: client, newvisit: newvisit},
	});
	
}else{
	alert("JQuery is required for Wilson Analytics to work.");
}
function wilson(){
    this.appname = null, this.storekey = null, this.modules = [];

  this.initapp =  function(tname, skey, mod){
      this.appname = tname;
      this.storekey = skey;


        if(mod instanceof Array === false){
            console.error("Modules param should be an Array.");
            return false;
        }
    this.modules = mod;
  }


  this.datastore = function(action, name, data, cat, website, callback){
   (typeof(cat) == "undefined") ? cat = null : true;
   (typeof(name) == "undefined") ? name = null : true;
   (typeof(data) == "undefined") ? data = null : true;
    (typeof(website) == "undefined") ? website = null : true;

    if(this.storekey == null){
        console.error("Store key is required for data storing. Please check your store key.");
        return false;
    }
      $.ajax({
            url: '$rootloc' + 'apps/api/post/storemanager.php',
            type: 'POST',
            dataType: 'JSON',
            data: {action: action, name: name, cat: cat, storekey: this.storekey, data: data, website: website, from: 'monitor_script'
            },
          }).done(function(data){
              if(data.error != null){
                  console.error(data.error);
              }else if(data.warning != null){
                  console.warn(data.warning);
              }else{
                if(typeof(callback) == "function"){
                  return callback(data);
                }
              }
      });
}
}


END;

$query = mysqli_query($con, "SELECT * FROM datastore WHERE cate='monitor'");
	while($row = mysqli_fetch_array($query)){
		$loc = $row['data'];
		$loc = "../apps" . $loc;
    if(file_exists($loc)){
        $script = file_get_contents($loc, true);
    echo <<<END
    $script
END;
    }
	}
?>