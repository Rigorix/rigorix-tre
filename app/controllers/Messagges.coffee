Rigorix.controller 'Messages', ($scope, $rootScope, Api, UserServiceNew, $modal)->

#  Api.call "get", "users/" + $scope.currentUser.id_utente + "/messages/unread",
#    count: RigorixConfig.messagesPerPage
#    success: (json)->
#      $scope.messages = json.data

  $scope.$on "user:update", ->
    do $scope.updateMessages

  $scope.$on "message:deleted", ()->
    do $scope.updateMessages

  $scope.updateMessages = ->
    Api.call "get", "users/" + $scope.currentUser.id_utente + "/messages/unread",
      count: RigorixConfig.messagesPerPage
      success: (json)->
        $scope.messages = json.data

  $scope.writeNewMessage = ->
    $modal.open
      templateUrl:  '/app/templates/modals/message.new.html',
      controller:    'Message.Modal.New',

  $scope.openMessage = (message)->

    if message.letto is 0
      message.letto = 1;
      $rootScope.$broadcast "message:read", message

    $modal.open
      templateUrl:  '/app/templates/modals/message.html',
      controller:    'Message.Modal',
      resolve:
        message: ->
          message

  do $scope.updateMessages

#  Pagination
#  $scope.page = 1
#  $scope.currentPage = 1
#  $scope.totMessages = Number $scope.currentUser.totMessages



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal', ($scope, $modal, $modalInstance, $rootScope, message, MessageResource)->

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal'
    modalClass: 'modal-read-message'

  $scope.editMode = false
  $scope.isTextCollapsed = false
  $scope.answer = "<br><br>" + User.username

  messageRes = MessageResource.get
    id_message: message.id_mess

  console.log "message", messageRes

  $scope.message = message

#  TODO: add this to Api call
#  AppService.putMessageRead
#    id_message: message.id_mess

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
    Api.call "post", "message/new",
      text: answerText
      message: $scope.message
      success: (json)->
        $.notify "Risposta mandata con successo", "success"
        $rootScope.$broadcast "modal:close"
        do $modalInstance.dismiss
      error: ->
        $.notify "Errore nel spedire la risposta.<br>Riprova pi$ugrave; tardi.", "error"


  $scope.discard = ->
    $scope.isTextCollapsed = true
    $scope.editMode = false

  $scope.delete = ->
    Api.call "delete", "message/" + message.id_mess,
      success: (json)->
        do $modalInstance.dismiss
        $.notify "Messaggio cancellato correttamente", "success"
        $rootScope.$broadcast "message:deleted", message

      error: ->
        $.notify "Errore nel cancellare il messaggio", "error"

  $scope.cancel = ->
    do $modalInstance.dismiss



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal.New', ($scope, $modal, $modalInstance, $rootScope, Api)->

  $scope.newMessage =
    oggetto: ''
    id_sender: User.id_utente
    id_receiver: 0
    receiver: ''
    testo: ''
    letto: 0
    dta_mess: '_V_NOW_'
    report: 0

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal.New'
    modalClass: 'modal-write-message'

  $scope.getUsers = (usernameQuery)->
    Api.call "get", "users/search/username/" + usernameQuery,
      success: (json)->
        return json.data

  $scope.onSelectUser = (obj)->
    console.log "obj, ", obj

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.sendNewMessage = (newMessage)->
    Api.call "post", "messages/new",
      message: newMessage,
      success: (json)->
        $.notify "Messaggio mandato con successo", "success"

  $scope.cancel = ->
    do $modalInstance.dismiss