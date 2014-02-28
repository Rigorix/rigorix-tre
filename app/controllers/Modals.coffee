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



Rigorix.controller "Modals.ViewSfida", ['$scope', '$modal', '$modalInstance', '$rootScope', 'sfida', ($scope, $modal, $modalInstance, $rootScope, sfida)->

  $rootScope.$broadcast "modal:open",
    modalClass: 'modal-view-sfida'

  console.log "sfidadio", sfida

  $scope.id_sfida = sfida.id_sfida
  $scope.id_utente = $rootScope.currentUser.id_utente

  $scope.close = ->
    if $(".results-container").size() > 0
      $rootScope.$broadcast "modal:open",
        modalClass: "modal-show-end-match"
    else
      $rootScope.$broadcast "modal:close"
    do $modalInstance.dismiss
]



#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Modals.ShowEndMatch", ['$scope', '$modal', '$modalInstance', '$rootScope', 'sfida', 'Api', ($scope, $modal, $modalInstance, $rootScope, sfida, Api)->

  $rootScope.$broadcast "modal:open",
    modalClass: 'modal-show-end-match'

  $scope.sfida = sfida
  $scope.badges = []

  Api.call "get", "sfide/"+sfida.id_sfida+"/rewards/"+$rootScope.currentUser.id_utente,
    success: (json)->
      $scope.userRewards = json.data
      $scope.badges.push reward for reward in $scope.userRewards when reward.reward.tipo is "badge"
      $rootScope.$broadcast "show:newbadges" if $scope.badges.length > 0

  $scope.resultLabel = if sfida.id_vincitore is 0 then "draw" else if sfida.id_vincitore is $rootScope.currentUser.id_utente then "win" else "lose"

  $scope.setBadgesSeen = ->
    $rootScope.$broadcast "hide:newbadges"
    $scope.badges = []

  $scope.showSfida = ->
    $rootScope.$broadcast "show:sfida", $scope.sfida

  $scope.close = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:show",
      modalClass: 'modal-show-end-match'
]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller "Modals.DeleteUser", ['$scope', '$modal', '$modalInstance', '$rootScope', 'user', 'Api', ($scope, $modal, $modalInstance, $rootScope, user, Api)->

  $rootScope.$broadcast "modal:open",
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
    modalClass: 'modal-delete-user'

  $modalInstance.result.then (selectedItem) ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.cancel = ->
    do $modalInstance.dismiss
    $rootScope.$broadcast "modal:close"
]


#-----------------------------------------------------------------------------------------------------------------------



