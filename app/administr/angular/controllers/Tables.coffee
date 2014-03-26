RigorixAdmin.controller "Tables", ['$scope', '$http', '$route', ($scope, $http, $route)->

  $scope.tableName = $route.current.params.table
  $scope.schema = "loading"

  $http.get("/app/administr/schema/"+$scope.tableName+".json").then (schema)->
    $scope.schema = schema.data
    RigorixAdmin.schemas[$scope.tableName] = $scope.schema
  , ()->
    $scope.schema = false

  $http.get("/api/tables/"+$scope.tableName).success (table)->
    if table.length > 0
      $scope.table =
        header: []
        content: []
      for k,v of table[0]
        $scope.table.header.push k

      $scope.table.content = table
      RigorixAdmin.tables[$scope.tableName] = table

]