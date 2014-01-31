Rigorix.controller "Home", ($scope, AppService) ->

  $scope.updateResources = ->
    $scope.campione = AppService.getCampioneSettimana()
    $scope.activeUsers = AppService.getActiveUsers()

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000