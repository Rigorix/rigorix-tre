Rigorix.controller "Sidebar", ($scope, AppService)->

  $scope.topUsers = []
  $scope.userTooltipMessage = if $scope.currentUser? and $scope.currentUser isnt false then "Clicca per sfidare" else "Entra per sfidare "

  AppService.getTopUsers (users)->
    $scope.topUsers = users

  $scope.doAuth = (social) ->
    auth_url = RigorixEnv.REMOTE + "/social_login.php?provider=" + social + "&origin="+RigorixEnv.DOMAIN+"&return_to="+RigorixEnv.REMOTE
    window.open auth_url, "hybridauth_social_sing_on", "location=0,status=0,scrollbars=0,width=800,height=500"

  $scope.$on "message:read", (ev, message)->
    $scope.updateUserObject()