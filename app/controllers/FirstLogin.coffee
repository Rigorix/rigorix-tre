Rigorix.controller "FirstLogin", ['$scope', 'UserService', '$location', '$rootScope', '$sce', 'notify', 'Api', ($scope, UserService, $location, $rootScope, $sce, notify, Api)->

  if !User? or User is false
    $location.path "/"
    return

  if User.dead is true
    $location.path "access-denied"

  $scope.auth_user_exist = $.cookie "auth_user_exist"
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
      $scope.picture_trusted = $sce.trustAsResourceUrl $scope.newUser.picture
      $route.reload()

  $scope.useOldUser = ($event)->
    Api.post "users/rawdelete/" + User.id_utente
    $scope.doAuth $event, $scope.auth_user_exist.toLowerCase()

  $scope.discardOldUser = ->
    $scope.auth_user_exist = null
    $.removeCookie "auth_user_exist"

  $scope.doActivateUser = ->

    if $scope.newUserForm.$valid
      $rootScope.$broadcast "show:loading"

      Api.get "user/newuser/check"
      $scope.newUser.db_object.attivo = 1
      $scope.newUser.$save
        id_utente: $scope.newUser.id_utente
      ,
      (json)->
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "user:activated", json
    else
      notify.warn "Ci sono uno o piu' campi che non sono stati compilati."
]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "FirstLogin.SameEmail", ['$scope', '$rootScope', 'Api', ($scope, $rootScope, Api)->

  $scope.userAlreadyPresent = false

  Api.get "users/byemail/" + $.cookie("auth_same_email"),
    success: (json)->
#      alert "let's go"
      console.log "json.data.id_utente", json.data.id_utente
      $scope.userAlreadyPresent = json.data.id_utente#Api.getUserBasic json.data.id_utente

  $rootScope.$broadcast "hide:loading"

]