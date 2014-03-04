Rigorix.controller 'Messages', ['$scope', '$rootScope', 'Api', 'MessageResource', '$modal', ($scope, $rootScope, Api, MessageResource, $modal)->

  $scope.$on "user:update", ->
    do $scope.updateMessages

  $scope.$on "message:deleted", ()->
    do $scope.updateMessages

  $scope.updateMessages = ->
    Api.call "get", "users/" + $scope.currentUser.id_utente + "/messages",
      count: RigorixConfig.messagesPerPage
      success: (json)->
        $scope.messages = json.data

  $scope.writeNewMessage = ->
    $modal.open
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


#  Pagination
#  $scope.page = 1
#  $scope.currentPage = 1
#  $scope.totMessages = Number $scope.currentUser.totMessages



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal', ['$scope', '$modal', '$modalInstance', '$rootScope', 'message', 'MessageResource', 'Api', ($scope, $modal, $modalInstance, $rootScope, message, MessageResource, Api)->

  $rootScope.$broadcast "modal:open",
    controller: 'Message.Modal'
    modalClass: 'modal-read-message'

  $scope.editMode = false
  $scope.isTextCollapsed = false
  $scope.answer = "<br><br>" + User.username
  $scope.message = message

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
    $scope.message.testo = answerText
    $scope.message.oggetto = 'RE: ' + $scope.message.oggetto
    $scope.message.id_receiver = $scope.message.id_sender
    $scope.message.id_sender = $rootScope.currentUser.id_utente
    $scope.message.letto = 0

    Api.call "post", "messages/reply/",
      message: $scope.message
      success: (json)->
        $.notify "Risposta mandata con successo", "success"
        $rootScope.$broadcast "modal:close"
        do $modalInstance.dismiss
      error: ->
        $.notify "Errore nel spedire la risposta. Riprova più tardi.", "error"


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
]


#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal.New', ['$scope', '$modal', '$modalInstance', '$rootScope', 'Api', ($scope, $modal, $modalInstance, $rootScope, Api)->

  $scope.receiver = ''
  $scope.newMessage =
    oggetto: ''
    id_sender: User.id_utente
    id_receiver: 0
    testo: ''
    letto: 0
    report: 0

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

  $scope.sendNewMessage = ()->

    Api.call "post", "messages",
      message: $scope.newMessage,
      success: ->
        do $scope.cancel
        $.notify "Messaggio mandato con successo", "success"

      error: ->
        $.notify "Errore nel mandare il messaggio, riprova più tardi", "error"

  $scope.cancel = ->
    do $modalInstance.dismiss
]
