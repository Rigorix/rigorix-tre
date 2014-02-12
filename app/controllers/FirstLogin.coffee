Rigorix.controller "FirstLogin", ($scope, UserServiceNew, $location)->

  if !User? or User is false
    $location.path "/"
    return

  $scope.datepickerOpened = false
  $scope.today = ->
    $scope.dt = new Date()

  $scope.today()

  $scope.openDatepicker = ->
    $scope.datepickerOpened = true

  $scope.newUser = UserServiceNew.get
    id_utente: User.id_utente
  ,
    (json) ->
      $scope.newUser = json
      $scope.newUser.email_utente = json.email if json.email_utente is ""

  $scope.doActivateUser = ->

    if $scope.newUserForm.$valid
      $rootScope.$broadcast "show:loading"

      $scope.newUser.attivo = 1
      $scope.newUser.$save
        id_utente: $scope.newUser.id_utente
      ,
      (json)->
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "user:activated", json
