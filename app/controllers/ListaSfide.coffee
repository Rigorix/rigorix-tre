Rigorix.controller "ListaSfide.Sfida", ['$scope', '$modal', ($scope, $modal) ->

  $scope.id_avversario = if $scope.sfida.id_sfidante == User.id_utente then $scope.sfida.id_sfidato else $scope.sfida.id_sfidante
  $scope.risultato = $scope.sfida.risultato.split ","

  $scope.sfida.dta_sfida = false if !moment($scope.sfida.dta_sfida).isValid()
  $scope.sfida.dta_conclusa = false if !moment($scope.sfida.dta_conclusa).isValid()
  $scope.sfida.id_avversario = $scope.id_avversario
  $scope.sfida.id_utente = User.id_utente

  $scope.punti = 0
  $scope.risultatoLabel = "lose"
  if User.id_utente == $scope.sfida.id_vincitore
    $scope.punti = 3
    $scope.risultatoLabel = "won"
  else if $scope.sfida.id_vincitore == 0
    $scope.punti = 1
    $scope.risultatoLabel = "draw"

  $scope.punti_rewards = if User.id_utente = $scope.sfida.id_sfidante then $scope.sfida.punti_sfidante - $scope.punti else $scope.sfida.punti_sfidato - $scope.punti

  $scope.risultatoLabel = 'ongoing' if $scope.sfida.stato < 2

  $scope.hasActiveButton = true

  switch $scope.sfida.stato
    when 0
      $scope.statoButton = 'lancia_sfida'
      $scope.statoButtonIcon = 'send'
    when 1
      $scope.statoButton = if $scope.sfida.id_sfidato is User.id_utente then 'rispondi' else 'lanciata'
      $scope.statoButtonIcon = if $scope.statoButton is 'lanciata' then 'send' else 'share-alt'
      $scope.hasActiveButton = $scope.statoButton is 'rispondi'
    when 2
      $scope.statoButton = 'vedi_sfida'
      $scope.statoButtonIcon = 'eye-open'
    when 3 then $scope.statoButton = 'vinta_a_tavolino'
    else
      $scope.statoButton = $scope.sfida.stato
      $scope.statoButtonIcon = ''
      $scope.hasActiveButton = false


  $scope.doClickSfida = (stato)->

    if stato is 'vedi_sfida'
      $modal.open
        templateUrl:  '/app/templates/modals/vedi-sfida.html',
        controller:    'Modals.ViewSfida',
        resolve:
          sfida: ->
            $scope.sfida
          currentUser: ->
            User

    else
      $modal.open
        templateUrl:  '/app/templates/modals/sfida.html',
        controller:    'Modals.Sfida',
        resolve:
          sfida: ->
            $scope.sfida
]