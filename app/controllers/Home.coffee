Rigorix.controller "Home", ($scope, AppService, UserServiceNew) ->

  $scope.updateResources = ->
    $scope.campione = AppService.getCampioneSettimana()
    $scope.activeUsers = AppService.getActiveUsers()

    if $scope.campione? and $scope.campione.id != 0
      $scope.campione = UserServiceNew.get
        parameters:
          id_utente: $scope.campione.id
      ,
        (json)->
          $scope.campioneObj = json.db_object

  do $scope.updateResources

  setInterval ()=>
    do $scope.updateResources
  , 60000