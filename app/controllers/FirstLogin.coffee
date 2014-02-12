Rigorix.controller "FirstLogin", ($scope, UserServiceNew)->

  $scope.datepickerOpened = false

  $scope.openDatepicker = ->
    $scope.datepickerOpened = true

  $scope.newUser = UserServiceNew.get
    id_utente: User.id_utente
  ,
    (json) ->
