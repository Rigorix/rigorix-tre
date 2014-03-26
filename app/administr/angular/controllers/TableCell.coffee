RigorixAdmin.controller "TableCell", ['$scope', '$rootScope', '$element', '$sce', ($scope, $rootScope, $element, $sce)->

  $scope.data = $scope.row[$scope.th]
  $scope.def = $scope.schema.fields[$scope.th]

  if $scope.def?
    if $scope.def.hidden is true
      $element.parent("td").remove()

  $scope.fieldContent = $scope.getFieldContent $scope.th, $scope.data, $scope.row
  $scope.fieldContent = $sce.trustAsHtml $scope.fieldContent.toString()

]