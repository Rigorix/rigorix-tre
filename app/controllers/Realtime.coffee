Rigorix.controller "Realtime", ['$scope', 'Api', '$http', 'notify', '$location', '$q', '$rootScope', ($scope, Api, $http, notify, $location, $q, $rootScope)->

  ## Events ####

  $scope.$on "realtime:registered", (ev, obj)->
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

  $scope.doSendSfida = (id_avversario)->
    Api.post "realtime/request/"+id_avversario

  $scope.doAcceptSfida = (id_avversario)->
    Api.post "realtime/accept/"+id_avversario

]


#-----------------------------------------------------------------------------------------------------------------------


Rigorix.controller "Realtime.Sfida", ['$scope', 'notify', '$routeParams', 'Api', '$q', ($scope, notify, $routeParams, Api, $q)->

  $scope.loading = true
  $scope.id_sfida = parseInt $routeParams.id_sfida, 10

  Api.get "realtime/sfida/"+$scope.id_sfida,
    success: (json)->
      if json.data.stato? and json.data.stato is 0
        $scope.sfida = json.data
        $scope.loading = false

]

