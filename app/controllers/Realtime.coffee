Rigorix.controller "Realtime", ['$scope', 'Api', '$http', ($scope, Api, $http)->

  Api.post "realtime/register/"+$scope.currentUser.id_utente,
    success: (json)->
      $scope.registered = true
      $scope.members = json.data

    error: ()->
      $scope.registered = false

  $scope.updateMembers = ->
    Api.poll 'get', 'realtime/members',
      timeout: 120000
      success: (json)->
        $scope.members = json.data
        do $scope.updateMembers

  do $scope.updateMembers

]

Rigorix.controller "Realtime.Room", ['$scope', 'notify', ($scope, notify)->

  $scope.$watch "registered", (val)->
    notify.success "Sei entrato nella stanza" if val is true
    notify.error "C'e'; stato un problema nel registrarsi nella stanza!" if val is false

]