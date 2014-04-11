Rigorix.controller "Realtime", ['$scope', 'Api', '$http', 'notify', '$location', '$q', '$rootScope', ($scope, Api, $http, notify, $location, $q, $rootScope)->

  $scope.loading = true


  ## Events ####

  $scope.$on "realtime:registered", (ev, obj)->
    $scope.loading = false
    $scope.member = obj.member
    $scope.members = obj.members

    $location.path "realtime/room"
    $rootScope.$broadcast "realtime:polling:start"

  $scope.$on "realtime:unregistered", (obj)->
    $scope.member = false
    $scope.members = false
    $rootScope.$broadcast "realtime:polling:stop"

  $scope.$on "realtime:updates", (ev, obj)->
    $scope.member = obj.member
    $scope.members = obj.members

    return if obj.member is null or !obj.member?

    if $scope.member.busy_with isnt 0
      $rootScope.$broadcast "realtime:sfida:start", $scope.member.busy_with

  $scope.$on "realtime:sfida:start", (ev, id_sfida)->
    $location.path "/realtime/sfida/"+id_sfida



  ## Methods ####

  $scope.registerUser = ->
    $location.path "realtime/room"

  $scope.unregisterUser = ->
    $rootScope.$broadcast "realtime:unregister"

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.Room", ['$scope', 'notify', '$location', 'Api', '$rootScope', ($scope, notify, $location, Api, $rootScope)->

  if !$scope.member? or $scope.member is false
    $rootScope.$broadcast "realtime:register"

  $scope.userAction = (user)->
    return "sfidaRequestReceived" if $scope.member.has_request_from is user.id_utente
    return "sfidaRequestSent" if user.has_request_from is $scope.member.id_utente
    return "sfidaPossible" if user.id_utente isnt $scope.member.id_utente and user.busy_with is 0

  $scope.doSendSfida = (event, id_avversario)->
    $(event.currentTarget).addClass "disabled"
    Api.post "realtime/request/"+id_avversario

  $scope.doAcceptSfida = (id_avversario)->
    Api.post "realtime/accept/"+id_avversario

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.Sfida", ['$scope', 'notify', '$routeParams', 'RealtimeService', ($scope, notify, $routeParams, RealtimeService)->

  $scope.loading = true
  $scope.accessDenied = false
  $scope.sfidaStatus = "pending"
  $scope.id_sfida = parseInt $routeParams.id_sfida, 10

  $scope.sfida = RealtimeService.sfida.get id: $scope.id_sfida, (data)->
    if data? and data.id?
      if $scope.sfida.id_sfidato is $scope.currentUser.id_utente or $scope.sfida.id_sfidante is $scope.currentUser.id_utente
        $scope.tiri = RealtimeService.tiri.get id: $scope.id_sfida
        $scope.parate = RealtimeService.parate.get id: $scope.id_sfida

        $scope.round =
          index: 1
          type: if $scope.sfida.id_sfidato is $scope.currentUser.id_utente then "tiro" else "parata"

        $scope.loading = false
      else
        do $scope.denyAccess
  , ->
    do $scope.denyAccess

  $scope.denyAccess = ->
    $scope.accessDenied = true

  $scope.startSfida = ->
    $scope.sfidaStatus = "start"

    $scope.sfida.stato = 1
    $scope.sfida.$save id: $scope.id_sfida

    $scope.playRound 0

  $scope.playRound = (index)->
    console.log "ciao"

  $scope.setRoundValue = (value)->
    resource = if $scope.round.type is "tiro" then $scope.tiri else $scope.parate
    resource["o"+$scope.round.index] = value
    resource.$save id: $scope.id_sfida

    $scope.round =
      index: $scope.round++
      type: if $scope.round.type is "parate" then "tiri" else "parate"

    do $scope.$apply


]

