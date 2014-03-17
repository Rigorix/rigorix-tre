Rigorix.controller "Directive.InlineLoader", ['$scope', ($scope)->
  $scope.icon = "gear" if !$scope.icon?
  $scope.text = "Caricamento in corso..." if !$scope.text?

]