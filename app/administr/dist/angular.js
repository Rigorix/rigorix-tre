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

RigorixAdmin.controller("Table", [
  "$scope", "$http", "$element", "$q", "$route", "$location", function($scope, $http, $element, $q, $route, $location) {
    $scope.tableName = $route.current.params.table;
    $scope.temp = [];
    return $scope.getFieldContent = function(fieldName, data, row) {
      var conf, relationId;
      if ($scope.schema.fields[fieldName] != null) {
        conf = $scope.schema.fields[fieldName];
        switch (conf.type) {
          case "index":
            return "#" + data;
          case "date":
            return moment(data).format("Do MMM YY");
          case "color":
            return '<div class="type-color" style="background: ' + data + ';">' + data + '</div>';
          case "userpicture":
            return '<img src="' + data + '" class="user-picture" />';
          case "relation":
            relationId = fieldName + '_' + row[$scope.schema.index] + '_promise';
            $http.get("/api/relations/" + conf.relation.table + "/" + data + "/" + conf.relation.showField).then(function(json) {
              return angular.element('#' + relationId).html(json.data);
            });
            return '<div id="' + relationId + '">Loading...</div>';
          case "boolean":
            if (data === 0 || data === "0" || data === false || data === "false") {
              return "false";
            } else {
              return "true";
            }
        }
      }
      return data;
    };
  }
]);

RigorixAdmin.controller("TableRow", [
  "$scope", "$location", "$resource", "$route", function($scope, $location, $resource, $route) {
    $scope.doEdit = function() {
      return $location.path("/tables/" + $scope.tableName + "/edit/" + $scope.row[$scope.schema.index]);
    };
    return $scope.doDelete = function() {
      var entry;
      if (confirm("delete")) {
        entry = $resource("/api/tables/" + $scope.tableName + "/" + $scope.row[$scope.schema.index], {
          method: "GET",
          isArray: false
        });
        entry.get().$delete();
        return $route.reload();
      }
    };
  }
]);

RigorixAdmin.controller("TableTh", [
  "$scope", "$element", function($scope, $element) {
    $scope.def = $scope.schema.fields[$scope.th];
    if ($scope.def != null) {
      $scope.fieldContent = $scope.def.name != null ? $scope.def.name : $scope.th;
      if ($scope.def.hidden === true) {
        return $element.parent("th").remove();
      }
    } else {
      return $scope.fieldContent = $scope.th;
    }
  }
]);

RigorixAdmin.controller("TableCell", [
  '$scope', '$rootScope', '$element', '$sce', function($scope, $rootScope, $element, $sce) {
    $scope.data = $scope.row[$scope.th];
    $scope.def = $scope.schema.fields[$scope.th];
    if ($scope.def != null) {
      if ($scope.def.hidden === true) {
        $element.parent("td").remove();
      }
    }
    $scope.fieldContent = $scope.getFieldContent($scope.th, $scope.data, $scope.row);
    return $scope.fieldContent = $sce.trustAsHtml($scope.fieldContent.toString());
  }
]);

RigorixAdmin.controller("TableEdit", [
  "$scope", "$http", "$route", "$location", "$resource", function($scope, $http, $route, $location, $resource) {
    $scope.tableName = $route.current.params.table;
    $scope.index = $route.current.params.id != null ? parseInt($route.current.params.id, 10) : 0;
    $scope.action = $scope.index !== 0 ? "edit" : "create";
    $scope.backToList = function($event) {
      $event.preventDefault();
      return $location.path("/tables/" + $scope.tableName);
    };
    $scope.doUpdate = function() {
      return $scope.data.$save({
        table: $scope.tableName,
        index: $scope.index
      }, function(data) {
        return console.log("Done saving", data);
      });
    };
    $scope.resource = $resource("/api/tables/:table/:index", {
      method: "GET",
      isArray: false,
      table: "@table",
      index: "@index"
    });
    $scope.data = $scope.resource.get({
      table: $scope.tableName,
      index: $scope.index
    });
    return console.log("data", $scope.data);
  }
]);

RigorixAdmin.controller("Tables", [
  '$scope', '$http', '$route', '$location', function($scope, $http, $route, $location) {
    $scope.tableName = $route.current.params.table;
    $scope.schema = "loading";
    $scope.doAdd = function() {
      return $location.path("/tables/" + $scope.tableName + "/create");
    };
    $http.get("/app/administr/schema/" + $scope.tableName + ".json").then(function(schema) {
      $scope.schema = schema.data;
      return RigorixAdmin.schemas[$scope.tableName] = $scope.schema;
    }, function() {
      return $scope.schema = false;
    });
    return $http.get("/api/tables/" + $scope.tableName).success(function(table) {
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
        return RigorixAdmin.tables[$scope.tableName] = table;
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

RigorixAdmin.filter("capitalize", function() {
  return function(input) {
    return input.substring(0, 1).toUpperCase() + input.substring(1);
  };
});
