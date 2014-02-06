<?php
require_once('classes/core.php');
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Rigorix">
<?php require_once("boxes/html_head.php"); ?>

<body ng-controller="Main" ng-class="modalClass" ng-click="doClick($event)">

  <div ng-include="'/app/templates/partials/loader.html'"></div>

  <div class="container">

    <div id="page" set-loader="20"></div>

    <div ng-include="'/app/templates/partials/head.html'"></div>

    <div id="body" class="row" ng-controller="Home">

      <div class="col col-sm-9">
        <div ng-view class="rigorix-main-view"></div>
      </div>

      <div class="col col-sm-3" ng-controller="Sidebar">
        <ng-include src="'app/templates/user-box.html'"></ng-include>
        <ng-include src="'app/templates/best-users.html'"></ng-include>
      </div>

    </div>

    <div set-loader="60"></div>

    <div ng-include="'/app/templates/partials/footer.html'"></div>

  </div>

  <div set-loader="100"></div>
</body>
</html>