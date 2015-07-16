<?php require_once("php/connect.php"); ?>
<!DOCTYPE html>
<html ng-app="app">
    <head>
    	<?php require_once("php/header.php"); ?>
    	<title>Wilson Analytics</title>
    </head>
    <body ng-controller="mainCtrl">
    	<div ui-view><div welcome></div>
    	  <div info></div>
    	</div>
     <?php require_once("php/footer.php"); ?>
    </body>
</html>
