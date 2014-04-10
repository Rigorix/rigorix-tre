Rigorix.controller "AreaPersonale", ['$scope', '$routeParams', '$location', 'notify', '$timeout', ($scope, $routeParams, $location, notify, $timeout) ->

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
    icon: 'inbox'
  ]

  $scope.section = $routeParams.section
  $scope.sectionPage = $routeParams.sectionPage

  $scope.$on "$routeChangeStart", ->
    $scope.loading = true

  $scope.$on "$routeChangeSuccess", ->
    $scope.loading = false
    $timeout ->
      notify.animate ".area-personale-page-container", "fadeInDown"
    , 0

  if !$scope.section?
    $location.path "/area-personale/utente"

  $scope.isCurrentPage = (page)->
    if $routeParams.sectionPage? then $routeParams.sectionPage is page else false
]

#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Utente", ['$scope', 'Api', ($scope, Api) ->

  $scope.pages = [ 'palmares' ]
  $scope.loadingBadges = !Rigorix.Storage.badges? or Rigorix.Storage.badges.length is 0

  if $scope.loadingBadges
    Api.call "get", "badges",
      success: (json)->
        $scope.loadingBadges = false
        $scope.rewards = json.data
        Rigorix.Storage.badges = $scope.rewards
  else
    $scope.rewards = Rigorix.Storage.badges

  Api.post "users/" + $scope.currentUser.id_utente + "/badges/seen"
  $scope.currentUser.has_new_badges = 0

  $scope.userHasBadge = (reward) ->
    if reward.tipo == 'badge'
      for badge in $scope.currentUser.badges
        if reward.key_id == badge.key_id
          return true
    false
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Sfide", ['$scope', '$route', 'Api', ($scope, $route, Api) ->

  $scope.pages = [
    name: 'da_giocare'
    icon: 'play'
  ,
    name: 'in_attesa'
    icon: 'coffee'
  ,
    name: 'archivio'
    icon: 'archive'
  ]
  $scope.sfideDaGiocare = $scope.currentUser.sfide_da_giocare
  $scope.status = "loading"
  $scope.sfideInAttesaDiRisposta = []
  $scope.sfideArchivio = []
  $scope.sfideArchivioCount = 0
  $scope.sfideArchivioPerPage = RigorixConfig.sfidePerPage
  $scope.sfideArchivioCurrentPage = 1

  $scope.$on "user:update", (event, userObject)->
    $scope.sfideDaGiocare = userObject.sfide_da_giocare

  $scope.$watch "sfideArchivioCurrentPage", ->
    do $scope.getSfideArchivio

  $scope.getSfideArchivio = ->
    Api.call "get", "/sfide/archivio/" + $scope.currentUser.id_utente,
      params:
        start: ($scope.sfideArchivioCurrentPage-1) * $scope.sfideArchivioPerPage
        count: $scope.sfideArchivioPerPage

      success: (json)->
        $scope.sfideArchivio = json.data.sfide
        $scope.sfideArchivioCount = json.data.count
        $scope.status = "done"



  if $scope.sectionPage is "in_attesa"
    Api.call "get", "sfide/pending/" + $scope.currentUser.id_utente,
      success: (json)->
        $scope.sfideInAttesaDiRisposta = json.data
        $scope.status = "done"

  if $scope.sectionPage is "archivio"
    do $scope.getSfideArchivio

  $scope.reload = ->
    do $route.reload
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "AreaPersonale.Impostazioni", ['$scope', '$rootScope', '$modal', '$upload', 'notify', ($scope, $rootScope, $modal, $upload, notify) ->

  $scope.isLoading = true
  $scope.pages = [
    name: 'dati_utente'
    icon: 'user'
  ,
    name: 'mascotte'
    icon: 'male'
  ,
    name: 'cancellazione'
    icon: 'trash-o'
  ]
  $scope.currentUser.db_object.email_utente = $scope.currentUser.db_object.email if $scope.currentUser.db_object.email_utente is ""
  $scope.storedPicture = $scope.currentUser.db_object.picture

  $scope.doUpdateUserData = ->
    $rootScope.$broadcast "show:loading"

    $scope.currentUser.$save
      id_utente: $scope.currentUser.id_utente
    ,
    (json)->
      $rootScope.$broadcast "hide:loading"
      $rootScope.$broadcast "user:update", json

      notify.success "Dati utente aggiornati correttamente"

  $scope.doDeleteUser = ->
    $modal.open
      templateUrl:  '/app/templates/modals/user.delete.html',
      controller:    'Modals.DeleteUser',
      resolve:
        user: ->
          $scope.currentUser

  $scope.onFileSelect = ($files) ->
    $rootScope.$broadcast "show:loading"
    $scope.upload = $upload.upload(
      url: "/api/users/save/picture"
      file: $files[0]
    ).success((data, status, headers, config) ->
      $rootScope.$broadcast "hide:loading"
      if data.profile_picture?
        $scope.currentUser.db_object.picture = data.profile_picture
        notify.warn
          text: "Clicca 'salva' per impostare la nuova immagine"
          icon: "picture-o"

    ).error((message, status)->
      $rootScope.$broadcast "hide:loading"
      notify.error "Errore nel caricare l'immagine ("+message+")"
    )

  $scope.doAnnullaChangePicture = ->
    $scope.currentUser.db_object.picture = $scope.storedPicture

  $scope.doSaveNewPicture = ->
    $scope.storedPicture = $scope.currentUser.db_object.picture
    do $scope.doUpdateUserData


]



