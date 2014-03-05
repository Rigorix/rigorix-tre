Rigorix.controller "Header", ['$scope', '$rootScope', '$location', ($scope, $rootScope, $location) ->

  $scope.showUserPopout = false;

  $scope.$on "rootevent:click", (ev, args)->
    $scope.showUserPopout = false if $(args.event.target).parents(".user-container").size() is 0

  $scope.doClickLogo = ->
    $location.path "/"

  $scope.toggleVisibility = ->
    $scope.showUserPopout = !$scope.showUserPopout

]