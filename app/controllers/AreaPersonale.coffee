Rigorix.controller "AreaPersonale", ['$scope', '$routeParams', '$location', '$rootScope', ($scope, $routeParams, $location, $rootScope) ->

  $scope.sections = [
    name: 'utente'
    icon: 'user'
  ,
    name: 'sfide'
    icon: 'gamepad'
  ,
    name: 'impostazioni'
    icon: 'cogs'
  ,
    name: 'messaggi'
    icon: 'envelope-o'
  ]
  $scope.section = $routeParams.section
  $scope.sectionPage = $routeParams.sectionPage

  $scope.$watch "section", ->
    $scope.loading = true

  if !$scope.section?
    $location.path "/area-personale/utente"

  $scope.isCurrentPage = (page)->
    if $routeParams.sectionPage? then $routeParams.sectionPage is page else false
]

#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Utente", ['$scope', 'Api', ($scope, Api) ->

  $scope.isLoading = true
  $scope.pages = [ 'palmares' ]
  Api.call "get", "badges",
    success: (json)->
      $scope.rewards = json.data

  $scope.userHasBadge = (reward) ->
    if reward.tipo == 'badge'
      for badge in $scope.currentUser.badges
        if reward.key_id == badge.key_id
          return true
    false
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Sfide", ['$scope', '$route', 'Api', ($scope, $route, Api) ->

  $scope.pages = [ 'sfide_da_giocare', 'in_attesa_di_risposta', 'archivio' ]
  $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare
  $scope.status = "loading"
  $scope.sfideInAttesaDiRisposta = []
  $scope.sfideArchivio = []

  $scope.$on "user:update", (event, userObject)->
    $scope.sfideDaGiocare = userObject.sfide_da_giocare

  if $scope.sectionPage is "in_attesa_di_risposta"
    Api.call "get", "sfide/pending/" + $scope.currentUser.id_utente,
      success: (json)->
        $scope.sfideInAttesaDiRisposta = json.data
        $scope.status = "done"

  if $scope.sectionPage is "archivio"
    Api.call "get", "/sfide/archivio/" + $scope.currentUser.id_utente,
      success: (json)->
        $scope.sfideArchivio = json.data
        $scope.status = "done"

  $scope.reload = ->
    do $route.reload
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Impostazioni", ['$scope', '$rootScope', 'UserServiceNew', '$modal', ($scope, $rootScope, UserServiceNew, $modal) ->

  $scope.isLoading = true
  $scope.pages = [ 'dati_utente', 'rigorix_mascotte', 'cancellazione_utente' ]

  $scope.currentUser.db_object.email_utente = $scope.currentUser.db_object.email if $scope.currentUser.db_object.email_utente is ""

  $scope.doChangePhoto = ->
    $.notify "Funzionalita' non ancora attiva"

  $scope.doUpdateUserData = ->
    $rootScope.$broadcast "show:loading"

    $scope.currentUser.$save
      id_utente: $scope.currentUser.id_utente
    ,
    (json)->
      $rootScope.$broadcast "hide:loading"
      $rootScope.$broadcast "user:update", json

      $.notify "Dati utente aggiornati correttamente", "success"

  $scope.doDeleteUser = ->
    $modal.open
      templateUrl:  '/app/templates/modals/user.delete.html',
      controller:    'Modals.DeleteUser',
      resolve:
        user: ->
          $scope.currentUser
]



