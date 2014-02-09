Rigorix.controller "Home", ($scope, AppService, UserServiceNew) ->

  $scope.updateResources = ->
    $scope.activeUsers = AppService.getActiveUsers()
    $scope.campione = false

    AppService.getCampioneSettimana (campione)->
      if campione.userObject? and campione.userObject.id_utente isnt 0
        $scope.campione = campione
      else
        $scope.campione = false

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000