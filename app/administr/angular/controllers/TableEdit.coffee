RigorixAdmin.controller "TableEdit", ["$scope", "$http", "$route", "$location", "$resource", ($scope, $http, $route, $location, $resource)->

  $scope.tableName = $route.current.params.table
  $scope.index = parseInt $route.current.params.id, 10

  $scope.backToList = ($event)->
    do $event.preventDefault
    $location.path "/tables/" + $scope.tableName

  $scope.doUpdate = ->
    $scope.data.$save (data)->
      console.log "Done saving", data

  $scope.resource = $resource "/api/tables/"+$scope.tableName+"/"+$scope.index,
    method      : "GET"
    isArray     : false

  $scope.data = $scope.resource.get()


#  if RigorixAdmin.tables[$scope.tableName]?
#    $scope.data = row for row in RigorixAdmin.tables[$scope.tableName] when row[RigorixAdmin.schemas[$scope.tableName].index] is $scope.index
#
#  else
#    $http.get("/api/tables/"+$scope.tableName+"/"+$scope.index).success (json)->
#      $scope.data = json

]