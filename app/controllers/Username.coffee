Rigorix.controller "Username", ($scope, $rootScope, $modal, Api)->

  $scope.currentUser = $scope.$parent.currentUser;
  $scope.userTooltipMessage = if $scope.currentUser? and $scope.currentUser isnt false then "Clicca per sfidare" else "Entra per sfidare "

  if $scope.id_utente
    if RigorixStorage.users[$scope.id_utente]?
      $scope.userObject = RigorixStorage.users[$scope.id_utente]
    else
      Api.call "get", "users/" + $scope.id_utente + "/basic",
        success: (json)->
          $scope.userObject = json.data
          RigorixStorage.users[$scope.id_utente] = json.data

        error: (json)->
          $scope.userObject =
            id_utente: 0
          RigorixStorage.users[$scope.id_utente] = $scope.userObject

  $scope.doClickUsername = ->
    do $scope.doLanciaSfida

  $scope.doLanciaSfida = ->
    $rootScope.$broadcast "sfida:lancia", $scope.userObject

    $modal.open
      templateUrl:  '/app/templates/modals/sfida.html',
      controller: 'Modals.Sfida'
      resolve:
        sfida: ->
          id_sfidante: $scope.currentUser.id_utente
          id_avversario: $scope.userObject.id_utente
          id_sfida: false

