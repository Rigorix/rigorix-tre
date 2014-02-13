Rigorix.controller "Home", ($scope, AppService, UserServiceNew, Api) ->

  $scope.campione = false

  $scope.updateResources = ->
    $scope.activeUsers = AppService.getActiveUsers()
    $scope.campione = false

    Api.call "get", "users/champion/week",
      success: (champion)->
        $scope.campione = champion

      error: (message, status)->
        if status is 404
          $scope.campione = false

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000