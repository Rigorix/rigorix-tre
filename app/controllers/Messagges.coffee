Rigorix.controller 'Messages', ['$scope', '$rootScope', 'Api', 'MessageResource', '$modal', 'notify', ($scope, $rootScope, Api, MessageResource, $modal, notify)->

  $scope.stopUpdates = false

  $scope.currentPage = 1
  $scope.messagesCount = $scope.currentUser.totMessages
  $scope.messagesPerPage = RigorixConfig.messagesPerPage

  $scope.$on "user:update", ->
    do $scope.updateMessages

  $scope.$on "message:deleted", ()->
    do $scope.updateMessages

  $scope.$on "messages:deleted", ()->
    $scope.stopUpdates = false
    $(".table-messages tbody").find("input[type=checkbox]").prop "checked", null
    do $scope.updateMessages

  $scope.$watch "currentPage", ->
    do $scope.updateMessages

  $scope.toggleAllMessages = ->
    $(".table-messages tbody").find("input[type=checkbox]").prop "checked", $("[name=toggleAllMessages]").prop("checked")
    do $scope.checkMessagesActions
    false

  $scope.checkMessagesActions = ->
    $scope.stopUpdates = $(".table-messages tbody").find(":checked").size() > 0

  $scope.deleteMessages = ->
    messages = $(".table-messages tbody").find(":checked")
    ids = (angular.element(message).scope().message.id_mess for message in messages)

    Api.call "delete", "messages",
      params:
        ids: JSON.stringify ids
      success: (json)->
        notify.success "Messaggi cancellati correttamente"
        $rootScope.$broadcast "messages:deleted"

      error: ->
        notify.error "Errore nel cancellare i messaggi"

  $scope.updateMessages = ->
    if $scope.stopUpdates isnt true
      Api.call "get", "users/" + $scope.currentUser.id_utente + "/messages",
        params:
          start: ($scope.currentPage-1) * $scope.messagesPerPage
          count: $scope.messagesPerPage

        success: (json)->
          $scope.messages = json.data

  $scope.writeNewMessage = ->
    modalInsance = $modal.open
      templateUrl:  '/app/templates/modals/message.new.html',
      controller:    'Message.Modal.New',

  $scope.openMessage = (message)->
    $rootScope.$broadcast "message:read", message #if message.letto is 0

    $modal.open
      templateUrl:  '/app/templates/modals/message.html',
      controller:    'Message.Modal',
      resolve:
        message: ->
          message

  do $scope.updateMessages
]



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal', ['$scope', '$modal', '$modalInstance', '$rootScope', 'message', 'MessageResource', 'Api', 'notify', ($scope, $modal, $modalInstance, $rootScope, message, MessageResource, Api, notify)->

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal'
    modalClass: 'modal-read-message'

  $scope.editMode = false
  $scope.isTextCollapsed = false
  $scope.answer = "coming from scope"
  $scope.message = message

  $scope.reply = ->
    $scope.editMode = true
    $scope.isTextCollapsed = true
    $("textarea").focus()
    false

  $scope.sendReply = (answerText)->
    $scope.answer = $(".answer-text").val()

    $scope.message.testo = $scope.answer
    $scope.message.oggetto = 'RE: ' + $scope.message.oggetto
    $scope.message.id_receiver = $scope.message.id_sender
    $scope.message.id_sender = $rootScope.currentUser.id_utente
    $scope.message.letto = 0

    Api.call "post", "messages/reply/",
      message: $scope.message
      success: (json)->
        notify.success "Risposta mandata con successo"
        $rootScope.$broadcast "modal:close"
        do $modalInstance.dismiss

      error: ->
        notify.error "Errore nel spedire la risposta. Riprova più tardi."


  $scope.discard = ->
    $scope.isTextCollapsed = true
    $scope.editMode = false

  $scope.delete = ->
    Api.call "delete", "message/" + message.id_mess,
      success: (json)->
        do $modalInstance.dismiss

        notify.success "Messaggio cancellato correttamente"
        $rootScope.$broadcast "message:deleted", message

      error: ->
        notify.error "Errore nel cancellare il messaggio"

  $scope.cancel = ->
    do $modalInstance.dismiss
]



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal.New', ['$scope', '$modal', '$modalInstance', '$rootScope', 'Api', 'notify', '$timeout', ($scope, $modal, $modalInstance, $rootScope, Api, notify, $timeout)->

  $scope.receiver = ''
  $scope.newMessage =
    oggetto: ''
    id_sender: User.id_utente
    id_receiver: 0
    testo: ''
    letto: 0
    report: 0

  $timeout ->
    $("[autofocus]").focus()
  , 500

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal.New'
    modalClass: 'modal-write-message'

  $scope.getUsers = (usernameQuery)->
    Api.call "get", "users/search/username/" + usernameQuery,
      success: (json)->
        return json.data

  $scope.onSelectUser = (userObj)->
    $scope.newMessage.id_receiver = userObj.id_utente

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.sendNewMessage = ->

    if $scope.newMessage.id_receiver isnt 0 and $scope.newMessage.oggetto isnt ""
      Api.call "post", "messages",
        message: $scope.newMessage,
        success: ->
          do $scope.cancel
          notify.success "Messaggio mandato con successo"

        error: ->
          notify.error "Errore nel mandare il messaggio, riprova più tardi"

    else
      notify.warn "Devi scegliere destinatario e scrivere un oggetto per mandare il messaggio"

  $scope.cancel = ->
    do $modalInstance.dismiss
]
