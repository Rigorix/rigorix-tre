var RigorixAdmin;

RigorixAdmin = angular.module("RigorixAdmin", ["ngRoute", "ui.bootstrap"]);

RigorixAdmin.config([
  '$routeProvider', function($routeProvider) {
    $routeProvider.when("/logs", {
      templateUrl: "templates/page.logs.html",
      controller: "Logs"
    });
    $routeProvider.when("/logs/:logfile", {
      templateUrl: "templates/page.logs.html",
      controller: "Logs"
    });
    return $routeProvider.when("/users", {
      templateUrl: "templates/page.utente.html",
      controller: "Users"
    });
  }
]);

RigorixAdmin.config([
  '$rootScope', function($rootScope) {
    return $rootScope.TableDefinition = {
      utente: {
        name: "Utenti",
        fields: id_utente({
          name: "ID",
          type: "number",
          hidden: true
        })
      }
    };
  }
]);
