Rigorix.factory "Modals", ['$scope', '$modal', ($scope, $modal)->

  success: (content)->
    $modal.open
      templateUrl:  '/app/templates/modals/success.html',
      controller:    'Modals.Success',
      resolve:
        content: ->
          content

]

#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.Success", ['$scope', '$modal', '$modalInstance', '$rootScope', ($scope, $modal, $modalInstance, $rootScope)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.Success'
    modalClass: 'modal-success'

  $modalInstance.result.then ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.close = ->
    do $modalInstance.dismiss

]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.Sfida", ['$scope', '$modal', '$modalInstance', '$rootScope', 'sfida', ($scope, $modal, $modalInstance, $rootScope, sfida)->

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
]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.ViewSfida", ['$scope', '$modal', '$modalInstance', '$rootScope', 'sfida', 'currentUser', ($scope, $modal, $modalInstance, $rootScope, sfida, currentUser)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.VediSfida'
    modalClass: 'modal-view-sfida'

  $scope.currentUser = currentUser if currentUser?
  console.log "$scope.currentUser", $scope.currentUser, currentUser

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
]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.DeleteUser", ['$scope', '$modal', '$modalInstance', '$rootScope', 'user', 'Api', ($scope, $modal, $modalInstance, $rootScope, user, Api)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.DeleteUser'
    modalClass: 'modal-delete-user'

  $modalInstance.result.then (selectedItem) ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.doDeleteUser = ->
    Api.call 'post', 'users/delete',
      user: user
      success: (json)->
        if json.status == "success"
          do $modalInstance.dismiss
          $rootScope.$broadcast "modal:close"
          $rootScope.$broadcast "user:logout"
          $.notify "Ti e' stata inviata una mail per la cancellazione"


      error: ->
        console.log "error", arguments

  $scope.cancel = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:close"
]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Modals.NewUser", ['$scope', '$modal', '$modalInstance', '$rootScope', 'user', ($scope, $modal, $modalInstance, $rootScope, user)->

  $rootScope.$broadcast "modal:open",
    controller: 'Modals.DeleteUser'
    modalClass: 'modal-delete-user'

  $modalInstance.result.then (selectedItem) ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.cancel = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:close"
]