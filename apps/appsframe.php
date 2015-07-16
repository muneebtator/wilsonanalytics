<?php if(!(isset($_COOKIE['secret']))){ ?>
<div class='alert alert-warning' style="padding: 7px;">
   <p>In order to run apps, you need to <a ui-sref="start">provide your secret.</a>.</p>
</div>
<?php 
exit(); 
} 
?>
<script type="text/javascript">
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
apploc = getCookie('apploc');
$("#app").load(apploc);
</script>
<div ng-controller="appframeCtrl">
	<div id="app"></div>
</div>