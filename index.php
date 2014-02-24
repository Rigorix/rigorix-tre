<?php
require_once "classes/new.core.php";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Rigorix">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Rigorix - Gioco online a premi</title>
  <meta name="description" content="Gioca gratis e vinci bellissimi premi ai rigori. Rigorix e' uno dei piu' divertenti giochi online gratuiti in flash in cui si vincono veri premi. Gioca online!">
  <meta name="keywords" content="giochi on line gratuiti a premi">
  <link rel="stylesheet" href="/app/assets/dist/app.css" type="text/css" />
  <script>
    var User = <?php echo $core->logged; ?>;
    var RigorixEnv = <?php echo FastJSON::convert($env); ?>;
  </script>
</head>
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

  <?php if($env->SHOW_LOGS === true) { _on_page_log(); } ?>
</body>
<script src="/app/assets/dist/<?php echo $env->APP_FILE; ?>" type="text/javascript"></script>
</html>