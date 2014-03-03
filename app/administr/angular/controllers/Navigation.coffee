RigorixAdmin.controller "Navigation", ["$scope", "$location", ($scope, $location)->

  $scope.activeItem = $location.$$path

  $scope.$on "$routeChangeStart", (event, next, current)->
    $scope.activeItem = $location.$$path

    console.log "activeItem=", $scope.activeItem

]