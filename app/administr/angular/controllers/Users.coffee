RigorixAdmin.controller "Users", ['$scope', '$http', ($scope, $http)->

  $http.get("/api/tables/utente").success (table)->
    if table.length > 0
      $scope.table =
        header: []
        content: []
      for k,v of table[0]
        $scope.table.header.push k

      $scope.table.content = table

      console.log "table", table, $scope.table

]