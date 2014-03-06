Rigorix.controller "Sidebar", ['$scope', 'Api', '$rootScope', ($scope, Api, $rootScope)->

  $scope.topUsers = []

  Api.call "get", "users/top/10",
    success: (json)->
      $scope.topUsers = json.data

  $scope.showBadges = ->
    Api.call "post", "users/" + $rootScope.currentUser.id_utente + "/badges/seen"
]