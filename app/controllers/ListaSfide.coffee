Rigorix.controller "ListaSfide", ($scope) ->

  console.log "+++++++++"


Rigorix.controller "ListaSfide.Sfida", ($scope) ->

  $scope.id_avversario = if $scope.sfida.id_sfidante == User.id_utente then $scope.sfida.id_sfidato else $scope.sfida.id_sfidante
  $scope.risultato = $scope.sfida.risultato.split ","
  $scope.punti = 0
  $scope.punti = 3 if $scope.currentUser.id_utente == $scope.sfida.id_vincitore
  $scope.punti = 1 if $scope.sfida.id_vincitore == 0

  switch $scope.sfida.stato
    when "0" then $scope.statoButton = 'lancia_sfida'
    when "1" then $scope.statoButton = 'sfida_lanciata'
    when "2" then $scope.statoButton = 'vedi_sfida'
    when "3" then $scope.statoButton = 'vinta_a_tavolino'
    else
      $scope.statoButton = $scope.sfida.stato
