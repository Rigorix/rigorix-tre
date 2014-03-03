RigorixAdmin = angular.module "RigorixAdmin", ["ngRoute", "ui.bootstrap"]

RigorixAdmin.config ['$routeProvider', ($routeProvider, $rootScope)->

  $routeProvider.when "/logs",
    templateUrl: "templates/page.logs.html",
    controller: "Logs"

  $routeProvider.when "/logs/:logfile",
    templateUrl: "templates/page.logs.html",
    controller: "Logs"

  $routeProvider.when "/users",
    templateUrl: "templates/page.utente.html",
    controller: "Users"

]