Rigorix.controller "Username", ($scope, $rootScope, $modal)->

  $scope.doLanciaSfida = ->
    $rootScope.$broadcast "sfida:lancia", $scope.user

    $modal.open
      templateUrl:  '/app/templates/modals/sfida.html',
      controller: 'Modals.Sfida'
      resolve:
        sfida: ->
          id_sfidante: $scope.currentUser.id_utente
          id_avversario: $scope.user.id_utente
          id_sfida: false

