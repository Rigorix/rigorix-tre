

Rigorix.factory "Modals", ($scope, $modal)->

  success: (content)->
    $modal.open
      templateUrl:  '/app/templates/modals/success.html',
      controller:    'Modals.Success',
      resolve:
        content: ->
          content



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.Success", ($scope, $modal, $modalInstance, $rootScope, content)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.Success'
    modalClass: 'modal-success'

  $modalInstance.result.then ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.close = ->
    do $modalInstance.dismiss



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.Sfida", ($scope, $modal, $modalInstance, $rootScope, sfida)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.Sfida'
    modalClass: 'modal-play-sfida'

  $scope.sfida = if sfida? then sfida else
    id_sfidante: $scope.currentUser.id_utente
    id_avversario: user.id_utente
    id_sfida: false

  $modalInstance.result.then (selectedItem) ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.ok = ->
    do $modalInstance.close #I can pass watever I choose from the front end if needed

  $scope.cancel = ->
    do $modalInstance.dismiss



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.ViewSfida", ($scope, $modal, $modalInstance, $rootScope, sfida)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.VediSfida'
    modalClass: 'modal-view-sfida'

  $scope.sfida = if sfida? then sfida else
    id_sfidante: $scope.currentUser.id_utente
    id_avversario: user.id_utente
    id_sfida: false

  $modalInstance.result.then (selectedItem) ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.close = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:close"