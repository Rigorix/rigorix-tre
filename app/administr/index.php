<!doctype html>
<html lang="en" ng-app="RigorixAdmin">
<head>
  <meta charset="UTF-8">
  <title>Administration Rigorix</title>
  <link rel="stylesheet" href="/app/assets/dist/dependencies/bootstrap.css"/>
  <link rel="stylesheet" href="dist/app.css"/>
</head>
<body>

<div ng-include="'/app/administr/templates/navigation.html'" ng-controller="Navigation"></div>

<ng-view></ng-view>

<script src="../assets/dist/dependencies/jquery.js"></script>
<script src="../assets/dist/dependencies/angular.js"></script>
<script src="../assets/dist/dependencies/angular-route.js"></script>
<script src="../assets/dist/dependencies/angular-resource.js"></script>
<script src="../assets/dist/dependencies/angular-bootstrap.js"></script>
<script src="../assets/dist/dependencies/moment.js"></script>
<script src="dist/app.js"></script>
<script src="dist/angular.js"></script>
</body>
</html>