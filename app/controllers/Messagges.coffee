Rigorix.controller 'Messages', ($scope, AppService, $modal)->

  $scope.messages = AppService.getMessages
    count: RigorixConfig.messagesPerPage

  $scope.openMessage = (message)->

    $modal.open
      templateUrl:  '/app/templates/modals/message.html',
      controller:    'Message.Modal',
      resolve:
        message: ->
          message


#  Pagination
  $scope.page = 1
  $scope.currentPage = 1
  $scope.totMessages = Number $scope.currentUser.totMessages

  $scope.$watch 'currentPage', (newVal, oldVal)->
    console.log "watch currentPages newval", newVal

  $scope.pageChanged = (page)=>
    console.log "onSelectPage", page



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal', ($scope, $modal, $modalInstance, $rootScope, message)->

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal'
    modalClass: 'modal-read-message'

  $scope.message = message

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.cancel = ->
    do $modalInstance.dismiss

  console.log "Load message", message
