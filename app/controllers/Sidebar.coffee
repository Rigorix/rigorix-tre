Rigorix.controller "Sidebar", ($scope, Api, $rootScope)->

  $scope.topUsers = []

  Api.call "get", "users/top/10",
    success: (json)->
      $scope.topUsers = json.data

  $scope.doAuth = (event, social) ->
    do event.preventDefault

    $rootScope.$broadcast "show:loading",
      type: "logging-in"
      social: social
    $rootScope.$broadcast "user:logout"
    window.location.href = RigorixEnv.OAUTH_URL + social + "?return_to="+RigorixEnv.DOMAIN

  $scope.$on "message:read", (ev, message)->
    $scope.updateUserObject()