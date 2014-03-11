<?php require_once "classes/new.core.php"; ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:ng="http://angularjs.org" id="ng-app" ng-app="Rigorix">
<head>
  <title>Rigorix - Gioco online a premi</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="description" content="Gioca gratis e vinci bellissimi premi ai rigori. Rigorix e' uno dei piu' divertenti giochi online gratuiti in flash in cui si vincono veri premi. Gioca online!">
  <meta name="keywords" content="giochi on line gratuiti a premi">

  <meta charset="utf-8" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
  <title>Cronycle</title>
  <meta content="initial-scale=1.0 maximum-scale=1.0 user-scalable=no width=device-width" name="viewport" />
  <meta content="website" property="og:type" />
  <meta content="yes" name="apple-mobile-web-app-capable" />
  <meta content="black" name="apple-mobile-web-app-status-bar-style" />

  <link rel="stylesheet" href="/app/assets/dist/app.css" type="text/css" />
  <link rel="stylesheet" href="http://daneden.github.io/animate.css/animate.min.css" type="text/css" />
  <script>
    var User = <?php echo $core->logged; ?>;
    var RigorixEnv = <?php echo $core->get_env_vars($env); ?>;
  </script>
</head>
<body ng-controller="Main" ng-class="modalClass" ng-click="doClick($event)">

  <div ng-include="'/app/templates/partials/loader.html'"></div>
  <div ng-include="'/app/templates/partials/notifications.html'"></div>

  <div class="container">

    <div set-loader="20"></div>

    <div ng-include="'/app/templates/partials/head.html'"></div>

    <div id="body" class="row" ng-controller="Home">

      <div class="col col-sm-9">
        <div ng-view class="rigorix-main-view"></div>
      </div>

      <div class="col col-sm-3 sidebar" ng-controller="Sidebar">
        <div ng-include="'app/templates/partials/user-box.html'"></div>
        <div ng-include="'app/templates/partials/best-users.html'"></div>
      </div>

    </div>

    <div set-loader="60"></div>

    <div ng-include="'/app/templates/partials/footer.html'"></div>

  </div>

  <div set-loader="100"></div>

  <div class="visible-sm">Layout SM</div>
  <div class="visible-xs">Layout XS</div>
  <div class="visible-lg">Layout LG</div>
  <div class="visible-md">Layout MD</div>
  <div class="visible-print">Layout Print</div>
  <?php if($env->SHOW_LOGS === true) { _on_page_log(); ?>
  <?php } ?>
</body>
<script src="/app/assets/dist/<?php echo $env->APP_FILE; ?>" type="text/javascript"></script>
</html>