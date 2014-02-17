Rigorix.controller "Sidebar", ($scope, AppService, $rootScope)->

  $scope.topUsers = []

  AppService.getTopUsers (users)->
    $scope.topUsers = users

  $scope.doAuth = (event, social) ->
    do event.preventDefault

    $rootScope.$broadcast "user:logout"
    window.location.href = RigorixEnv.OAUTH_URL + social + "?return_to="+RigorixEnv.DOMAIN

  $scope.$on "message:read", (ev, message)->
    $scope.updateUserObject()