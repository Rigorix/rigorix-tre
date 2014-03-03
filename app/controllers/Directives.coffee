Rigorix.controller "Directive.InlineLoader", ['$scope', ($scope)->

  console.log "$scope.text", $scope.text

  $scope.icon = "gear" if !$scope.icon?
  $scope.text = "Caricamento in corso..." if !$scope.text?

]