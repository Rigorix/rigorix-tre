Rigorix.controller 'Messages', ($scope, $rootScope, UserServiceNew, $modal)->

  $rootScope.textAngularOpts = {
    toolbar: [
      ['bold', 'italics', 'ul', 'ol', 'redo', 'undo']
    ],
    classes: {
      focussed: "focussed",
      toolbar: "btn-toolbar",
      toolbarGroup: "btn-group",
      toolbarButton: "btn btn-default",
      toolbarButtonActive: "active",
      textEditor: 'form-control',
      htmlEditor: 'form-control'
    }
  }

  $scope.messages = UserServiceNew.get
    id_utente: User.id_utente
    parameter: 'messages'
    count: RigorixConfig.messagesPerPage

  $scope.openMessage = (message)->

    message.letto = 1;

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



Rigorix.controller 'Message.Modal', ($scope, $modal, $modalInstance, $rootScope, message, UserServiceNew, AppService)->

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal'
    modalClass: 'modal-read-message'

  $scope.editMode = false
  $scope.isTextCollapsed = false
  $scope.answer = "<br><br>" + User.username

  $scope.message = message

  AppService.putMessageRead
    id_message: message.id_mess

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.reply = ->
    $scope.editMode = true
    $scope.isTextCollapsed = true
    angular.element(".message-text").click()
    angular.element(".ta-editor").focus()

  $scope.sendReply = (answerText)->
    AppService.postReply
      text: answerText
      message: $scope.message

  $scope.discard = ->
    $scope.isTextCollapsed = true
    $scope.editMode = false

  $scope.delete = ->
    AppService.deleteMessage
      value: message.id_mess

  $scope.cancel = ->
    do $modalInstance.dismiss