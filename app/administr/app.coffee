RigorixAdmin = angular.module "RigorixAdmin", ["ngRoute", "ngResource", "ui.bootstrap"]

RigorixAdmin.config ['$routeProvider', ($routeProvider)->

  $routeProvider.when "/logs",
    templateUrl: "templates/page.logs.html",
    controller: "Logs"

  $routeProvider.when "/logs/:logfile",
    templateUrl: "templates/page.logs.html",
    controller: "Logs"

  $routeProvider.when "/tables/:table",
    templateUrl: "templates/page.table.html",
    controller: "Tables"

  $routeProvider.when "/tables/:table/edit/:id",
    templateUrl: "templates/page.table-edit.html",
    controller: "TableEdit"

]

RigorixAdmin.schemas = {}
RigorixAdmin.tables = {}