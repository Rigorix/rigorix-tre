<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Rigorix">

<?php require_once("boxes/html_head.php"); ?>

<body ng-controller="Main" ng-class="modalClass" ng-click="doClick($event)">

  <div ng-include="'/app/templates/partials/loader.html'"></div>

  <div class="container">

    <div id="page" set-loader="20">

      <div ng-include="'/app/templates/partials/head.html'"></div>

      <div id="body">

