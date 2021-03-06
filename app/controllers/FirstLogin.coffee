Rigorix.controller "FirstLogin", ['$scope', 'UserService', '$location', '$rootScope', '$sce', 'notify', 'Api', ($scope, UserService, $location, $rootScope, $sce, notify, Api)->

  if !User? or User is false
    $location.path "/"
    return

  if User.dead is true
    $location.path "access-denied"

  $scope.auth_user_exist = $.cookie "auth_user_exist"
  $scope.validUsername = true
  $scope.isGettingUsernameValidation = false
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

  $scope.validateUsername = ->
    $scope.validUsername = false
    $scope.isGettingUsernameValidation = true
    Api.get "user/exists",
      params:
        username: $scope.newUser.db_object.username
      success: (json)->
        $scope.newUserForm.username.$setValidity "usernametaken", json.data isnt "true" || $scope.newUserForm.username.$viewValue is $scope.newUser.username


  $scope.useOldUser = ($event)->
    Api.post "users/rawdelete/" + User.id_utente
    $scope.doAuth $event, $scope.auth_user_exist.toLowerCase()

  $scope.discardOldUser = ->
    $scope.auth_user_exist = null
    $.removeCookie "auth_user_exist"

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
      notify.error "Ci sono uno o piu' campi che non sono stati compilati o contengono errori."
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
