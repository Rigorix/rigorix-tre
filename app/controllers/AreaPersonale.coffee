Rigorix.controller "AreaPersonale", ($scope, $routeParams, $location) ->

  $scope.sections = [ 'utente', 'sfide', 'impostazioni', 'messaggi' ]
  $scope.section = $routeParams.section
  $scope.sectionPage = $routeParams.sectionPage

  $scope.onClickAreaPersonaleSection = (sec)->
    $scope.$emit "areapersonale:change:section", sec

  if !$scope.section?
    $location.path "/area-personale/utente"

  $scope.isCurrentPage = (page)->
    if $routeParams.sectionPage?
      $routeParams.sectionPage is page
    else
      false


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Utente", ($scope, AppService) ->

  $scope.isLoading = true
  $scope.pages = [ 'palmares' ]
  $scope.rewards = AppService.getBadges ->
    $(".game-loader").remove()
    $scope.isLoading = false

  $scope.userHasBadge = (reward) ->
    ret = false
    if reward.tipo == 'badge'
      for badge in $scope.currentUser.rewards.badges
        if reward.key_id == badge.key_id
          ret = true
    ret



#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Sfide", ($scope, SfideService, $route) ->

  $scope.isLoading = true
  $scope.pages = [ 'sfide_da_giocare', 'in_attesa_di_risposta', 'archivio' ]

  $scope.$on "currentuser:update", (event, userObject)->
    $scope.sfideDaGiocare = userObject.sfide_da_giocare

  $scope.loadSfide = ()->
    $scope.isLoading = false if $scope.sectionPage is "sfide_da_giocare"
    $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare

    if $scope.sectionPage == 'archivio'
      $scope.sfideArchivio = SfideService.getArchivioSfide
        limit_start: 0
        limit_count: 15
      ,
        $scope.isLoading = false

    if $scope.sectionPage == 'in_attesa_di_risposta'
      $scope.sfideInAttesaDiRisposta = SfideService.getSfidePending $scope.isLoading = false

  do $scope.loadSfide

  $scope.reload = ->
    do $route.reload

#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Impostazioni", ($scope) ->

  $scope.isLoading = true
  $scope.pages = [ 'dati_utente', 'rigorix_mascotte', 'cancellazione_utente' ]