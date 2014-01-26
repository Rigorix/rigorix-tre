Rigorix.controller "ListaSfide", ($scope) ->
  true


Rigorix.controller "ListaSfide.Sfida", ($scope, $modal) ->

  $scope.id_avversario = if $scope.sfida.id_sfidante == User.id_utente then $scope.sfida.id_sfidato else $scope.sfida.id_sfidante
  $scope.risultato = $scope.sfida.risultato.split ","

  $scope.sfida.dta_sfida = false if !moment($scope.sfida.dta_sfida).isValid()
  $scope.sfida.dta_conclusa = false if !moment($scope.sfida.dta_conclusa).isValid()
  $scope.sfida.id_avversario = $scope.id_avversario
  $scope.sfida.id_utente = $scope.currentUser.id_utente

  if $scope.currentUser.id_utente == $scope.sfida.id_vincitore
    $scope.punti = 3
    $scope.risultatoLabel = "won"
  else if $scope.sfida.id_vincitore == 0
    $scope.punti = 1
    $scope.risultatoLabel = "draw"
  else
    $scope.punti = 0
    $scope.risultatoLabel = "lose"

  switch $scope.sfida.stato
    when "0" then $scope.statoButton = 'lancia_sfida'
    when "1"
      $scope.statoButton = if $scope.sfida.id_sfidante is User.id_utente then 'lanciata' else 'rispondi'
    when "2" then $scope.statoButton = 'vedi_sfida'
    when "3" then $scope.statoButton = 'vinta_a_tavolino'
    else
      $scope.statoButton = $scope.sfida.stato

  $scope.doClickSfida = (action)->

    $modal.open
      templateUrl:  '/app/templates/modals/sfida.html',
      controller:    'Modals.Sfida',
      resolve:
        sfida: ->
          $scope.sfida