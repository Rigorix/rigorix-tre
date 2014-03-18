RigorixAdmin.controller("Logs", [
  '$scope', '$rootScope', '$http', '$location', '$routeParams', function($scope, $rootScope, $http, $location, $routeParams) {
    $http.get("/api/logs").success(function(logs) {
      return $scope.logs = logs;
    });
    $scope.logfile = $routeParams.logfile != null ? $routeParams.logfile : false;
    if ($scope.logfile !== false) {
      $http.get("/api/logs/" + $scope.logfile).success(function(content) {
        return $scope.logContent = content;
      });
    }
    return $scope.isActiveLogFile = function(file) {
      var ret;
      ret = file.split(" ").join("_") + "_log.txt" === $scope.logfile;
      return ret;
    };
  }
]);

RigorixAdmin.controller("Navigation", [
  "$scope", "$location", function($scope, $location) {
    $scope.activeItem = $location.$$path;
    return $scope.$on("$routeChangeStart", function(event, next, current) {
      $scope.activeItem = $location.$$path;
      return console.log("activeItem=", $scope.activeItem);
    });
  }
]);

RigorixAdmin.controller("TableCell", [
  '$scope', '$rootScope', function($scope, $rootScope) {
    var schema;
    schema = $rootScope.TableDefinition[$scope.table];
    $scope.fieldContent = $scope.getFieldContent();
    return $scope.getFieldContent = function() {
      switch (schema.fields[$scope.column].type) {
        case "number":
          return "[" + $scope.data + "]";
        default:
          return "cicccio";
      }
    };
  }
]);

RigorixAdmin.controller("Users", [
  '$scope', '$http', function($scope, $http) {
    return $http.get("/api/tables/utente").success(function(table) {
      var k, v, _ref;
      if (table.length > 0) {
        $scope.table = {
          header: [],
          content: []
        };
        _ref = table[0];
        for (k in _ref) {
          v = _ref[k];
          $scope.table.header.push(k);
        }
        $scope.table.content = table;
        return console.log("table", table, $scope.table);
      }
    });
  }
]);

RigorixAdmin.directive("icon", function() {
  return {
    link: function(scope, element, attr) {
      return angular.element(element).prepend(angular.element('<span style="margin-right: 7px" class="glyphicon glyphicon-' + attr.icon + '"></span>'));
    }
  };
});

RigorixAdmin.directive("adminCell", function() {
  return {
    restrict: 'E',
    templateUrl: '/app/administr/templates/admin-table-cell.html',
    controller: 'TableCell',
    scope: {
      data: "=",
      column: "=",
      table: "="
    }
  };
});

RigorixAdmin.filter("locationToTitle", function() {
  return function(input) {
    var title;
    title = input.split("/").join("");
    return title.substring(0, 1).toUpperCase() + title.substring(1);
  };
});

RigorixAdmin.filter("logFileToPath", function() {
  return function(input) {
    return input.split(" ").join("_") + "_log.txt";
  };
});
