RigorixAdmin = angular.module "RigorixAdmin", ["ngRoute", "ui.bootstrap"]

RigorixAdmin.config ['$routeProvider', ($routeProvider)->

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

#RigorixAdmin.config "TableDefinitions", ['$rootScope', ($rootScope)->
#
#  $rootScope.TableDefinition =
#
#    utente:
#      name: "Utenti"
#      fields:
#        id_utente
#          name: "ID"
#          type: "number"
#          hidden: true
#
#]