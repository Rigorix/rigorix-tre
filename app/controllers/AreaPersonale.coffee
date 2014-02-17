Rigorix.controller "AreaPersonale", ($scope, $routeParams, $location, $rootScope, Api) ->

  $scope.sections = [ 'utente', 'sfide', 'impostazioni', 'messaggi' ]
  $scope.section = $routeParams.section
  $scope.sectionPage = $routeParams.sectionPage

  $scope.onClickAreaPersonaleSection = (sec)->
    $rootScope.$broadcast "areapersonale:change:section", sec

  if !$scope.section?
    $location.path "/area-personale/utente"

  $scope.isCurrentPage = (page)->
    if $routeParams.sectionPage?
      $routeParams.sectionPage is page
    else
      false


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Utente", ($scope, Api, $rootScope, AppService) ->

  $scope.isLoading = true
  $scope.pages = [ 'palmares' ]
  Api.call "get", "badges",
    success: (json)->
      $scope.rewards = json.data

  $scope.badgesCount = $scope.currentUser.rewards.badges.length

  $scope.userHasBadge = (reward) ->
    ret = false
    if reward.tipo == 'badge'
      for badge in $scope.currentUser.rewards.badges
        if reward.key_id == badge.key_id
          ret = true
    ret



#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Sfide", ($scope, $route, Api) ->

  $scope.pages = [ 'sfide_da_giocare', 'in_attesa_di_risposta', 'archivio' ]
  $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare
  $scope.sfideInAttesaDiRisposta = []

  $scope.$on "user:update", (event, userObject)->
    $scope.sfideDaGiocare = userObject.sfide_da_giocare

  $scope.$on "areapersonale:change:section", (event, section)->
    if section == 'archivio'
      alert "here"
      Api.call "get", "/sfide/archivio/" + $scope.currentUser.id_utente,
        success: (lista)->
          $scope.sfideArchivio = lista
          console.log "LISTA sfideArchivio", lista

  Api.call "get", "sfide/pending/" + $scope.currentUser.id_utente,
    success: (json)->
      $scope.sfideInAttesaDiRisposta = json.data

  Api.call "get", "/sfide/archivio/" + $scope.currentUser.id_utente,
    success: (json)->
      $scope.sfideArchivio = json.data

  $scope.reload = ->
    do $route.reload


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Impostazioni", ($scope, $rootScope, UserServiceNew, $modal) ->

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




