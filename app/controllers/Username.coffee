Rigorix.controller "Username", ['$scope', '$rootScope', '$modal', 'Api', ($scope, $rootScope, $modal, Api)->

  $scope.currentUser = $scope.$parent.currentUser;
  $scope.userTooltipMessage = if $scope.currentUser? and $scope.currentUser isnt false then "Clicca per sfidare" else "Entra per sfidare "
  $scope.tooltip_placement = "top" if !$scope.tooltip_placement?
  $scope.tooltipDelay = if $scope.disabled is "disabled" then 9999999 else 1

  $scope.$watch "id_utente", (val)->
    if val isnt 0
      Api.getUserBasic(val).then (userObject)->
        $scope.userObject = userObject

  $scope.doClickUsername = ->
    do $scope.doLanciaSfida

  $scope.doLanciaSfida = ->
    if $rootScope.currentUser is false
      notify.warn "Devi entrare in Rigorix per poter sfidare un utente"
    else
      if $scope.disabled != 'disabled'
        $rootScope.$broadcast "sfida:lancia", $scope.userObject

        $modal.open
          templateUrl:  '/app/templates/modals/sfida.html',
          controller: 'Modals.Sfida'
          resolve:
            sfida: ->
              id_sfidante: $scope.currentUser.id_utente
              id_avversario: $scope.userObject.id_utente
              id_sfida: false

]