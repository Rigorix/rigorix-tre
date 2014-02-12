Rigorix.controller "Sidebar", ($scope, AppService, Api, $window)->

  $scope.topUsers = []
  $scope.userTooltipMessage = if $scope.currentUser? and $scope.currentUser isnt false then "Clicca per sfidare" else "Entra per sfidare "

  AppService.getTopUsers (users)->
    $scope.topUsers = users

  $scope.doAuth = (social) ->
    window.location.href = RigorixEnv.OAUTH_URL + social + "?return_to="+RigorixEnv.DOMAIN

  $scope.$on "message:read", (ev, message)->
    $scope.updateUserObject()