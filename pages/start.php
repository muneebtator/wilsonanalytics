      <h1 style="margin-bottom: 0px;">Hello, <small>Welcome to the Wilson Dashboard.</small></h1>
          <hr style="margin-top: 4px;">
          <div class="alert alert-info" style="padding: 7px;" ng-show="oldversion==true">
            <p style="display: inline-block; margin-right: 10px;">A new version is available for download. Check out the <a href='{{newverfeatures}}'>new features.</a></p><a class="btn btn-primary" href="{{newverlink}}">Download new version files</a>
          </div>
          <legend style="margin-bottom: 8px;">Your Websites status</legend>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Total hits</th>
                    <th>Last hit</th>
                  </tr>
                </thead>
              <tbody>
                <tr ng-repeat="stat in status">
                  <td>{{stat.webistename}}</td>
                  <td>{{stat.hits}}</td>
                  <td>{{stat.lasthit}}</td>
                </tr>
            </tbody>
          </table>
      </div>

      <legend style="margin-bottom: 8px;">App Drawer</legend>
        <div class="row" ng-show="noapps==false">
          <div class="col-md-2" ng-repeat="app in apps" style="margin: 10px; margin-top: 0px;">
            <img src="apps/{{app.appicon}}" style="max-width: 100%; cursor: pointer;" ng-click="loadApp(app.applocation)">
            <p style="text-align: center; margin-bottom: 5px;">{{app.appname}}</p>
          </div>
        </div>
<?php if(!(isset($_COOKIE['secret']))){ ?>
<div class='alert alert-warning' style="padding: 7px;">
<p>You won't be able to run Apps, until you provide your secret.</p>
<form ng-submit="secrett()">
   <input type="password" ng-model="secret" class="form-control" placeholder="Your secret" style="margin-top: 4px; margin-bottom: 4px; width: 60%; display: inline-block;">
   <input type="submit" class="btn btn-warning" value="Go">
</form>
<small>You have to get your secret from your database manually, We have to do this for your security.</small>
</div>
<?php } ?>