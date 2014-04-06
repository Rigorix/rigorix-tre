Rigorix.controller "Realtime", ['$scope', 'Api', '$http', 'notify', '$location', '$q', ($scope, Api, $http, notify, $location, $q)->

  $scope.registerUser = ->
    Api.post "realtime/register/"+$scope.currentUser.id_utente,
      success: (json)->
        $scope.registered = true
        $scope.member = json.data.member
        $scope.members = json.data.members

        do $scope.updateMember

      error: ()->
        $scope.registered = false

  $scope.unregisterUser = ->
    Api.post "realtime/unregister/"+$scope.currentUser.id_utente

  $scope.updateMember = ->
    $scope.pollingStopper = do $q.defer
    Api.poll 'get', 'realtime/member',
      timeout: $scope.pollingStopper.promise
      success: (json)->
        $scope.member = json.data.member
        $scope.members = json.data.members
        do $scope.updateMember

      error: ->
        $scope.registered = false
        $scope.member = false
        do $scope.unregisterUser

  do $scope.registerUser
  do $scope.updateMember

  $scope.$watch "member", (val)->
    if val?
      return $location.path "/realtime/room" if $scope.member is false

      $location.path "/realtime/private/"+$scope.member.busy_with if $scope.member.busy_with isnt 0

      if $scope.member.has_request_from isnt 0
        notify.warn "Hai ricevuto una nuova richiesta!"

  #      $modal.open
  #        templateUrl:  '/app/templates/modals/message.html',
  #        controller:    'Realtime.NewRequest',
  #        resolve:
  #          avversario: ->
  #            $scope.member.has_request_from

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.Room", ['$scope', 'notify', '$location', 'Api', ($scope, notify, $location, Api)->

  $scope.userAction = (user)->
    return "sfidaRequestReceived" if $scope.member.has_request_from is user.id_utente
    return "sfidaRequestSent" if user.has_request_from is $scope.member.id_utente
    return "sfidaPossible" if user.id_utente isnt $scope.member.id_utente and user.busy_with is 0


  $scope.doSendSfida = (id_avversario)->
    Api.post "realtime/request/"+id_avversario

  $scope.doAcceptSfida = (id_avversario)->
    Api.post "realtime/accept/"+id_avversario,
      success: ->
        $location.path "/realtime/private/"+id_avversario


  $scope.$watch "registered", (val)->
    notify.success "Sei entrato nella stanza" if val is true
#    notify.error "C'e'; stato un problema nel registrarsi nella stanza!" if val is false

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.Private", ['$scope', 'notify', '$routeParams', 'Api', '$q', ($scope, notify, $routeParams, Api, $q)->

  $scope.accessDenied = false
  $scope.loading = true
  $scope.id_sfidato = parseInt $routeParams.id_sfidato, 10

  $scope.start = ->
    do $scope.pollingStopper.resolve #Stops the polling for members

    $scope.avversario = user for user in $scope.members when user.id_utente is $scope.id_sfidato

    do $scope.startGame

  $scope.pollGame = ->
    $scope.gameStopper = do $q.defer

    Api.poll "get", "realtime/game/"+$scope.sfida.id
      timeout: $scope.gameStopper.promise
      success: (json)->
        console.log "something happened"

        do $scope.pollGame


  $scope.$watch "member", (val)->
    if val? and val isnt false
      Api.get "realtime/sfida/"+$scope.id_sfidato,
        success: (json)->
          $scope.loading = false

          $scope.sfida = json.data
          $scope.accessDenied = !json.data? or json.data is false or $scope.sfida.stato is 2

          do $scope.start

#  Api.get "realtime/sfida/"
#  console.log "Member", $scope.$parent.member
#  $scope.avversario = user for user in $scope.members when user.id_utente is $scope.id_sfidato

#  console.log "", $scope.currentUser.id_utente
#  console.log "", $scope.$parent.member.busy_with
#  console.log "", $scope.avversario.id_utente
#  console.log "", $scope.avversario.busy_with
#  $scope.accessDenied = $scope.avversario.busy_with isnt $scope.currentUser.id_utente or $scope.member.busy_with isnt $scope.avversario.id_utente


#  $scope.accepted = "pending"
#
#  $scope.discardRequest = ->
#    alert "TO BE DONE"
#
#  $scope.$watch "members", (val)->
#    if val?
#      $scope.avversario = user for user in $scope.members when user.id_utente is $scope.id_sfidato
#
#      if $scope.avversario.has_request_from is 0
#        $scope.accepted = no

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.NewRequest", ['$scope', '$modalInstance', '$rootScope', ($scope, $modal, $rootScope )->

  $rootScope.$broadcast "modal:open",
    modalClass: 'modal-success'

#  $modalInstance.result.then ->
#    true
#  , ()->
#    $rootScope.$broadcast "modal:close"

  $scope.close = ->
    do $modalInstance.dismiss

]