Rigorix.controller "FirstLogin", ['$scope', 'UserService', '$location', '$rootScope', '$sce', 'notify', ($scope, UserService, $location, $rootScope, $sce, notify)->

  if !User? or User is false
    $location.path "/"
    return

  if User.dead is true
    $location.path "access-denied"

  $scope.datepickerOpened = false
  $scope.today = ->
    $scope.dt = new Date()

  $scope.today()

  $scope.openDatepicker = ->
    $scope.datepickerOpened = true

  $scope.newUser = UserService.get
    id_utente: User.id_utente
  ,
    (json) ->
      $scope.newUser = json
      $scope.newUser.db_object.email_utente = json.email if json.email_utente is ""
      $scope.social_url_trusted = $sce.trustAsResourceUrl $scope.newUser.social_url
      $route.reload()

  $scope.doActivateUser = ->

    if $scope.newUserForm.$valid
      $rootScope.$broadcast "show:loading"

      $scope.newUser.db_object.attivo = 1
      $scope.newUser.$save
        id_utente: $scope.newUser.id_utente
      ,
      (json)->
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "user:activated", json
    else
      notify.warn "Ci sono uno o più campi che non sono stati compilati."
]