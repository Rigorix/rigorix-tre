Rigorix.controller 'Messages', ($scope, $rootScope, Api, UserServiceNew, $modal)->

  Api.call "get", "users/" + $scope.currentUser.id_utente + "/messages/unread",
    count: RigorixConfig.messagesPerPage
    success: (json)->
      $scope.messages = json.data

#  $scope.messages = UserServiceNew.get
#    id_utente: User.id_utente
#    parameter: 'messages'
#    count: RigorixConfig.messagesPerPage

  $scope.$on "user:update", ->
    do $scope.updateMessages

  $scope.$on "message:deleted", (event, message)->
    do $scope.updateMessages

  $scope.updateMessages = ->
    $scope.messages = UserServiceNew.get
      id_utente: User.id_utente
      parameter: 'messages'
      count: RigorixConfig.messagesPerPage

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

#  Pagination
  $scope.page = 1
  $scope.currentPage = 1
  $scope.totMessages = Number $scope.currentUser.totMessages



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
    ,
    (response)->
      if response.status == 'success'
        $.notify "Risposta mandata con successo", "success"
        $rootScope.$broadcast "modal:close"
        do $modalInstance.dismiss
      else
        $.notify "Errore nel spedire la risposta.<br>Riprova pi$ugrave; tardi.", "error"


  $scope.discard = ->
    $scope.isTextCollapsed = true
    $scope.editMode = false

  $scope.delete = ->
    AppService.deleteMessage
      param2: message.id_mess
    ,
    (json)->
      if json.status == 'ok'
        do $modalInstance.dismiss
        $.notify "Messaggio cancellato correttamente", "success"
        $rootScope.$broadcast "message:deleted", message
      else
        $.notify "Errore nel cancellare il messaggio", "error"

  $scope.cancel = ->
    do $modalInstance.dismiss



#-----------------------------------------------------------------------------------------------------------------------



Rigorix.controller 'Message.Modal.New', ($scope, $modal, $modalInstance, $rootScope, $http, Api)->

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
    $http.get(RigorixEnv.API_DOMAIN + "users/search/username/" + usernameQuery).then (json)->
      users = []
      for i,user of json.data
        users.push user

      return users

  $scope.onSelectUser = (obj)->
    console.log "obj, ", obj

  $modalInstance.result.then () ->
    true
  , ()->
    $rootScope.$broadcast "modal:close"

  $scope.sendNewMessage = (newMessage)->
    $http.post(RigorixEnv.API_DOMAIN + "messages/new/",
      message: newMessage
    ).then (json)->
      alert "inserito correttamente"
      console.log "json", json

  $scope.cancel = ->
    do $modalInstance.dismiss