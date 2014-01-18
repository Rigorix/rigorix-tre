Rigorix.controller "Home", ($scope, UserService) ->

  $scope.updateResources = ->
    $scope.campione = UserService.getCampioneSettimana()
    $scope.activeUsers = UserService.getActiveUsers()

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000