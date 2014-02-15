Rigorix.controller "Sidebar", ($scope, AppService, $rootScope)->

  $scope.topUsers = []
  $scope.userTooltipMessage = if $scope.currentUser? and $scope.currentUser isnt false then "Clicca per sfidare" else "Entra per sfidare "

  AppService.getTopUsers (users)->
    $scope.topUsers = users

  $scope.doAuth = (event, social) ->
    do event.preventDefault

    $rootScope.$broadcast "user:logout"
    window.location.href = RigorixEnv.OAUTH_URL + social + "?return_to="+RigorixEnv.DOMAIN

  $scope.$on "message:read", (ev, message)->
    $scope.updateUserObject()