<div ng-controller="myappsCtrl">
<legend><i class="fa fa-tasks"></i> My Apps</legend>

<p>You can install Apps by putting them in the Apps directory, You can create your own too. Read the docs for more about Apps.</p>
<?php
   require('../php/connect.php');
   $apps = glob("*/");
   for ($i=1; $i<count($apps); $i++){
      $app = $apps[$i];
      $icon =  "$app" . "icon.png"; 
      if(file_exists($icon) == false){
         $icon = "../img/appicon.png";
      }
      $apploc = "apps/" . "$app" . "index.html"; 
      $info = file_get_contents("$app" . "info.json");
      $data = json_decode($info,true);
      $appname = $data['info']['name'];
      $description = $data['info']['description'];
      $unbookmark = false;
      $query = mysqli_query($con, "SELECT * FROM apps WHERE applocation='$apploc'"); 
         if(mysqli_num_rows($query) != 0){
            $unbookmark = true;
         }

?>
	<div class="row" style="margin: 10px;" title="<?php echo $description ?>">
		<div class="col-md-8">
		<img src="apps/<?php echo $icon; ?>" style="cursor: pointer;" ui-sref="appframe({filename: '<?php echo $apploc; ?>'})">
 		<h4 style="display: inline-block; margin-left: 8px; cursor: pointer;" ng-click="loadApp('<?php echo $apploc; ?>')"><?php echo $appname; ?> | </h4>
      <?php if($unbookmark == false){ ?>
      <div class="btn btn-primary" ng-click="bookmarkApp('<?php echo $apploc; ?>', '<?php echo $appname; ?>', '<?php echo $icon ?>')">Add to app drawer</div>
	  <?php }else{ ?>
      <div class="btn btn-danger" ng-click="unbookmarkapp('<?php echo $apploc; ?>')">Remove from app drawer</div>
     <?php } ?>
   </div>
</div>
<?php } ?>
</div>