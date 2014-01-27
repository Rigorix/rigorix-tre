Rigorix.controller "GamePlay", ($scope, $timeout, $rootScope, $modal, Modals, SfideService)->

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

  $scope.submitButtonLabel = if $scope.sfida.id_sfida isnt false then "Rispondi" else "Lancia"
  $scope.id_utente_avversario = if $scope.sfida.id_sfida isnt false then $scope.sfida.id_sfidante else $scope.sfida.id_avversario

  $scope.randomPlaySet = ->

    $timeout =>
      for row in @rows
        randTiro = Math.ceil(Math.random(0,1) * 3) - 1
        randParata = Math.ceil(Math.random(0,1) * 3) - 1

        do $("#gameSetBox_tiro_" + row.index + " .game-tile[value="+randTiro+"]").click
        do $("#gameSetBox_parata_" + row.index + " .game-tile[value="+randParata+"]").click
    , 0

  $scope.resetPlaySet = ->
    $('.game-tile').removeClass "active"

    for index, value of $scope.matrix
      $scope.matrix[index].tiro = false
      $scope.matrix[index].parata = false

  $scope.submitSfida = ->

    for index, value of $scope.matrix
      return alert "errore, compila tutto" if value.tiro is false or value.parata is false

    do @sendSfida

  $scope.sendSfida = ->
    $rootScope.$broadcast "show:loading"

    matrix = {}
    for index, value of $scope.matrix
      row = Number(index) + 1
      matrix['tiro' + row] = value.tiro
      matrix['parata' + row] = value.parata

    SfideService.sendSfida
      value: $scope.sfida.id_sfida
      sfida_matrix: JSON.stringify(matrix)
      sfida: $scope.sfida
    ,
      (json)->
        $rootScope.$broadcast "hide:loading"

        if json.status == "success"
          Modals.success
            title: "titolo"
            text: "testo"

          alert "Sfida inserita correttamente"
        else
          alert "Errore " + json.error_code


#  ------------------------------------------------------------------------


Rigorix.controller "GamePlay.Tile", ($scope, $element)->

  $scope.tileValue = false
  $scope.GamePlay = $scope.$parent.$parent

  $scope.subject = if $scope.tileType is "parata" then "portiere" else "pallone"
  $scope.dir =
    sx: if $scope.tileType is "parata" then "Sx" else ""
    dx: if $scope.tileType is "parata" then "Dx" else ""

  $scope.setTileValue = (value)->
    $scope.tileValue = value
    $element.find('.game-tile').removeClass "active"
    $element.find('.game-tile[value='+value+']').addClass 'active'

    $scope.GamePlay.matrix[$scope.row.index][$scope.tileType] = value

