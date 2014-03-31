Rigorix.controller "GamePlay", ['$scope', '$timeout', '$rootScope', '$modal', 'Api', 'notify', ($scope, $timeout, $rootScope, $modal, Api, notify)->

  $scope.rows = [
    { index: 0 },
    { index: 1 },
    { index: 2 },
    { index: 3 },
    { index: 4 }
  ]
  $scope.matrix =
    '0':
      tiro: false
      parata: false
    '1':
      tiro: false
      parata: false
    '2':
      tiro: false
      parata: false
    '3':
      tiro: false
      parata: false
    '4':
      tiro: false
      parata: false

  $timeout ->
    do $("[autofocus]").focus
  , 500

  $scope.submitButtonLabel = if $scope.sfida.id_sfida isnt false then "Rispondi" else "Lancia"
  $scope.id_utente_avversario = if $scope.sfida.id_sfida isnt false then $scope.sfida.id_sfidante else $scope.sfida.id_avversario

  $scope.randomPlaySet = ->

    $timeout =>
      for row in @rows
        randTiro = Math.ceil(Math.random(0,1) * 3) - 1
        randParata = Math.ceil(Math.random(0,1) * 3) - 1

        do $("#gameSetBox_tiro_" + row.index + " .game-tile[value="+randTiro+"]").click
        do $("#gameSetBox_parata_" + row.index + " .game-tile[value="+randParata+"]").click

      $timeout ->
        $rootScope.$broadcast "gameplay:tile:clicked"
      , 400
    , 0

  $scope.resetPlaySet = ->
    $('.game-tile').removeClass "active"

    for index, value of $scope.matrix
      $scope.matrix[index].tiro = false
      $scope.matrix[index].parata = false

    $(".carousel-indicators li").removeClass "ok"

    do $(".parata-slides li:first").click
    do $(".tiro-slides li:first").click
    false

  $scope.doChangeAvversario = ->
    $scope.sfida.id_avversario = 0

  $scope.$on "gameplay:tile:clicked", ->
    for index,value of $scope.matrix
      return if value.tiro is false or value.parata is false

    notify.animate ".btn-submit-sfida", "shake"

  $scope.submitSfida = ->

    for index,value of $scope.matrix
      if value.tiro is false or value.parata is false
        tipo = if value.tiro is false then "tiro" else "parata"
        $timeout =>
          do $("."+tipo+"-slides li:eq("+index+")").click
        , 0
        return notify.error "Devi impostare tutti i 5 tiri e parate"

    return notify.error "Devi scegliere un avversario" if $scope.sfida.id_avversario is 0

    do @sendSfida

  $scope.sendSfida = ->
    $rootScope.$broadcast "show:loading"

    matrix = {}
    for index, value of $scope.matrix
      matrix['tiro' + index] = value.tiro
      matrix['parata' + index] = value.parata

    Api.call "post", "sfide/set",
      sfida_matrix: JSON.stringify(matrix)
      sfida: $scope.sfida
      success: (json)->
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "modal:close"
        do $scope.cancel

        if json.data.stato is 2
          $modal.open
            templateUrl:  '/app/templates/modals/show-end-match.html',
            controller:    'Modals.ShowEndMatch',
            resolve:
              sfida: ->
                json.data
              currentUser: ->
                User

        else
          notify.success "Sfida mandata con successo"

        $rootScope.$broadcast "user:refresh"

      error: ->
        $rootScope.$broadcast "hide:loading"
        $rootScope.$broadcast "modal:close"
        do $scope.cancel
        notify.error "Errore nel mandare la sfida"

]


#  ------------------------------------------------------------------------


Rigorix.controller "GamePlay.Tile", ['$scope', '$rootScope', '$element', '$timeout', ($scope, $rootScope, $element, $timeout)->

  $scope.tileValue = false
  $scope.GamePlay = $scope.$parent.$parent
  $scope.sliderLi = $("."+$scope.tileType+"-slides li:eq("+$scope.row.index+")")

  $scope.subject = if $scope.tileType is "parata" then "portiere" else "pallone"
  $scope.dir =
    sx: if $scope.tileType is "parata" then "Sx" else ""
    dx: if $scope.tileType is "parata" then "Dx" else ""

  $scope.setTileValue = (value)->
    $rootScope.$broadcast "gameplay:tile:clicked"

    $scope.tileValue = value
    $element.find('.game-tile').removeClass "active"
    $element.find('.game-tile[value='+value+']').addClass 'active'

    $timeout =>
      $scope.updateMatrixStatus value
    , 0

  $scope.updateMatrixStatus = (value)->
    $scope.GamePlay.matrix[$scope.row.index][$scope.tileType] = value
    $scope.sliderLi.addClass "ok" if $scope.GamePlay.matrix[$scope.row.index][$scope.tileType] isnt false
    console.log "vai"
    do $scope.sliderLi.next().click if $scope.row.index != $scope.GamePlay.rows.length-1
    false

]
