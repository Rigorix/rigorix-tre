RigorixAdmin.controller "TableEdit", ["$scope", "$http", "$route", "$location", "$resource", ($scope, $http, $route, $location, $resource)->

  $scope.tableName = $route.current.params.table
  $scope.index = if $route.current.params.id? then parseInt $route.current.params.id, 10 else 0
  $scope.action = if $scope.index isnt 0 then "edit" else "create"

  $scope.backToList = ($event)->
    do $event.preventDefault
    $location.path "/tables/" + $scope.tableName

  $scope.doUpdate = ->
    $scope.data.$save table: $scope.tableName, index: $scope.index, (data)->
      console.log "Done saving", data

  $scope.resource = $resource "/api/tables/:table/:index",
    method  : "GET"
    isArray : false
    table   : "@table"
    index   : "@index"

  $scope.data = $scope.resource.get( table: $scope.tableName, index: $scope.index)

  console.log "data", $scope.data

#  if RigorixAdmin.tables[$scope.tableName]?
#    $scope.data = row for row in RigorixAdmin.tables[$scope.tableName] when row[RigorixAdmin.schemas[$scope.tableName].index] is $scope.index
#
#  else
#    $http.get("/api/tables/"+$scope.tableName+"/"+$scope.index).success (json)->
#      $scope.data = json

]