RigorixAdmin.controller "TableCell", ['$scope', '$rootScope', ($scope, $rootScope)->

  schema = $rootScope.TableDefinition[$scope.table]

  $scope.fieldContent = $scope.getFieldContent()

  $scope.getFieldContent = ->

    switch schema.fields[$scope.column].type
      when "number"
        return "["+$scope.data+"]"
      else "cicccio"


#  console.log "cell", $scope.column, , $scope.data

]